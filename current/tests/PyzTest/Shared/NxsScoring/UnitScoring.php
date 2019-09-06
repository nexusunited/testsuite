<?php declare(strict_types=1);


namespace PyzTest\Shared\NxsScoring;


use Codeception\Test\Unit;
use PHPUnit\Framework\TestResult;

class UnitScoring extends Unit
{

    public function getAnnotations(): array
    {
        $annotations = parent::getAnnotations();
        return $annotations;
    }


    public function run(TestResult $result = null): TestResult
    {
        $result = parent::run($result);

        $maxScore = 0;
        $currentScore = 0;

        $passed = $result->passed();

        $failure = $result->failures();


        return $result;
    }

}