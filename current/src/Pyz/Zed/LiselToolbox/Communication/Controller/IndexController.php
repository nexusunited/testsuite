<?php

namespace Pyz\Zed\LiselToolbox\Communication\Controller;

use ArrayObject;
use DateTime;
use Exception;
use Generated\Shared\Transfer\DhlImportTransfer;
use Generated\Shared\Transfer\DhlPieceEventTransfer;
use Generated\Shared\Transfer\DhlPieceStatusPublicListTransfer;
use Generated\Shared\Transfer\DhlPieceStatusPublicTransfer;
use Generated\Shared\Transfer\DhlStatusTransfer;
use Generated\Shared\Transfer\NxsDhlImportDetailsTransfer;
use Orm\Zed\DhlImport\Persistence\NxsDhlImportDetailsQuery;
use Orm\Zed\DhlImport\Persistence\NxsDhlImportIndexQuery;
use Orm\Zed\DhlStatus\Persistence\NxsDhlStatus;
use Orm\Zed\DhlStatus\Persistence\NxsDhlStatusQuery;
use Orm\Zed\LiselStopStatus\Persistence\NxsStopStatusQuery;
use Orm\Zed\LiselTime\Persistence\NxsTimeQuery;
use Orm\Zed\LiselTour\Persistence\NxsStopQuery;
use Orm\Zed\LiselTour\Persistence\NxsTourQuery;
use Pyz\Zed\DhlImport\Business\Model\Steps\TriggerFirstDhlStep;
use Pyz\Zed\DhlImport\DhlImportConfig;
use Pyz\Zed\DhlNotification\Business\DhlNotificationFacade;
use Pyz\Zed\DhlStatus\Business\Model\Steps\TriggerDhlUpdate;
use Pyz\Zed\DhlStatus\DhlStatusConfig;
use Pyz\Zed\Lisel\Communication\Controller\V2Controller;
use Pyz\Zed\ScenarioLogger\Business\ScenarioLoggerFacade;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;
use function json_encode;

/**
 * @method \Shared\Zed\LiselToolbox\Communication\LiselToolboxCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    public const LISEL_URL = '/Lisel/v2';
    public const ACTION = 'POST';
    public const TOUR_ACTION = 'tourAction';
    public const TIME_ACTION = 'timeAction';
    public const STOP_STATUS_ACTION = 'statusAction';
    public const API_USER = 'Nexus';
    public const API_PW = 'Nexus123';

    /**
     * @var string
     */
    private $stopId = 'nxsstop1';

    /**
     * @var string
     */
    private $tourNr = 'NXSTOUR1';

    /**
     * @var string
     */
    private $cNr = 'nxscust1';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $ptaresponse = '';
        $etaresponse = '';
        $ataresponse = '';
        $dhlresponse = '';
        $dhliceresponse = '';
        $deleteresponse = '';
        $startWorkerResponse = '';

        if ($request->isMethod('POST') && $request->request->has('sendPtaRequest')) {
            $pta = $request->get('pta');
            $tourid = $request->get('tourid');
            $stopid = $request->get('stopid');
            $temp = $request->get('temp');

            if ($pta !== '' && $tourid !== '' && $stopid !== '' && $temp !== '') {
                $tour = json_decode($this->getExampleTourRequest(), true);
                $stop = json_decode($this->getExampleStopRequest(), true);
                $tour['TourNummer'] = $tourid;
                $stop['Pta'] = $pta;
                $stop['StoppId'] = $stopid;
                $stop['AuftragListe'][0]['Temperaturbereich'] = (int)$temp;
                $tour['StoppListe'][] = $stop;
                $tourBody = json_encode($tour);
                $apiTourRequest = $this->getLiselApiRequest($tourBody);
                $apiTourRequest->request->set('_route', self::LISEL_URL);
                $ptaresponse = $this->callAction(self::TOUR_ACTION, $apiTourRequest);
            }
        }

        if ($request->isMethod('POST') && $request->request->has('sendEtaRequest')) {
            $eta = $request->get('eta');
            $stopid = $request->get('stopid');

            if ($eta !== '' && $stopid !== '') {
                $time = json_decode($this->getExampleTimeRequest(), true);
                $time['SingleTimeMessages'][0]['StoppId'] = $stopid;
                $time['SingleTimeMessages'][0]['Eta'] = $eta;

                $etaBody = json_encode($time);
                $apiTimeRequest = $this->getLiselApiRequest($etaBody);
                $apiTimeRequest->request->set('_route', self::LISEL_URL);
                $etaresponse = $this->callAction(self::TIME_ACTION, $apiTimeRequest);
            }
        }

        if ($request->isMethod('POST') && $request->request->has('sendAtaRequest')) {
            $ata = $request->get('ata');
            $stopid = $request->get('stopid');
            $code = $request->get('statuscode');

            if ($ata !== '' && $stopid !== '' && $code !== '') {
                $status = json_decode($this->getExampleStopStatusRequest(), true);
                $status['StoppId'] = $stopid;
                $status['Status'] = (int)$code;
                $status['Ata'] = $ata;

                $statusBody = json_encode($status);
                $apiStatusRequest = $this->getLiselApiRequest($statusBody);
                $apiStatusRequest->request->set('_route', self::LISEL_URL);
                $ataresponse = $this->callAction(self::STOP_STATUS_ACTION, $apiStatusRequest);
            }
        }

        if ($request->isMethod('POST') && $request->request->has('startWorker')) {
            try {
                $this->getFactory()->getLiselAnalyserFacade()->startWorker();
            } catch (Exception $e) {
                $startWorkerResponse = $e->getMessage();
            }
        }

        if ($request->isMethod('POST') && $request->request->has('sendDhlRequest')) {
            try {
                $dhldeliverynumber = $request->get('dhldeliverynumber');
                $dhlpta = $request->get('dhlpta');

                if ($dhldeliverynumber !== '' && $dhlpta !== '') {
                    $importIndex = NxsDhlImportIndexQuery::create()
                        ->filterByKey($this->cNr)
                        ->findOneOrCreate();
                    if ($importIndex->isNew()) {
                        $importIndex->setKey($this->cNr);
                        $importIndex->save();
                    }

                    $nxsDhlImportDetailsEntityTransfer = new NxsDhlImportDetailsTransfer();
                    $nxsDhlImportDetailsEntityTransfer->setDeliveryNumber($dhldeliverynumber);
                    $nxsDhlImportDetailsEntityTransfer->setCustomerNumber($this->cNr);
                    $dhlptaDate = (new DateTime($dhlpta))->format('Y-m-d H:i:s');
                    $nxsDhlImportDetailsEntityTransfer->setDeliveryTimestamp($dhlptaDate);
                    $nxsDhlImportDetailsEntityTransfer->setDelivered(null);
                    $nxsDhlImportDetailsEntityTransfer->setPostCode('101010');
                    $nxsDhlImportDetailsEntityTransfer->setFkNxsDhlImportIndexId($importIndex->getNxsDhlImportIndexId());
                    $nxsDhlImportDetailsEntityTransfer = $this->getFactory()
                        ->getDhlImportFacade()
                        ->updateDhlImportDetails($nxsDhlImportDetailsEntityTransfer);
                    $triggerFirstDhlStep = new TriggerFirstDhlStep(new ScenarioLoggerFacade(), new DhlNotificationFacade(), new DhlImportConfig());
                    $dhlImportTransfer = new DhlImportTransfer();
                    $nxsDhlImportDetailsEntities = new ArrayObject();
                    $nxsDhlImportDetailsEntities->append($nxsDhlImportDetailsEntityTransfer);
                    $dhlImportTransfer->setNxsDhlImportDetails($nxsDhlImportDetailsEntities);
                    $triggerFirstDhlStep->process($dhlImportTransfer);
                }
            } catch (Exception $e) {
                $dhlresponse = $e->getMessage();
            }
        }

        if ($request->isMethod('POST') && $request->request->has('sendDhlIceRequest')) {
            try {
                $dhldeliverynumber = $request->get('dhldeliverynumber');
                $dhlice = $request->get('dhlice');
                $dhlicetimestamp = $request->get('dhlicetimestamp');

                if ($dhldeliverynumber !== '' && $dhlice !== '') {
                    $dhlImportDetails = NxsDhlImportDetailsQuery::create()
                        ->filterByDeliveryNumber($dhldeliverynumber)
                        ->findOneOrCreate();

                    $nxsDhlStatus = new NxsDhlStatus();
                    $nxsDhlStatus->setFkNxsDhlImportDetailsId($dhlImportDetails->getNxsDhlImportDetailsId());
                    $nxsDhlStatus->setIce($dhlice);
                    $nxsDhlStatus->setEventLocation('Langenfeld');
                    $nxsDhlStatus->setEventCountry('Deutschland');
                    $nxsDhlStatus->setStandardEventCode('VA');
                    $nxsDhlStatus->setRic('NRQRD');
                    $nxsDhlStatus->setRuecksendung('false');
                    $nxsDhlStatus->setEventText($this->cNr);
                    $nxsDhlStatus->setEventTimestamp($dhlicetimestamp);
                    $nxsDhlStatus->save();
                    $nxsDhlStatus->reload();

                    $triggerDhlUpdate = new TriggerDhlUpdate(
                        new ScenarioLoggerFacade(),
                        new DhlNotificationFacade(),
                        new DhlStatusConfig()
                    );
                    $statusTransfer = new DhlStatusTransfer();
                    $statusTransfer->setNxsDhlImportDetails((new NxsDhlImportDetailsTransfer())->fromArray($dhlImportDetails->toArray(), true));
                    $dhlStatusListTransfer = new DhlPieceStatusPublicListTransfer();
                    $dhlPieceStatusPublicTransfer = new DhlPieceStatusPublicTransfer();
                    $dhlPieceStatusPublicTransfer->addDhlPieceEvent((new DhlPieceEventTransfer())->fromArray($nxsDhlStatus->toArray(), true));

                    $dhlStatusListTransfer->setDhlPieceStatusPublic($dhlPieceStatusPublicTransfer);
                    $statusTransfer->setResponse($dhlStatusListTransfer->toArray());
                    $statusTransfer->fromArray($nxsDhlStatus->toArray(), true);
                    $triggerDhlUpdate->process($statusTransfer);
                }
            } catch (Exception $e) {
                $dhliceresponse = $e->getMessage();
            }
        }

        if ($request->isMethod('POST') && $request->request->has('delete')) {
            $stopid = $request->get('stopid');
            $tourid = $request->get('tourid');

            if ($tourid !== '') {
                $tourQuery = NxsTourQuery::create();
                $tours = $tourQuery->findByTourNummer($tourid);
                $stoppIds = [];
                foreach ($tours as $tour) {
                    $stops = $tour->getNxsStops();
                    foreach ($stops as $stop) {
                        $stoppIds[] = $stop->getStoppId();
                    }
                }
                $tourQuery->delete();

                foreach ($stoppIds as $oneStop) {
                    $this->deleteInStopTables($oneStop);
                }
            }

            if ($stopid !== '') {
                $this->deleteInStopTables($stopid);
            }

            if ($stopid === '' && $tourid === '') {
                $nxsStops = NxsStopQuery::create();
                $stopps = $nxsStops->findByCustomerNr($this->cNr);
                $stoppIds = [];

                foreach ($stopps as $stop) {
                    $stoppIds[] = $stop->getStoppId();
                    $tour = NxsTourQuery::create()->findByIdNxsTour($stop->getFkIdNxsTour())->delete();
                }

                foreach ($stoppIds as $oneStop) {
                    $this->deleteInStopTables($oneStop);
                }
            }

            NxsDhlImportDetailsQuery::create()
                ->filterByCustomerNumber($this->cNr)
                ->delete();

            NxsDhlImportIndexQuery::create()
                ->filterByKey($this->cNr)
                ->delete();

            NxsDhlStatusQuery::create()
                ->filterByEventText($this->cNr)
                ->delete();

            $deleteresponse = 'OK';
        }

        return $this->viewResponse([
            'ptaresponse' => $ptaresponse,
            'dhliceresponse' => $dhliceresponse,
            'etaresponse' => $etaresponse,
            'dhlresponse' => $dhlresponse,
            'ataresponse' => $ataresponse,
            'deleteresponse' => $deleteresponse,
            'startworker' => $startWorkerResponse,
        ]);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function generateRandomString($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @param string $body $body
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getLiselApiRequest(string $body)
    {
        return Request::create(
            self::LISEL_URL,
            Request::METHOD_POST,
            [],
            [],
            [],
            ['PHP_AUTH_USER' => self::API_USER, 'PHP_AUTH_PW' => self::API_PW],
            $body
        );
    }

    /**
     * @param string $action $action
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return mixed
     */
    private function callAction($action, $request)
    {
        return $this->createController()->$action($request);
    }

    /**
     * @param string $stopid $stopid
     *
     * @return void
     */
    private function deleteInStopTables($stopid)
    {
        $nxsTimeQuery = NxsTimeQuery::create();
        $nxsTimeQuery->findByStoppId($stopid)->delete();

        $nxsStopStatusQuery = NxsStopStatusQuery::create();
        $nxsStopStatusQuery->findByStoppId($stopid)->delete();

        $nxsStop = NxsStopQuery::create();
        $nxsStop->findByStoppId($stopid)->delete();
    }

    /**
     * @return string
     */
    private function getExampleTourRequest(): string
    {
        return '{ "TourNummer": "' . $this->tourNr . '", "DcName": "My DcName", "TourDate": "2017-12-04UTC03:30:00", "Vehicle": "GÖ LL 1234", "TourType": 0, "StoppListe": [  ] }';
    }

    /**
     * @return string
     */
    private function getExampleStopStatusRequest(): string
    {
        return '{ "StoppId": "' . $this->stopId . '", "Status": 0, "Ata": "" }';
    }

    /**
     * @return string
     */
    private function getExampleTimeRequest(): string
    {
        return '{ "SingleTimeMessages": [{ "MessageId": "' . $this->generateRandomString(20) . '", "StoppId": "' . $this->stopId . '", "Eta": "", "Ata": "", "Event": 10, "Lat": "51.12312", "Lon": "9.234", "TimeStamp": "2017-12-05UTC05:18:13", "ScemId": "234234" } ] }';
    }

    /**
     * @return string
     */
    private function getExampleStopRequest(): string
    {
        return '{ "StoppId": "' . $this->stopId . '", "Name": "DFF Solutions", "Street": "Berliner Str. 12", "City": "Göttingen", "Zip": "37073", "Pta": "", "CustomerNr": "' . $this->cNr . '", "DeliveryNumber": "666777555", "InvoiceNumber": "XYZ-345-RTZ", "AuftragListe": [ { "Sortiment": 5, "Temperaturbereich": 0, "ThmAnzahl": 2, "OrderNo": "918273Z", "ArtikelListe": [ { "Name": "Cola", "ArtNo": "5551" } ] } ] }';
    }

    /**
     * @return \Shared\Zed\Lisel\Communication\Controller\V2Controller
     */
    private function createController(): V2Controller
    {
        return new V2Controller();
    }
}
