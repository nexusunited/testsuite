<?php

namespace Pyz\Zed\RestRequest\Business;

use Pyz\Zed\RestRequest\Plugin\ValidationRuleCollection;

interface RestRequestFacadeInterface
{
    /**
     * @param string $request
     *
     * @return array
     */
    public function getData(string $request): array;

    /**
     * @param string $request
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleCollection $rules
     *
     * @return array
     */
    public function validateData(string $request, ValidationRuleCollection $rules): array;
}
