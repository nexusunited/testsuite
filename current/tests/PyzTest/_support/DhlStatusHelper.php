<?php

namespace PyzTest\_support;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DhlPieceEventTransfer;
use Generated\Shared\Transfer\DhlPieceStatusPublicListTransfer;
use Generated\Shared\Transfer\DhlPieceStatusPublicTransfer;
use Generated\Shared\Transfer\DhlPublicRequestTransfer;
use Generated\Shared\Transfer\DhlStatusTransfer;
use Pyz\Zed\DhlClient\Business\DhlClientFacade;
use Pyz\Zed\DhlClient\Business\Model\Request\GetStatusForPublicUser;
use Pyz\Zed\DhlImport\Business\DhlImportFacade;
use Pyz\Zed\DhlStatus\Business\DhlStatusBusinessFactory;
use Pyz\Zed\DhlStatus\Business\DhlStatusFacade;
use Pyz\Zed\DhlStatus\Business\Queue\Producer\DhlQueueProducer;
use Pyz\Zed\DhlStatus\DhlStatusConfig;
use Pyz\Zed\DhlStatus\DhlStatusDependencyProvider;
use Pyz\Zed\DhlStatus\Persistence\DhlStatusEntityManager;
use Pyz\Zed\DhlStatus\Persistence\DhlStatusRepository;
use Spryker\Zed\Kernel\Container;

class DhlStatusHelper extends Unit
{
    /**
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @return \Generated\Shared\Transfer\DhlStatusTransfer
     */
    public function createDhlStatusTransfer(): DhlStatusTransfer
    {
        $dhlStatus = new DhlStatusTransfer();
        $dhlClientRequestData = new DhlPublicRequestTransfer();
        $dhlClientRequestData->setPieceCode('INTEGRATION TEST PieceCode');
        $dhlClientRequestData->setZipCode('INTEGRATION TEST ZipCode');
        $dhlClientRequestData->setFromDate('INTEGRATION TEST FromDate');
        $dhlClientRequestData->setToDate('INTEGRATION TEST ToDate');
        $dhlClientRequestData->setGetIcons(false);
        $dhlStatus->setCreated(date('Y-m-d H:i:s'));
        $dhlStatus->setName(GetStatusForPublicUser::NAME);
        $dhlStatus->setTransfer($dhlClientRequestData->toArray());

        $publicListTransfer = $this->createDhlPieceStatusPublicListTransfer();
        $dhlStatus->setResponse($publicListTransfer->toArray());
        return $dhlStatus;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\DhlStatus\Business\DhlStatusFacade
     */
    public function createDhlStatusFacadeMock(): DhlStatusFacade
    {
        $dhlImportFacadeMock = $this->getMockBuilder(DhlStatusFacade::class)
            ->setMethods(['getFactory'])
            ->getMock();
        $dhlImportFacadeMock->method('getFactory')->willReturn($this->createDhlStatusBusinessFactoryMock());

        return $dhlImportFacadeMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\DhlStatus\Business\DhlStatusBusinessFactory
     */
    public function createDhlStatusBusinessFactoryMock(): DhlStatusBusinessFactory
    {
        $dhlStatusBusinessMock = $this->getMockBuilder(DhlStatusBusinessFactory::class)
            ->setMethods(['createQueueProducer', 'getRepository', 'getDhlClient', 'getDhlImportFacade', 'getEntityManager', 'getConfig'])
            ->getMock();
        $dhlStatusBusinessMock->method('createQueueProducer')->willReturn($this->createDhlQueueProducerMock());
        $dhlStatusBusinessMock->method('getRepository')->willReturn(new DhlStatusRepository());
        $dhlStatusBusinessMock->method('getDhlClient')->willReturn($this->createDhlClientFacadeMock());
        $dhlStatusBusinessMock->method('getDhlImportFacade')->willReturn(new DhlImportFacade());
        $dhlStatusBusinessMock->method('getEntityManager')->willReturn(new DhlStatusEntityManager());
        $dhlStatusBusinessMock->method('getConfig')->willReturn(new DhlStatusConfig());

        $container = new Container();
        $dependencyProvider = new DhlStatusDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dhlStatusBusinessMock->setContainer($container);

        return $dhlStatusBusinessMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\DhlClient\Business\DhlClientFacade
     */
    public function createDhlClientFacadeMock(): DhlClientFacade
    {
        $dhlImportFacadeMock = $this->getMockBuilder(DhlClientFacade::class)
            ->setMethods(['send'])
            ->getMock();

        $dhlImportFacadeMock->method('send')->willReturn($this->createDhlStatusTransfer());

        return $dhlImportFacadeMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\DhlStatus\Business\Queue\Producer\DhlQueueProducer
     */
    public function createDhlQueueProducerMock(): DhlQueueProducer
    {
        $dhlImportFacadeMock = $this->getMockBuilder(DhlQueueProducer::class)
            ->disableOriginalConstructor()
            ->setMethods(['sendMessage'])
            ->getMock();

        $dhlImportFacadeMock->expects($this->exactly(2))->method('sendMessage');
        return $dhlImportFacadeMock;
    }

    /**
     * @return \Generated\Shared\Transfer\DhlPieceStatusPublicListTransfer
     */
    private function createDhlPieceStatusPublicListTransfer(): DhlPieceStatusPublicListTransfer
    {
        $publicListTransfer = new DhlPieceStatusPublicListTransfer();
        $publicTransfer = new DhlPieceStatusPublicTransfer();
        $pieceEvent = new DhlPieceEventTransfer();
        $pieceEvent->setIce('TEST');
        $pieceEvents = new ArrayObject();
        $pieceEvents->append($pieceEvent);
        $publicTransfer->setDhlPieceEvents($pieceEvents);
        $publicListTransfer->setDhlPieceStatusPublic($publicTransfer);
        return $publicListTransfer;
    }
}
