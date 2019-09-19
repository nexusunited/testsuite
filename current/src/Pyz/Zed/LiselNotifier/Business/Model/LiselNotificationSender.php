<?php

namespace Pyz\Zed\LiselNotifier\Business\Model;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\CommunicationDetailsTransfer;
use Generated\Shared\Transfer\ScenarioLogTransfer;
use Generated\Shared\Transfer\StoppListeTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Pyz\Shared\Log\LoggerTrait;
use Pyz\Zed\CustomerImport\Business\CustomerImportFacadeInterface;
use Pyz\Zed\LiselEvent\Plugin\LiselEventInterface;
use Pyz\Zed\LiselNotifier\LiselNotifierConfig;
use Pyz\Zed\LiselNotifier\Persistence\LiselNotifierQueryContainerInterface;
use Pyz\Zed\LiselTime\Business\LiselTimeFacadeInterface;
use Pyz\Zed\LiselTour\Business\LiselTourFacadeInterface;
use Pyz\Zed\Scenario\Business\ScenarioFacadeInterface;
use Pyz\Zed\ScenarioLogger\Business\ScenarioLoggerFacadeInterface;
use function count;
use function in_array;

class LiselNotificationSender
{
    use LoggerTrait;

    /**
     * @var \Shared\Zed\Scenario\Business\ScenarioFacadeInterface
     */
    private $scenarioFacade;

    /**
     * @var \Shared\Zed\CustomerImport\Business\CustomerImportFacade
     */
    private $customerImportFacade;

    /**
     * @var \Shared\Zed\ScenarioLogger\Business\ScenarioLoggerFacadeInterface
     */
    private $scenarioLoggerFacade;

    /**
     * @var \Generated\Shared\Transfer\StoppListeTransfer
     */
    private $stopListeTransfer;

    /**
     * @var \Shared\Zed\LiselTour\Business\LiselTourFacadeInterface
     */
    private $liselTourFacade;

    /**
     * @var \Shared\Zed\LiselTime\Business\LiselTimeFacadeInterface
     */
    private $liselTimeFacade;

    /**
     * @var string
     */
    private $notificationLimit;

    /**
     * @var \Shared\Zed\LiselNotifier\Persistence\LiselNotifierQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @var \Shared\Zed\LiselNotifier\LiselNotifierConfig
     */
    private $liselNotifierConfig;

    /**
     * @param \Shared\Zed\Scenario\Business\ScenarioFacadeInterface $scenarioFacade
     * @param \Shared\Zed\CustomerImport\Business\CustomerImportFacadeInterface $customerImportFacade
     * @param \Shared\Zed\ScenarioLogger\Business\ScenarioLoggerFacadeInterface $scenarioLoggerFacade
     * @param \Shared\Zed\LiselTour\Business\LiselTourFacadeInterface $tourFacade
     * @param \Shared\Zed\LiselTime\Business\LiselTimeFacadeInterface $timeFacade
     * @param string $notificationLimit
     * @param \Shared\Zed\LiselNotifier\Persistence\LiselNotifierQueryContainerInterface $queryContainer
     * @param \Shared\Zed\LiselNotifier\LiselNotifierConfig $liselNotifierConfig
     */
    public function __construct(
        ScenarioFacadeInterface $scenarioFacade,
        CustomerImportFacadeInterface $customerImportFacade,
        ScenarioLoggerFacadeInterface $scenarioLoggerFacade,
        LiselTourFacadeInterface $tourFacade,
        LiselTimeFacadeInterface $timeFacade,
        string $notificationLimit,
        LiselNotifierQueryContainerInterface $queryContainer,
        LiselNotifierConfig $liselNotifierConfig
    ) {
        $this->scenarioFacade = $scenarioFacade;
        $this->customerImportFacade = $customerImportFacade;
        $this->scenarioLoggerFacade = $scenarioLoggerFacade;
        $this->liselTourFacade = $tourFacade;
        $this->liselTimeFacade = $timeFacade;
        $this->notificationLimit = $notificationLimit;
        $this->queryContainer = $queryContainer;
        $this->liselNotifierConfig = $liselNotifierConfig;
    }

    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $liselEvent
     *
     * @return void
     */
    public function notify(LiselEventInterface $liselEvent): void
    {
        try {
            $this->determineStopListeTransfer($liselEvent);
            $this->notifyCustomers($liselEvent);
        } catch (Exception $e) {
            $this->logError($e->getMessage(), ['Unhandled Exception ' . print_r($e->getMessage(), true)]);
        }
    }

    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $liselEvent
     *
     * @return void
     */
    private function determineStopListeTransfer(LiselEventInterface $liselEvent): void
    {
        if (!$liselEvent->getTransferData() instanceof StoppListeTransfer) {
            $this->stopListeTransfer = $this->liselTourFacade->getStopById($liselEvent->getLiselEventTransfer()->getStoppId());
        } else {
            $this->stopListeTransfer = $liselEvent->getTransferData();
        }
    }

    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $responsibleEvent
     *
     * @return void
     */
    private function notifyCustomers(LiselEventInterface $responsibleEvent): void
    {
        $customerCollection = $this->getCustomerToCustomerNr($this->stopListeTransfer->getCustomerNr());
        $eventName = $responsibleEvent->getLiselEventTransfer()->getEventName();
        $nonRegisteredCustomerEvents = $this->liselNotifierConfig->getAllowedEventsForNonRegisteredCustomers();

        if (count($customerCollection) > 0) {
            $first = true;
            foreach ($customerCollection as $spyCustomer) {
                $limitNotReached = $this->checkNotificationLimit($spyCustomer, $responsibleEvent);

                if ($responsibleEvent->canBeNotified($spyCustomer)) {
                    if ($first) {
                        $this->writeScenarioLog($responsibleEvent);
                        $first = false;
                    }

                    if ($limitNotReached) {
                        $this->scenarioFacade->notifyCustomerByEntity(
                            $responsibleEvent->getType(),
                            $this->createCommunicationDetailsTransfer($responsibleEvent, $this->stopListeTransfer),
                            $spyCustomer
                        );
                        $this->writeEventCountEntry($spyCustomer, $responsibleEvent);
                    }
                }
            }
        } elseif (in_array($eventName, $nonRegisteredCustomerEvents, true)) {
            $this->writeScenarioLog($responsibleEvent);
        }
    }

    /**
     * @param string $customerNr
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer[]|\ArrayObject
     */
    private function getCustomerToCustomerNr(string $customerNr): ArrayObject
    {
        return $this->customerImportFacade->getSpyCustomersByNumber($customerNr);
    }

    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $responsibleEvent
     * @param \Generated\Shared\Transfer\StoppListeTransfer $stoppListeTransfer
     *
     * @return \Generated\Shared\Transfer\CommunicationDetailsTransfer
     */
    private function createCommunicationDetailsTransfer(
        LiselEventInterface $responsibleEvent,
        StoppListeTransfer $stoppListeTransfer
    ): CommunicationDetailsTransfer {
        $communicationDetails = new CommunicationDetailsTransfer();
        $communicationDetails->setDate($responsibleEvent->getLiselEventTransfer()->getDateTime());
        $communicationDetails->setDeliveryNumber($stoppListeTransfer->getDeliveryNumber());
        $communicationDetails->setStop($stoppListeTransfer);

        return $communicationDetails;
    }

    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $liselEvent
     *
     * @return void
     */
    private function writeScenarioLog(LiselEventInterface $liselEvent): void
    {
        $scenarioLog = new ScenarioLogTransfer();
        $scenarioLog->setEventName($liselEvent->getType());
        $scenarioLog->setCustomerNumber($this->stopListeTransfer->getCustomerNr());
        $scenarioLog->setDateTime($liselEvent->getLiselEventTransfer()->getDateTime());
        $scenarioLog->setStoppId($this->stopListeTransfer->getStoppId());
        $scenarioLog->setDeliveryNumber($this->stopListeTransfer->getDeliveryNumber());
        $this->addScemId($scenarioLog);
        $this->addTourNumber($scenarioLog);
        $this->scenarioLoggerFacade->saveScenarioLog($scenarioLog);
    }

    /**
     * @param \Generated\Shared\Transfer\ScenarioLogTransfer $scenarioLog
     *
     * @return void
     */
    private function addScemId(ScenarioLogTransfer $scenarioLog): void
    {
        try {
            $scenarioLog->setScemId($this->liselTimeFacade->getScemId($this->stopListeTransfer->getStoppId()));
        } catch (Exception $e) {
            $scenarioLog->setScemId(null);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ScenarioLogTransfer $scenarioLog
     *
     * @return void
     */
    private function addTourNumber(ScenarioLogTransfer $scenarioLog): void
    {
        try {
            $scenarioLog->setTourNumber($this->liselTourFacade->getTourNumber($this->stopListeTransfer->getStoppId()));
        } catch (Exception $e) {
            $scenarioLog->setTourNumber(null);

            if (!empty($this->stopListeTransfer->getTourNummerTemp())) {
                $scenarioLog->setTourNumber($this->stopListeTransfer->getTourNummerTemp());
            }
        }
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $spyCustomer
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $liselEvent
     *
     * @return bool
     */
    private function checkNotificationLimit(SpyCustomer $spyCustomer, LiselEventInterface $liselEvent): bool
    {
        $eventsQuery = $this->queryContainer->createNxsLiselEventsQuery();
        $eventTransfer = $liselEvent->getLiselEventTransfer();

        $count = $eventsQuery
            ->filterByArray([
                'idCustomer' => $spyCustomer->getIdCustomer(),
                'stoppId' => $eventTransfer->getStoppId(),
                'event' => $eventTransfer->getEventName(),
            ])->count();

        $limit = (int)$this->notificationLimit;

        return ($count < $limit);
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $spyCustomer
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventInterface $liselEvent
     *
     * @return void
     */
    private function writeEventCountEntry(SpyCustomer $spyCustomer, LiselEventInterface $liselEvent): void
    {
        $eventTransfer = $liselEvent->getLiselEventTransfer();
        $eventName = $eventTransfer->getEventName();

        if (in_array($eventName, $this->liselNotifierConfig->getLimitedEvents(), true)) {
            $nxsLiselEvent = $this->queryContainer->createNxsLiselEvents();
            $nxsLiselEvent
                ->setIdCustomer($spyCustomer->getIdCustomer())
                ->setStoppId($eventTransfer->getStoppId())
                ->setEvent($eventName);

            $nxsLiselEvent->save();
        }
    }
}
