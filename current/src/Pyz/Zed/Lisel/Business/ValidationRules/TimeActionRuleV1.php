<?php

namespace Pyz\Zed\Lisel\Business\ValidationRules;

use Pyz\Zed\RestRequest\Plugin\ValidationRuleInterface;

class TimeActionRuleV1 implements ValidationRuleInterface
{
    public const TIME_BASE_ACTION = 'timebaseaction';

    /**
     * @return string
     */
    public function filePath(): string
    {
        return realpath(__DIR__ . '/Schema/time_schemav1.json');
    }

    /**
     * @return string
     */
    public function ruleName(): string
    {
        return self::TIME_BASE_ACTION;
    }
}
