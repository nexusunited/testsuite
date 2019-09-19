<?php

namespace Pyz\Zed\Lisel\Business\ValidationRules;

use Pyz\Zed\RestRequest\Plugin\ValidationRuleInterface;

class StatusActionRuleV1 implements ValidationRuleInterface
{
    public const STATUS_BASE_ACTION = 'statusbaseaction';

    /**
     * @return string
     */
    public function filePath(): string
    {
        return realpath(__DIR__ . '/Schema/status_schemav1.json');
    }

    /**
     * @return string
     */
    public function ruleName(): string
    {
        return self::STATUS_BASE_ACTION;
    }
}
