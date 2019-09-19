<?php

namespace Pyz\Zed\RestRequest\Plugin;

use ArrayIterator;

class ValidationRuleCollection extends ArrayIterator
{
    /**
     * @param \Shared\Zed\RestRequest\Plugin\ValidationRuleInterface ...$rules
     */
    public function __construct(ValidationRuleInterface ...$rules)
    {
        parent::__construct($rules);
    }

    /**
     * @return \Shared\Zed\RestRequest\Plugin\ValidationRuleInterface
     */
    public function current(): ValidationRuleInterface
    {
        return parent::current();
    }

    /**
     * @param object $offset
     *
     * @return \Shared\Zed\RestRequest\Plugin\ValidationRuleInterface
     */
    public function offsetGet($offset): ValidationRuleInterface
    {
        return parent::offsetGet($offset);
    }
}
