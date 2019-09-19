<?php

namespace PyzTest\_support;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CommunicationChannel\Persistence\NxsCommunicationChannelToCustomer;
use Orm\Zed\CommunicationChannel\Persistence\NxsCommunicationChannelToCustomerQuery;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\CustomerImport\Persistence\NxsCustomerEmailToNumber;
use Orm\Zed\Scenario\Persistence\NxsScenarioToSpyCustomer;
use Portal\Zed\Customer\Business\CustomerFacade;
use Pyz\Zed\Settings\Business\SettingsFacade;
use Spryker\Zed\Locale\Business\LocaleFacade;

class CustomerHelper extends Unit
{
    public const CUSTOMER_EMAIL = 'integration@test.de';
    public const PHONE_NUMBER = '+491774798801';
    public const CUSTOMER_NUMBER = 'integration12345678';

    /**
     * @var \Shared\Zed\Settings\Business\SettingsFacade
     */
    private $settingsFacade;

    /**
     * @var \Portal\Zed\Customer\Business\CustomerFacade
     */
    private $customerFacade;

    public function __construct()
    {
        parent::__construct();
        $this->settingsFacade = new SettingsFacade();
        $this->customerFacade = new CustomerFacade();
    }

    /**
     * @param int $localeId
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function addCustomer(int $localeId = 154): CustomerTransfer
    {
        $localeFacade = new LocaleFacade();
        $localeTransfer = $localeFacade->getLocaleById($localeId);

        $preparedCustomerTransfer = $this->prepareCustomer($localeId);
        $customerTransfer = $this->customerFacade->addCustomer($preparedCustomerTransfer)->getCustomerTransfer();

        $reference = explode('_', $localeTransfer->getLocaleName());

        $referenceCustomer = $reference[1] . '--' . $customerTransfer->getIdCustomer();
        SpyCustomerQuery::create()
            ->filterByIdCustomer($customerTransfer->getIdCustomer())
            ->update(['CustomerReference' => $referenceCustomer]);

        $this->addAllScenarioToCustomer($customerTransfer);
        return $customerTransfer;
    }

    /**
     * @param int $localeId
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function prepareCustomer(int $localeId = 154): CustomerTransfer
    {
        $customerTransfer = (new CustomerBuilder())->build();

        $localeFacade = new LocaleFacade();
        $localeTransfer = $localeFacade->getLocaleById($localeId);

        $customerTransfer->setLocale($localeTransfer);
        $customerTransfer->setCustomerNumber(self::CUSTOMER_NUMBER);
        $this->addEmailToNumber($customerTransfer, $customerTransfer->getCustomerNumber());
        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    public function getSpyCustomer(CustomerTransfer $customerTransfer): SpyCustomer
    {
        return SpyCustomerQuery::create()
            ->findOneByIdCustomer(
                $customerTransfer->getIdCustomer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string $customerNumber
     * @param bool $upperCaseTest
     *
     * @return int
     */
    public function addEmailToNumber(CustomerTransfer $customerTransfer, string $customerNumber, bool $upperCaseTest = false)
    {
        $number = new NxsCustomerEmailToNumber();

        if ($upperCaseTest) {
            $email = strtolower($customerTransfer->getEmail());
        } else {
            $email = $customerTransfer->getEmail();
        }
        $number->setEmail($email);
        $number->setNumber($customerNumber);
        return $number->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer $customerTransfer
     *
     * @return bool
     */
    public function addAllScenarioToCustomer(CustomerTransfer $customerTransfer): bool
    {
        $scenariosSaved = true;
        for ($i = 1; $i < 4; $i++) {
            $scenario = new NxsScenarioToSpyCustomer();
            $scenario->setIdFkNxsScenario($i);
            $scenario->setIdFkCustomer($customerTransfer->getIdCustomer());
            if ($scenario->save() <= 0) {
                $scenariosSaved = false;
            }
        }
        return $scenariosSaved;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Orm\Zed\CommunicationChannel\Persistence\NxsCommunicationChannelToCustomer
     */
    public function addEmailChannel(CustomerTransfer $customerTransfer): NxsCommunicationChannelToCustomer
    {
        $channel = new NxsCommunicationChannelToCustomer();
        $channel->setData(json_encode(['channel_id' => 1, 'email_address' => self::CUSTOMER_EMAIL, 'email_address_checkbox' => true]));
        $channel->setIdFkNxsCommunicationChannel(1);
        $channel->setParentIdFkCustomer($customerTransfer->getIdCustomer());
        $channel->save();

        $customerChannel = NxsCommunicationChannelToCustomerQuery::create()->findOneByParentIdFkCustomer($customerTransfer->getIdCustomer());

        return $customerChannel;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Orm\Zed\CommunicationChannel\Persistence\NxsCommunicationChannelToCustomer
     */
    public function addSmsChannel(CustomerTransfer $customerTransfer): NxsCommunicationChannelToCustomer
    {

        $channel = new NxsCommunicationChannelToCustomer();
        $channel->setData(json_encode(['channel_id' => 2, 'phone_number' => self::PHONE_NUMBER, 'phone_number_checkbox' => true]));
        $channel->setIdFkNxsCommunicationChannel(2);
        $channel->setParentIdFkCustomer($customerTransfer->getIdCustomer());
        $channel->save();

        $customerChannel = NxsCommunicationChannelToCustomerQuery::create()->findOneByParentIdFkCustomer($customerTransfer->getIdCustomer());

        return $customerChannel;
    }

    /**
     * @param int $length $length
     *
     * @return string
     */
    private function generateRandomString(int $length = 10)
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
     * @param int $customerId
     * @param \DateTime $dateTime
     *
     * @return int
     */
    public function changeLastActivity(int $customerId, DateTime $dateTime): int
    {
        return SpyCustomerQuery::create()
            ->findOneByIdCustomer($customerId)
            ->setLastActivity($dateTime)
            ->save();
    }

    /**
     * @param int $localeId
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerWithChannelAndScenario(int $localeId = 154): CustomerTransfer
    {
        $customerTransfer = $this->addCustomer($localeId);
        $emailChannel = $this->addEmailChannel($customerTransfer);
        $this->assertEquals($emailChannel->getParentIdFkCustomer(), $customerTransfer->getIdCustomer());
        return $customerTransfer;
    }
}
