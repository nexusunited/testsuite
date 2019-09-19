<?php

namespace Pyz\Zed\Lisel\Business\ValidationRules;

use Pyz\Zed\RestRequest\Plugin\ValidationRuleInterface;

class TourActionRuleV2 implements ValidationRuleInterface
{
    public const TOUR_BASE_ACTION = 'tourbaseaction';

    /**
     * @return string
     */
    public function filePath(): string
    {
        return realpath(__DIR__ . '/Schema/tour_schemav2.json');
    }

    /**
     * @return string
     */
    public function ruleName(): string
    {
        return self::TOUR_BASE_ACTION;
    }
}
