<?php

namespace Pyz\Zed\HelloSpryker\Business\Model;

use Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface;

interface GeneratorCollectionInterface
{
    /**
     * @return \Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface
     */
    public function current(): GeneratorInterface;

    /**
     * @param string $offset
     *
     * @return \Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface
     */
    public function offsetGet($offset): GeneratorInterface;
}
