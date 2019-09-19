<?php

namespace PyzTest\_support;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AuftragListeBuilder;
use Generated\Shared\DataBuilder\LiselTourBuilder;
use Generated\Shared\DataBuilder\StoppListeBuilder;
use Generated\Shared\Transfer\LiselStopStatusTransfer;
use Generated\Shared\Transfer\LiselTimeMessagesTransfer;
use Generated\Shared\Transfer\LiselTourTransfer;
use Orm\Zed\LiselRequest\Persistence\Base\NxsLiselRequestQuery;
use Orm\Zed\LiselRequest\Persistence\NxsLiselRequest;
use Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomerQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Pyz\Zed\LiselAnalyser\Business\LiselAnalyserFacade;
use Pyz\Zed\LiselRequest\Business\LiselRequestFacade;
use Pyz\Zed\LiselStopStatus\Business\LiselStopStatusFacade;
use Pyz\Zed\LiselTime\Business\LiselTimeFacade;
use Pyz\Zed\LiselTour\Business\LiselTourFacade;

class RequestHelper extends Unit
{
    public const TOTAL_STOPS = 3;

    /**
     * @var bool
     */
    private $hasStops = false;

    /**
     * @var \Shared\Zed\LiselRequest\Business\LiselRequestFacade
     */
    private $liselRequestFacade;

    /**
     * @var \Shared\Zed\LiselAnalyser\Business\LiselAnalyserFacade
     */
    private $liselAnalyser;

    /**
     * @var \PyzTest\_support\CustomerHelper
     */
    private $customerHelper;

    /**
     * @var \Shared\Zed\LiselTour\Business\LiselTourFacade
     */
    private $tourFacade;

    /**
     * @var \Shared\Zed\LiselStopStatus\Business\LiselStopStatusFacade
     */
    private $stopStatusFacade;

    /**
     * @var \Shared\Zed\LiselTime\Business\LiselTimeFacade
     */
    private $timeFacade;

    /**
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->liselRequestFacade = new LiselRequestFacade();
        $this->liselAnalyser = new LiselAnalyserFacade();
        $this->customerHelper = new CustomerHelper();
        $this->tourFacade = new LiselTourFacade();
        $this->stopStatusFacade = new LiselStopStatusFacade();
        $this->timeFacade = new LiselTimeFacade();
    }

    /**
     * @return \Generated\Shared\Transfer\LiselTourTransfer
     */
    public function createValidTourTransfer()
    {
        $stopList = new ArrayObject();
        for ($i = 1; $i <= self::TOTAL_STOPS; $i++) {
            $stopList->append($this->createSampleStop());
        }

        $tour = $this->getTourBuilder()->build();
        $tour->setTourDate(date('Y-m-d H:i:s', strtotime('+2 hours')));
        $tour->setStoppListe($stopList);

        return $tour;
    }

    /**
     * @return \Generated\Shared\DataBuilder\LiselTourBuilder
     */
    private function getTourBuilder()
    {
        return new LiselTourBuilder();
    }

    /**
     * @return \Generated\Shared\DataBuilder\StoppListeBuilder
     */
    private function getStopBuilder()
    {
        return new StoppListeBuilder();
    }

    /**
     * @return \Generated\Shared\DataBuilder\AuftragListeBuilder
     */
    private function getAuftragBuilder()
    {
        return new AuftragListeBuilder();
    }

    /**
     * @return \Generated\Shared\Transfer\StoppListeTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function createSampleStop()
    {
        $auftraege = new ArrayObject([
            $this->getAuftragBuilder()->withArtikel()->withAnotherArtikel()->build(),
            $this->getAuftragBuilder()->withArtikel()->build(),
            $this->getAuftragBuilder()->withArtikel()->withAnotherArtikel()->withAnotherArtikel()->build(),
        ]);

        $stop = $this->getStopBuilder()->build();
        $stop->setPta(date('Y-m-d H:i:s', strtotime('+2 hours')));
        $stop->setAuftragListe($auftraege);

        return $stop;
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTourTransfer $tourTransfer
     *
     * @return \Generated\Shared\Transfer\LiselTourTransfer
     */
    public function createTour(LiselTourTransfer $tourTransfer): LiselTourTransfer
    {
        $this->tourFacade->storeRequest($tourTransfer->toArray());

        return $tourTransfer;
    }

    /**
     * @param string $msg
     *
     * @return \Orm\Zed\LiselRequest\Persistence\NxsLiselRequest
     */
    public function getLiselRequestByMessage(string $msg): NxsLiselRequest
    {
        return NxsLiselRequestQuery::create()->findOneByMessage($msg);
    }

    /**
     * @param int $localeId
     *
     * @return \Generated\Shared\Transfer\LiselTourTransfer
     */
    public function createTourWithCustomer(int $localeId)
    {
        $tourTransfer = $this->createValidTourTransfer();
        $customerTransfer = $this->customerHelper->createCustomerWithChannelAndScenario($localeId);
        $tourTransfer->getStoppListe()[0]->setCustomerNr($customerTransfer->getCustomerNumber());
        return $this->createTour($tourTransfer);
    }

    /**
     * @param string $pta
     *
     * @return \Generated\Shared\Transfer\LiselTourTransfer
     */
    public function createTourWithPta(string $pta)
    {
        $tourTransfer = $this->createValidTourTransfer();
        $tourTransfer->getStoppListe()[0]->setPta($pta);
        return $this->createTour($tourTransfer);
    }

    /**
     * @param int $localeId
     * @param array $scenarios
     *
     * @return \Generated\Shared\Transfer\LiselTourTransfer
     */
    public function createTourWithCustomerAndOneScenario(int $localeId, array $scenarios): LiselTourTransfer
    {
        $tourTransfer = $this->createValidTourTransfer();
        $customerTransfer = $this->customerHelper->createCustomerWithChannelAndScenario($localeId);

        $nxsScenario = new NxsScenarioToSpyCustomerQuery();
        $scenariosToCustomer = $nxsScenario->findByIdFkCustomer($customerTransfer->getIdCustomer());

        $this->filterScenariosToCustomer($scenarios, $scenariosToCustomer);

        $tourTransfer->getStoppListe()[0]->setCustomerNr($customerTransfer->getCustomerNumber());
        return $this->createTour($tourTransfer);
    }

    /**
     * @param array $scenarios $scenarios
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomer[] $scenariosToCustomer $scenariosToCustomer
     *
     * @return void
     */
    private function filterScenariosToCustomer(array $scenarios, ObjectCollection $scenariosToCustomer): void
    {
        foreach ($scenariosToCustomer as $nxsScToSpyCustomer) {
            if (!in_array($nxsScToSpyCustomer->getIdFkNxsScenario(), $scenarios, true)) {
                $nxsScToSpyCustomer->delete();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\LiselStopStatusTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\LiselStopStatusTransfer
     */
    public function createStopStatusMessage(LiselStopStatusTransfer $transfer): LiselStopStatusTransfer
    {
        $this->stopStatusFacade->storeRequest($transfer->toArray());

        return $transfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LiselTimeMessagesTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\LiselTimeMessagesTransfer
     */
    public function createTimeMessage(LiselTimeMessagesTransfer $transfer): LiselTimeMessagesTransfer
    {
        $this->timeFacade->storeRequest($transfer->toArray());

        return $transfer;
    }

    /**
     * @param string[] $existingEvents
     * @param \Orm\Zed\ScenarioLogger\Persistence\NxsScenarioLogging[] $scenarioLogs $scenarioLogs
     *
     * @return bool
     */
    public function existInScenarioLogging(array $existingEvents, array $scenarioLogs): bool
    {
        $allFound = false;
        foreach ($existingEvents as $existingEventKey => $existingEventName) {
            $gotScenario = false;
            foreach ($scenarioLogs as $scenarioLogKey => $scenarioLog) {
                if (!$gotScenario && $scenarioLog->getEventName() === $existingEventName) {
                    unset($existingEvents[$existingEventKey], $scenarioLogs[$scenarioLogKey]);
                    $gotScenario = true;
                }
            }
        }

        if (count($existingEvents) === 0) {
            $allFound = true;
        }
        return $allFound;
    }
}
