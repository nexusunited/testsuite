<?php

namespace Pyz\Zed\HelloSpryker\Business\Model\Generator;

interface GeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}
