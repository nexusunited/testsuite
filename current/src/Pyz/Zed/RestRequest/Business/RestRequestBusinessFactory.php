<?php

namespace Pyz\Zed\RestRequest\Business;

use Pyz\Zed\RestRequest\Business\Model\Request;
use Pyz\Zed\RestRequest\RestRequestDependencyProvider;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class RestRequestBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var \Shared\Zed\RestRequest\Business\Model\Request
     */
    private $request;

    /**
     * @return \Shared\Zed\RestRequest\Business\Model\Request
     */
    public function createRequest(): Request
    {
        if ($this->request === null) {
            $this->request = new Request($this->getUtilEncondingService());
        }
        return $this->request;
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    private function getUtilEncondingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(RestRequestDependencyProvider::JSON_SERVICE);
    }
}
