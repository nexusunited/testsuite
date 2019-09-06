<?php


namespace Pyz\Zed\HelloSpryker\Business\Model;

use ArrayIterator;
use Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface;

class GeneratorCollection extends ArrayIterator implements GeneratorCollectionInterface
{
    /**
     * @param \Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface ...$generators
     */
    public function __construct(GeneratorInterface ...$generators)
    {
        parent::__construct($generators);
    }

    /**
     * @return \Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface
     */
    public function current() : GeneratorInterface
    {
        return parent::current();
    }

    /**
     * @param string $offset
     *
     * @return \Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface
     */
    public function offsetGet($offset) : GeneratorInterface
    {
        return parent::offsetGet($offset);
    }
}
