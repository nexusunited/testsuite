<?php

namespace PyzTest\_support;

use Codeception\Test\Unit;
use DOMDocument;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Pyz\Zed\DhlClient\Business\DhlClientBusinessFactory;
use Pyz\Zed\DhlClient\Business\DhlClientFacade;
use Pyz\Zed\DhlClient\Business\Model\XmlValidatorInterface;
use Pyz\Zed\DhlClient\DhlClientConfig;
use Pyz\Zed\DhlClient\DhlClientDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Twig_Environment;

class DhlClientHelper extends Unit
{
    public const REQUEST_HEADER = '<?xml version="1.0" encoding="UTF-8" ?>
        <data appname="TEST APP"
              password="TEST PW"
              request="TEST REQUEST"
              language-code="TEST CODE"
              get-icons="1"
              from-date="2018-01-01"
              to-date="2018-01-01">
            <data piece-code="TESTPIECECODE"></data>
        </data>';

    public const RESPONSE_BODY = '<?xml version="1.0" encoding="UTF-8"?>
        <data request-id="c96ad227-4a89-4e35-8700-7a06a338728e">
            <data name="piece-status-public-list" code="0" _piece-code="00340088160019875022" _zip-code="">
                <data name="piece-status-public" piece-identifier="340088160019875022" _build-time="2018-08-13 16:36:09.000641" piece-id="93ee022a-eab0-47ae-a98c-1ffa89134132" leitcode="" pslz-nr="8797145842" order-preferred-delivery-day="false" searched-piece-code="00340088160019875022" piece-status="0" identifier-type="2" recipient-name="" recipient-id="" recipient-id-text="" pan-recipient-name="" street-name="" house-number="" city-name="" last-event-timestamp="10.08.2018 20:59" shipment-type="" status-next="Die Sendung wird in der Nachverpackungsstelle bearbeitet." status="Aufgrund einer Beschädigung verzögert sich der Transport der Sendung." error-status="0" delivery-event-flag="0" upu="" international-flag="0" piece-code="00340088160019875022" matchcode="" domestic-id="" airway-bill-number="" ice="" ric="" division="" icon-pos="3" icon-id="3" dest-country="DE" origin-country="DE" product-code="00" product-name="DHL PAKET" searched-ref-nr="" standard-event-code="" pan-recipient-street="" pan-recipient-city="" event-country="" event-location="Bruchsal" preferred-delivery-day="" preferred-delivery-timeframe-from="" preferred-delivery-timeframe-to="" preferred-timeframe-refused-text="" shipment-length="0.0" shipment-width="0.0" shipment-height="0.0" shipment-weight="28.521">
                <data name="piece-event" event-timestamp="03.08.2018 14:22" event-status="Die Auftragsdaten zu dieser Sendung wurden vom Absender elektronisch an DHL übermittelt." event-text="Die Auftragsdaten zu dieser Sendung wurden vom Absender elektronisch an DHL übermittelt." ice="PARCV" ric="NRQRD" event-location="" event-country="" standard-event-code="VA" ruecksendung="false" />
                <data name="piece-event" event-timestamp="03.08.2018 17:14" event-status="Die Sendung wurde im Start-Paketzentrum bearbeitet." event-text="Die Sendung wurde im Start-Paketzentrum bearbeitet." ice="LDTMV" ric="MVMTV" event-location="Aschheim" event-country="Deutschland" standard-event-code="AA" ruecksendung="false" />
                <data name="piece-event" event-timestamp="04.08.2018 03:30" event-status="Die Sendung wurde im Ziel-Paketzentrum bearbeitet." event-text="Die Sendung wurde im Ziel-Paketzentrum bearbeitet." ice="ULFMV" ric="UNLDD" event-location="Bruchsal" event-country="Deutschland" standard-event-code="EE" ruecksendung="false" />
                <data name="piece-event" event-timestamp="04.08.2018 08:41" event-status="Die Sendung wurde in das Zustellfahrzeug geladen." event-text="Die Sendung wurde in das Zustellfahrzeug geladen." ice="SRTED" ric="NRQRD" event-location="" event-country="" standard-event-code="PO" ruecksendung="false" />
                <data name="piece-event" event-timestamp="04.08.2018 08:42" event-status="Die Sendung wurde beschädigt und wird zur Nachverpackung in das Paketzentrum zurückgesandt." event-text="Die Sendung wurde beschädigt und wird zur Nachverpackung in das Paketzentrum zurückgesandt." ice="DLVRF" ric="DMGED" event-location="" event-country="Deutschland" standard-event-code="ZN" ruecksendung="false" />
                <data name="piece-event" event-timestamp="10.08.2018 20:59" event-status="Aufgrund einer Beschädigung verzögert sich der Transport der Sendung." event-text="Aufgrund einer Beschädigung verzögert sich der Transport der Sendung." ice="" ric="" event-location="Bruchsal" event-country="Deutschland" standard-event-code="" ruecksendung="false" />
                </data>
            </data>
        </data>';

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\DhlClient\Business\Model\XmlValidatorInterface
     */
    public function createXmlValidatorMock(): XmlValidatorInterface
    {
        $dhlImportFacadeMock = $this->getMockBuilder(XmlValidatorInterface::class)
            ->setMethods(['validate'])
            ->getMock();
        $dhlImportFacadeMock->method('validate')->willReturn($this->createDOMDocumentResponse());

        return $dhlImportFacadeMock;
    }

    /**
     * @return \DOMDocument
     */
    public function createDOMDocumentResponse(): DOMDocument
    {
        $xml = new DOMDocument();
        $xml->loadXML(self::RESPONSE_BODY);
        return $xml;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Shared\Zed\DhlClient\Business\DhlClientBusinessFactory
     */
    public function createDhlClientBusinessFactoryMock(): DhlClientBusinessFactory
    {
        $dhlImportFacadeMock = $this->getMockBuilder(DhlClientBusinessFactory::class)
            ->setMethods(['getHttpClient', 'getConfig'])
            ->getMock();
        $dhlImportFacadeMock->method('getHttpClient')->willReturn($this->createClientMock());
        $dhlImportFacadeMock->method('getConfig')->willReturn(new DhlClientConfig());

        $container = new Container();
        $dependencyProvider = new DhlClientDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dhlImportFacadeMock->setContainer($container);
        $container[DhlClientDependencyProvider::RENDERER] = $this->createTwigEnvironmentMock();
        return $dhlImportFacadeMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\GuzzleHttp\Client
     */
    public function createClientMock(): Client
    {
        $dhlImportFacadeMock = $this->getMockBuilder(Client::class)
            ->setMethods(['request'])
            ->getMock();
        $dhlImportFacadeMock->method('request')->willReturn($this->createResponseMock());
        return $dhlImportFacadeMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\GuzzleHttp\Psr7\Response
     */
    public function createResponseMock(): Response
    {
        $dhlImportFacadeMock = $this->getMockBuilder(Response::class)
            ->setMethods(['getBody'])
            ->getMock();
        $dhlImportFacadeMock->method('getBody')->willReturn($this->createStreamInterfaceMock());
        return $dhlImportFacadeMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\GuzzleHttp\Psr7\Stream
     */
    public function createStreamInterfaceMock(): Stream
    {
        $dhlImportFacadeMock = $this->getMockBuilder(Stream::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContents'])
            ->getMock();
        $dhlImportFacadeMock->method('getContents')->willReturn(DhlClientHelper::RESPONSE_BODY);
        return $dhlImportFacadeMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Twig_Environment
     */
    public function createTwigEnvironmentMock()
    {
        $twigEnvironmentMock = $this->getMockBuilder(Twig_Environment::class)->disableOriginalConstructor()->setMethods(['render'])->getMock();
        $twigEnvironmentMock
            ->method('render')
            ->willReturn(DhlClientHelper::REQUEST_HEADER);

        return $twigEnvironmentMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Portal\Zed\DhlClient\Business\DhlClientFacade
     */
    public function createDhlClientFacadeMock(): DhlClientFacade
    {
        $dhlImportFacadeMock = $this->getMockBuilder(DhlClientFacade::class)
            ->setMethods(['getFactory'])
            ->getMock();
        $dhlImportFacadeMock->method('getFactory')->willReturn($this->createDhlClientBusinessFactoryMock());

        return $dhlImportFacadeMock;
    }
}
