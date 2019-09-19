<?php

namespace PyzTest\_support;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\NxsDhlImportDetailsBuilder;
use Generated\Shared\Transfer\NxsDhlImportDetailsTransfer;
use Orm\Zed\DhlImport\Persistence\NxsDhlImportDetailsQuery;
use Orm\Zed\DhlImport\Persistence\NxsDhlImportIndexQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Pyz\Zed\DhlImport\Business\DhlImportBusinessFactory;
use Pyz\Zed\DhlImport\Business\DhlImportFacade;
use Pyz\Zed\DhlImport\DhlImportConfig;
use Pyz\Zed\DhlImport\DhlImportDependencyProvider;
use Pyz\Zed\DhlImport\Persistence\DhlImportEntityManager;
use Pyz\Zed\DhlImport\Persistence\DhlImportRepository;
use Spryker\Zed\Kernel\Container;

class DhlImportHelper extends Unit
{
    /**
     * @var string
     */
    public $validFileExample1 = "787|17|100|128|20180827|1626|5012345013|DPAG-EDICC|SAPL01_030|5012345013_27_V01_128_20180827.dat|20180827162625||
                    787|17|200|1|128|20180827|162625|5012345013||V01|27|03|6446||10029471||||||
                    787|17|210|5012345013|Lekkerland Oberhausen|Tor 8||Im Lekkerland|||Oberhausen|46147|DE||||||||||||||
                    787|17|300|1|DELIVERYNUMBER_TEST1|||1||1||||||||40327623774+99000900145006|1689||||||
                    787|17|310|1|CUSTOMER_NUMBER_TEST1|Kunde XYZ|KoSt: 2479||Tollbrettkoppel|6||Heiligenhafen|POSTCODE1|DE||||||||||||||||||||||||||||||||||||
                    787|17|400|1|1|PK|8.898|||||00340088300013440639|0010028177_20180418|
                    787|17|300|2|DELIVERYNUMBER_TEST2|||1||1||||||||40327622179+99000900021111|1689||||||
                    787|17|310|2|CUSTOMER_NUMBER_TEST2|Kunde XYZ|KoSt: 2536||Haldesdorfer Str.|111 -||Hamburg|POSTCODE2|DE||||||||||||||||||||||||||||||||||||
                    787|17|400|2|1|PK|10.712|||||00340088300013442077|0010028177_20180418|";

    /**
     * @var string
     */
    public $invalidFileExample1 = "
                    TEST INVALID LINE
                    TEST LINE INVALID
                    INTEGRATION TEST
                    DONT IMPORT TEST";

    public const INTEGRATION_KEY_NAME = 'INTEGRATION KEY';

    /**
     * @return void
     */
    public function cleanDb(): void
    {
        NxsDhlImportIndexQuery::create()->find()->delete();
        NxsDhlImportDetailsQuery::create()->find()->delete();
    }

    /**
     * @param string $sshBody
     *
     * @return void
     */
    public function import(string $sshBody): void
    {
        $this->createDhlImportFacadeMock($sshBody)->import();
    }

    /**
     * @return \Generated\Shared\Transfer\NxsDhlImportDetailsTransfer
     */
    public function importTestData(): NxsDhlImportDetailsTransfer
    {
        $dhlImportIndex = NxsDhlImportIndexQuery::create()
            ->filterByKey(self::INTEGRATION_KEY_NAME)
            ->findOneOrCreate();

        if ($dhlImportIndex->isNew()) {
            $dhlImportIndex->setKey(self::INTEGRATION_KEY_NAME);
            $dhlImportIndex->save();
            $dhlImportIndex->reload();
        }

        $dhlImportDetails = NxsDhlImportDetailsQuery::create()
            ->filterByNxsDhlImportDetailsId($dhlImportIndex->getNxsDhlImportIndexId())
            ->findOneOrCreate();

        $detailsTransfer = new NxsDhlImportDetailsTransfer();
        if ($dhlImportDetails->isNew()) {
            $detailsBuilder = new NxsDhlImportDetailsBuilder();
            $detailsTransfer = $detailsBuilder->build();
            $detailsTransfer->setFkNxsDhlImportIndexId($dhlImportIndex->getNxsDhlImportIndexId());
            $dhlImportDetails->fromArray($detailsTransfer->toArray());
            $dhlImportDetails->save();
            $dhlImportDetails->reload();
        }
        $detailsTransfer->fromArray($dhlImportDetails->toArray(), true);

        return $detailsTransfer;
    }

    /**
     * @param string $sshBody
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\DhlImport\Business\DhlImportFacade
     */
    public function createDhlImportFacadeMock(string $sshBody): DhlImportFacade
    {
        $dhlImportFacadeMock = $this->getMockBuilder(DhlImportFacade::class)
            ->setMethods(['getFactory'])
            ->getMock();
        $dhlImportFacadeMock->method('getFactory')->willReturn($this->createDhlImportBusinessFactoryMock($sshBody));

        return $dhlImportFacadeMock;
    }

    /**
     * @param string $sshBody
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\DhlImport\Business\DhlImportBusinessFactory
     */
    public function createDhlImportBusinessFactoryMock(string $sshBody): DhlImportBusinessFactory
    {
        $dhlBusinessFactoryMock = $this->getMockBuilder(DhlImportBusinessFactory::class)
            ->setMethods([ 'getConfig', 'getEntityManager', 'getRepository', 'getSshFacade'])
            ->getMock();
        $dhlBusinessFactoryMock->method('getConfig')->willReturn(new DhlImportConfig());
        $dhlBusinessFactoryMock->method('getEntityManager')->willReturn(new DhlImportEntityManager());
        $dhlBusinessFactoryMock->method('getRepository')->willReturn(new DhlImportRepository());
        $dhlBusinessFactoryMock->method('getSshFacade')->willReturn((new SshHelper())->createSshFacadeMock($sshBody));

        $container = new Container();
        $dependencyProvider = new DhlImportDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dhlBusinessFactoryMock->setContainer($container);

        return $dhlBusinessFactoryMock;
    }

    /**
     * @return \Orm\Zed\DhlImport\Persistence\NxsDhlImportDetails[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getImportDetailsInDb(): ObjectCollection
    {
        $importedFiles = NxsDhlImportIndexQuery::create()
            ->filterByKey(self::INTEGRATION_KEY_NAME)->find();
        $dhlImportDetails = NxsDhlImportDetailsQuery::create()
            ->filterByFkNxsDhlImportIndexId($importedFiles[0]->getNxsDhlImportIndexId())
            ->find();
        return $dhlImportDetails;
    }
}
