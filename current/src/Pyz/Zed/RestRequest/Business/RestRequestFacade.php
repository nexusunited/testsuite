<?php

namespace Pyz\Zed\RestRequest\Business;

use Pyz\Zed\RestRequest\Plugin\ValidationRuleCollection;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\RestRequest\Business\RestRequestBusinessFactory getFactory()
 */
class RestRequestFacade extends AbstractFacade implements RestRequestFacadeInterface
{
    /**
     * @param string $request
     *
     * @return array
     */
    public function getData(string $request): array
    {
        return $this->getFactory()->createRequest()->getData($request);
    }

    /**
     * @param string $request
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleCollection $rules
     *
     * @return array
     */
    public function validateData(string $request, ValidationRuleCollection $rules): array
    {
        return $this->getFactory()->createRequest()->validateData($request, $rules);
    }
}
