<?php

namespace Pyz\Zed\RestRequest\Plugin;

interface ValidationRuleInterface
{
    /**
     * @return string
     */
    public function filePath(): string;

    /**
     * @return string
     */
    public function ruleName(): string;
}
