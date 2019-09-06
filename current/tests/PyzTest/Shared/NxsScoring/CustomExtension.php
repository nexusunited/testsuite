<?php

namespace PyzTest\Shared\NxsScoring;

use \Codeception\Events;

class CustomExtension extends \Codeception\Extension
{
    // list events to listen to
    // Codeception\Events constants used to set the event

    public static $events = array(
        Events::SUITE_AFTER  => 'afterSuite',
        Events::TEST_BEFORE => 'beforeTest',
        Events::STEP_BEFORE => 'beforeStep',
        Events::TEST_FAIL => 'testFailed',
        Events::RESULT_PRINT_AFTER => 'print',
        Events::TEST_FAIL_PRINT => 'printfail',
    );

    // methods that handle events

    public function afterSuite(\Codeception\Event\SuiteEvent $e) {
//        die('afterSuite');
    }

    public function beforeTest(\Codeception\Event\TestEvent $e) {
//        die('beforeTest');
    }

    public function beforeStep(\Codeception\Event\StepEvent $e) {
//        die('beforeStep');
    }

    public function testFailed(\Codeception\Event\FailEvent $e) {
//        die('testFailed');
    }

    public function print(\Codeception\Event\PrintResultEvent $e) {
        $test='test';
        die('PRINT');
    }

    public function printfail(\Codeception\Event\PrintResultEvent $e) {
        $test='test';
        die('PRINT');
    }
}
