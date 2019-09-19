<?php

namespace PyzTest\_support;

use Generated\Shared\DataBuilder\EmailChannelBuilder;
use Generated\Shared\DataBuilder\EmployeeItemBuilder;
use Generated\Shared\DataBuilder\SmsChannelBuilder;
use Generated\Shared\Transfer\CommunicationChannelTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\EmailChannelTransfer;
use Generated\Shared\Transfer\EmployeeItemTransfer;
use Generated\Shared\Transfer\NotificationSettingsTransfer;
use Generated\Shared\Transfer\ScenarioItemTransfer;
use Generated\Shared\Transfer\SmsChannelTransfer;
use Orm\Zed\CommunicationChannel\Persistence\NxsCommunicationChannelToCustomerQuery;
use Orm\Zed\Employee\Persistence\NxsEmployeeQuery;
use Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomerQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Pyz\Shared\CommunicationChannel\CommunicationChannelConstants;
use Pyz\Shared\Settings\SettingsConstants;

class SettingsHelper
{
    /**
     * @var \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public $notificationSettingsTransfer;

    /**
     * @var \Generated\Shared\DataBuilder\SmsChannelBuilder
     */
    private $smsChannelBuilder;

    /**
     * @var \Generated\Shared\DataBuilder\EmailChannelBuilder
     */
    private $emailChannelBuilder;

    /**
     * @var \Generated\Shared\DataBuilder\EmployeeItemBuilder
     */
    private $employeeItemBuilder;

    public function __construct()
    {
        $this->notificationSettingsTransfer = (new NotificationSettingsTransfer())->setApplication(SettingsConstants::ETA_SETTINGS);
        $this->smsChannelBuilder = new SmsChannelBuilder();
        $this->emailChannelBuilder = new EmailChannelBuilder();
        $this->employeeItemBuilder = new EmployeeItemBuilder();
    }

    /**
     * @param bool $checked
     *
     * @return \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public function addSmsChannel(bool $checked = true): NotificationSettingsTransfer
    {
        $this->notificationSettingsTransfer->setSmsChannel($this->createSmsChannel($checked));
        return $this->notificationSettingsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer $customerTransfer
     *
     * @return void
     */
    public function setCustomer(CustomerTransfer $customerTransfer)
    {
        $this->notificationSettingsTransfer->setCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $transfer
     *
     * @return bool
     */
    public function customerHasSmsChannel(NotificationSettingsTransfer $transfer): bool
    {
        $nxsChannel = NxsCommunicationChannelToCustomerQuery::create()
            ->filterByParentIdFkCustomer($transfer->getCustomer()->getIdCustomer())
            ->filterByParentIdFkEmployee(null, Criteria::ISNULL)
            ->filterByIdFkNxsCommunicationChannel(CommunicationChannelConstants::SMS_CHANNEL_ID)
            ->findOne();

        if ($nxsChannel !== null && $nxsChannel->getData() === json_encode($transfer->getSmsChannel()->toArray())) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $transfer
     *
     * @return bool
     */
    public function customerHasEmailChannel(NotificationSettingsTransfer $transfer): bool
    {
        $nxsChannel = NxsCommunicationChannelToCustomerQuery::create()
            ->filterByParentIdFkCustomer($transfer->getCustomer()->getIdCustomer())
            ->filterByParentIdFkEmployee(null, Criteria::ISNULL)
            ->filterByIdFkNxsCommunicationChannel(CommunicationChannelConstants::EMAIL_CHANNEL_ID)
            ->findOne();

        if ($nxsChannel !== null && $nxsChannel->getData() === json_encode($transfer->getEmailChannel()->toArray())) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $transfer
     * @param \Generated\Shared\Transfer\EmployeeItemTransfer $employee
     *
     * @return bool
     */
    public function employeeHasEmailChannel(NotificationSettingsTransfer $transfer, EmployeeItemTransfer $employee): bool
    {
        $nxsChannel = NxsCommunicationChannelToCustomerQuery::create()
            ->filterByParentIdFkCustomer($transfer->getCustomer()->getIdCustomer())
            ->filterByParentIdFkEmployee($this->getEmployeeId($transfer, $employee))
            ->filterByIdFkNxsCommunicationChannel(CommunicationChannelConstants::EMAIL_CHANNEL_ID)
            ->findOne();

        if ($nxsChannel !== null && $nxsChannel->getData() === json_encode($employee->getCommunicationChannel()->getEmailChannel()->toArray())) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $transfer
     * @param \Generated\Shared\Transfer\EmployeeItemTransfer $employee
     *
     * @return bool
     */
    public function employeeHasSmsChannel(NotificationSettingsTransfer $transfer, EmployeeItemTransfer $employee): bool
    {
        $nxsChannel = NxsCommunicationChannelToCustomerQuery::create()
            ->filterByParentIdFkCustomer($transfer->getCustomer()->getIdCustomer())
            ->filterByParentIdFkEmployee($this->getEmployeeId($transfer, $employee))
            ->filterByIdFkNxsCommunicationChannel(CommunicationChannelConstants::SMS_CHANNEL_ID)
            ->findOne();

        if ($nxsChannel !== null && $nxsChannel->getData() === json_encode($employee->getCommunicationChannel()->getSmsChannel()->toArray())) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $settingsTransfer $settingsTransfer
     * @param \Generated\Shared\Transfer\EmployeeItemTransfer $employeeItemTransfer $employeeItemTransfer
     *
     * @return int
     */
    private function getEmployeeId(NotificationSettingsTransfer $settingsTransfer, EmployeeItemTransfer $employeeItemTransfer): int
    {
        $dbEmployee = NxsEmployeeQuery::create()
            ->filterByName($employeeItemTransfer->getName())
            ->filterByIdCustomer($settingsTransfer->getCustomer()->getIdCustomer())
            ->findOne();
        return $dbEmployee->getId();
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $transfer
     *
     * @return bool
     */
    public function customerHasEmailAndSmsChannel(NotificationSettingsTransfer $transfer): bool
    {
        return $this->customerHasSmsChannel($transfer) && $this->customerHasEmailChannel($transfer);
    }

    /**
     * @param bool $checked
     *
     * @return \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public function addEmailChannel(bool $checked = true): NotificationSettingsTransfer
    {
        $this->notificationSettingsTransfer->setEmailChannel($this->createEmailChannel($checked));
        return $this->notificationSettingsTransfer;
    }

    /**
     * @param bool $smsChannel
     * @param bool $emailChannel
     *
     * @return \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public function addEmployee(bool $smsChannel = true, bool $emailChannel = true): NotificationSettingsTransfer
    {
        $communicationChannelTransfer = new CommunicationChannelTransfer();
        $employee = $this->employeeItemBuilder->build();
        if ($smsChannel) {
            $communicationChannelTransfer->setSmsChannel($this->createSmsChannel());
        }
        if ($emailChannel) {
            $communicationChannelTransfer->setEmailChannel($this->createEmailChannel());
        }
        $employee->setCommunicationChannel($communicationChannelTransfer);

        $this->notificationSettingsTransfer->addEmployeeItems($employee);
        return $this->notificationSettingsTransfer;
    }

    /**
     * @param bool $checked
     *
     * @return \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public function addScenario1(bool $checked = true): NotificationSettingsTransfer
    {
        $this->notificationSettingsTransfer->addScenarioItems(
            (new ScenarioItemTransfer())
                ->setId(1)
                ->setName('scenario1')
                ->setCheckbox($checked)
        );
        return $this->notificationSettingsTransfer;
    }

    /**
     * @param bool $checked
     *
     * @return \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public function addScenario2(bool $checked = true): NotificationSettingsTransfer
    {
        $this->notificationSettingsTransfer->addScenarioItems(
            (new ScenarioItemTransfer())
                ->setId(2)
                ->setName('scenario2')
                ->setCheckbox($checked)
        );
        return $this->notificationSettingsTransfer;
    }

    /**
     * @param bool $checked
     *
     * @return \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public function addScenario3(bool $checked = true): NotificationSettingsTransfer
    {
        $this->notificationSettingsTransfer->addScenarioItems(
            (new ScenarioItemTransfer())
                ->setId(3)
                ->setName('scenario3')
                ->setCheckbox($checked)
        );
        return $this->notificationSettingsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $transfer
     *
     * @return bool
     */
    public function customerHasScenarioItems(NotificationSettingsTransfer $transfer): bool
    {
        foreach ($transfer->getScenarioItems() as $scenarioItem) {
            if ($scenarioItem->getCheckbox() && !$this->hasScenarioItem($transfer, $scenarioItem)) {
                return false;
            }

            if (!$scenarioItem->getCheckbox() && $this->hasScenarioItem($transfer, $scenarioItem)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $transfer
     * @param \Generated\Shared\Transfer\ScenarioItemTransfer $scenarioItemTransfer
     *
     * @return bool
     */
    private function hasScenarioItem(NotificationSettingsTransfer $transfer, ScenarioItemTransfer $scenarioItemTransfer): bool
    {
        $dbScenario = NxsScenarioToSpyCustomerQuery::create()
            ->filterByIdFkCustomer($transfer->getCustomer()->getIdCustomer())
            ->filterByIdFkNxsScenario($scenarioItemTransfer->getId())
            ->findOne();

        if ($dbScenario === null) {
            return false;
        }

        $nxsScenario = $dbScenario->getNxsScenario();
        if ($nxsScenario === null) {
            return false;
        }
        if ($nxsScenario->getName() !== $scenarioItemTransfer->getName()) {
            return false;
        }
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\NotificationSettingsTransfer $transfer
     *
     * @return bool
     */
    public function customerHasEmployeeItems(NotificationSettingsTransfer $transfer): bool
    {
        foreach ($transfer->getEmployeeItems() as $employeeItem) {
            $dbEmployee = NxsEmployeeQuery::create()
                ->filterByIdCustomer($transfer->getCustomer()->getIdCustomer())
                ->filterByName($employeeItem->getName())
                ->findOne();

            if ($dbEmployee === null) {
                return false;
            }

            if ($employeeItem->getCommunicationChannel()->getEmailChannel()) {
                if ($employeeItem->getCommunicationChannel()->getEmailChannel()->getEmailAddressCheckbox() && !$this->employeeHasEmailChannel($transfer, $employeeItem)) {
                    return false;
                }
                if (!$employeeItem->getCommunicationChannel()->getEmailChannel()->getEmailAddressCheckbox() && $this->employeeHasEmailChannel($transfer, $employeeItem)) {
                    return false;
                }
            }

            if ($employeeItem->getCommunicationChannel()->getSmsChannel()) {
                if ($employeeItem->getCommunicationChannel()->getSmsChannel()->getSmsChannelCheckbox() && !$this->employeeHasSmsChannel($transfer, $employeeItem)) {
                    return false;
                }
                if (!$employeeItem->getCommunicationChannel()->getSmsChannel()->getSmsChannelCheckbox() && $this->employeeHasSmsChannel($transfer, $employeeItem)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public function addAllSettings(): NotificationSettingsTransfer
    {
        $this->addSmsChannel();
        $this->addEmailChannel();
        $this->addScenario1();
        $this->addScenario2();
        $this->addScenario3();
        $this->addEmployee(true, true);
        $this->addEmployee(false, false);
        $this->addEmployee(true, false);
        $this->addEmployee(false, true);
        return $this->notificationSettingsTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\NotificationSettingsTransfer
     */
    public function unselectAllSettings(): NotificationSettingsTransfer
    {
        $this->notificationSettingsTransfer->getSmsChannel()->setSmsChannelCheckbox(false);
        $this->notificationSettingsTransfer->getEmailChannel()->setEmailAddressCheckbox(false);
        $this->notificationSettingsTransfer->getScenarioItems()[0]->setCheckbox(false);
        $this->notificationSettingsTransfer->getScenarioItems()[1]->setCheckbox(false);
        $this->notificationSettingsTransfer->getScenarioItems()[2]->setCheckbox(false);
        $this->notificationSettingsTransfer->getEmployeeItems()[0]->getCommunicationChannel()->getEmailChannel()->setEmailAddressCheckbox(false);
        $this->notificationSettingsTransfer->getEmployeeItems()[0]->getCommunicationChannel()->getSmsChannel()->setSmsChannelCheckbox(false);
        $this->notificationSettingsTransfer->getEmployeeItems()[2]->getCommunicationChannel()->getSmsChannel()->setSmsChannelCheckbox(false);
        $this->notificationSettingsTransfer->getEmployeeItems()[3]->getCommunicationChannel()->getEmailChannel()->setEmailAddressCheckbox(false);
        return $this->notificationSettingsTransfer;
    }

    /**
     * @param bool $checked
     *
     * @return \Generated\Shared\Transfer\EmailChannelTransfer
     */
    private function createEmailChannel(bool $checked = true): EmailChannelTransfer
    {
        return $this->emailChannelBuilder->build()->setEmailAddressCheckbox($checked);
    }

    /**
     * @param bool $checked
     *
     * @return \Generated\Shared\Transfer\SmsChannelTransfer
     */
    private function createSmsChannel(bool $checked = true): SmsChannelTransfer
    {
        return $this->smsChannelBuilder->build()->setSmsChannelCheckbox($checked);
    }
}
