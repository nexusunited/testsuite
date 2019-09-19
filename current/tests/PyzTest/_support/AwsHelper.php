<?php

namespace PyzTest\_support;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AwsS3ObjectKeyTransfer;
use Generated\Shared\Transfer\AwsS3ObjectListTransfer;
use Generated\Shared\Transfer\AwsS3ObjectTransfer;
use Pyz\Zed\Aws\Business\AwsFacade;

class AwsHelper extends Unit
{
    /**
     * @var string
     */
    private $body = "mG-EDICC|SAPL01_030|5001038112_18_V01_128_20180418.dat|20180418065646||
                    787|17|200|1|128|20180418|065646|5001038112||V01|18|03|6115||10028177||||||
                    787|17|210|5001038112|Lekkerland Deutschland GmbH  & Co.K|Niederlassung Großbeeren||Osdorfer Ring|10||Großbeeren|14979|DE||||||||||||||
                    787|17|300|1|DELIVERYNUMBER_TEST1|||1||1||||||||40327623774+99000900145006|1689||||||
                    787|17|310|1|CUSTOMER_NUMBER_TEST1|Kunde XYZ|KoSt: 2479||Tollbrettkoppel|6||Heiligenhafen|POSTCODE1|DE||||||||||||||||||||||||||||||||||||
                    787|17|400|1|1|PK|8.898|||||00340088300013440639|0010028177_20180418|
                    787|17|300|2|DELIVERYNUMBER_TEST2|||1||1||||||||40327622179+99000900021111|1689||||||
                    787|17|310|2|CUSTOMER_NUMBER_TEST2|Kunde XYZ|KoSt: 2536||Haldesdorfer Str.|111 -||Hamburg|POSTCODE2|DE||||||||||||||||||||||||||||||||||||
                    787|17|400|2|1|PK|10.712|||||00340088300013442077|0010028177_20180418|";

    /**
     * @var string
     */
    private $invalidBodyCodes = "787|17|800|3|DELIVERYNUMBER_TEST2|||1||1||||||||40327622179+99000900021111|1689||||||
                    787|17|810|3|CUSTOMER_NUMBER_TEST2|Kunde XYZ|KoSt: 2536||Haldesdorfer Str.|111 -||Hamburg|POSTCODE2|DE||||||||||||||||||||||||||||||||||||
                    787|17|900|3|1|PK|10.712|||||00340088300013442077|0010028177_20180418|
                    TEST INVALID LINE
                    TEST LINE INVALID
                    INTEGRATION TEST
                    DONT IMPORT TEST";

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\Aws\Business\AwsFacade
     */
    public const INTEGRATION_TEST_BUCKET = 'INTEGRATION TEST - BUCKET';

    public const INTEGRATION_TEST_KEY = 'INTEGRATION TEST - KEY';

    public const INTEGRATION_KEY_IN_OBJECT = 'INTEGRATION KEY';

    /**
     * @param \Generated\Shared\Transfer\AwsS3ObjectTransfer $transfer
     *
     * @return \Shared\Zed\Aws\Business\AwsFacacode:sniff:stylecode:sniff:stylede
     */
    public function createAwsFacadeMock(AwsS3ObjectTransfer $transfer): AwsFacade
    {
        $awsFacade = $this->getMockBuilder(AwsFacade::class)
            ->setMethods(['getObject', 'listObjects'])
            ->getMock();
        $awsFacade->method('getObject')->willReturn($transfer);
        $awsFacade->method('listObjects')->willReturn($this->createAwsS3ObjectListTransfer());
        return $awsFacade;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Generated\Shared\Transfer\AwsS3ObjectListTransfer
     */
    public function createAwsS3ObjectListTransfer(): AwsS3ObjectListTransfer
    {
        $awsS3ObjectTransfer = new AwsS3ObjectListTransfer();
        $awsS3ObjectTransfer->addAwsS3Object($this->createAwsS3ObjectTransfer());
        $awsS3ObjectTransfer->addAwsS3ObjectKey((new AwsS3ObjectKeyTransfer())->setKey(self::INTEGRATION_KEY_IN_OBJECT));
        return $awsS3ObjectTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Generated\Shared\Transfer\AwsS3ObjectListTransfer
     */
    public function createEmptyAwsS3ObjectListTransfer(): AwsS3ObjectListTransfer
    {
        $awsS3ObjectTransfer = new AwsS3ObjectListTransfer();
        $awsS3ObjectTransfer->addAwsS3ObjectKey((new AwsS3ObjectKeyTransfer())->setKey(self::INTEGRATION_KEY_IN_OBJECT));
        return $awsS3ObjectTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Generated\Shared\Transfer\AwsS3ObjectTransfer
     */
    public function createInvalidCodesS3ObjectTransfer(): AwsS3ObjectTransfer
    {
        $awsS3ObjectTransfer = $this->createAwsS3ObjectTransfer();
        $awsS3ObjectTransfer->setResult($this->body . $this->invalidBodyCodes);
        return $awsS3ObjectTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Generated\Shared\Transfer\AwsS3ObjectTransfer
     */
    public function createAwsS3ObjectTransfer(): AwsS3ObjectTransfer
    {
        $awsS3ObjectTransfer = new AwsS3ObjectTransfer();
        $awsS3ObjectTransfer->setBucket(self::INTEGRATION_TEST_BUCKET);
        $awsS3ObjectTransfer->setKey(self::INTEGRATION_TEST_KEY);
        $awsS3ObjectTransfer->setResult($this->body);
        return $awsS3ObjectTransfer;
    }
}
