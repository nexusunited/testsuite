<?php

namespace PyzTest\Shared\NxsScoring;

use Codeception\Event\PrintResultEvent;
use Codeception\Event\TestEvent;
use Codeception\Events;
use Codeception\Extension;
use Codeception\TestInterface;
use Maknz\Slack\Client;

class RatingExtension extends Extension
{
    /**
     * Emoji codes
     * @example https://apps.timwhitlock.info/emoji/tables/unicode
     */
    public const CRISTAL = "\xE2\x9C\xA8";

    /**
     * Output Strings
     */
    public const POINTS_COLLECTED = 'Crystals collected';
    public const RATING_ANNOTATION_NAME = 'score';
    public const YOUR_SCORE = 'Your Crystals';
    public const COLLECTED_SCORE = 'Collected Crystals';
    public const TOTAL_SCORE = 'Total Crystals';
    public const PROGRESS_UPDATE = '========Progress Update==========';

    /**
     * Slack Webhook
     */
    public const SLACK_HOOK_URL = 'https://hooks.slack.com/services/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';

    /**
     * Codeception Events
     * @var array
     */
    public static $events = [
        Events::TEST_SUCCESS => 'success',
        Events::RESULT_PRINT_AFTER => 'print',
        Events::TEST_BEFORE => 'beforeTest',
    ];

    /**
     * @var int
     */
    private $score = 0;

    /**
     * @var int
     */
    private $score_total = 0;

    /**
     * @var \Maknz\Slack\Client
     */
    private $slackClient;

    /**
     * @return void
     */
    public function _initialize(): void
    {
        $this->_reconfigure(['settings' => ['silent' => false]]);
        $this->slackClient = new Client(self::SLACK_HOOK_URL);
    }

    /**
     * @param \Codeception\Event\TestEvent $e
     *
     * @return void
     */
    public function success(TestEvent $e): void
    {
        $score = 0;
        $score += $this->getScore($e->getTest());
        $this->say(self::CRISTAL . ' ' . self::POINTS_COLLECTED . ": $score");
        $this->score += $score;
    }

    /**
     * @param \Codeception\Event\TestEvent $e
     *
     * @return void
     */
    public function beforeTest(TestEvent $e): void
    {
        $this->score_total += $this->getScore($e->getTest());
    }

    /**
     * @param \Codeception\Event\PrintResultEvent $e
     *
     * @return void
     */
    public function print(PrintResultEvent $e): void
    {
        $this->say(self::CRISTAL . ' ' . self::YOUR_SCORE . ' ' . $this->score . ' ' . self::CRISTAL);
        $this->notifySlack($e);
    }

    /**
     * @return void
     */
    private function notifySlack(PrintResultEvent $e)
    {
        $this->slackClient->send(self::PROGRESS_UPDATE);
        $this->slackClient->send('Passed: ' . count($e->getResult()->passed()) . ', ' .
                                'Errors: ' . count($e->getResult()->errors()) . ', ' .
                                'Failures: ' . count($e->getResult()->failures()));
        $this->slackClient->send(self::COLLECTED_SCORE . ': ' . $this->score . ' (' . $this->getProgress() . '%), ' . self::TOTAL_SCORE . ': ' . $this->score_total);
    }

    /**
     * @return int
     */
    private function getProgress(): int
    {
        return number_format(($this->score * 100) / $this->score_total);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return int
     */
    private function getScore(TestInterface $test): int
    {
        return $test->getMetadata()->getParam(self::RATING_ANNOTATION_NAME) ? (int)$test->getMetadata()->getParam(self::RATING_ANNOTATION_NAME)[0] : 0;
    }

    /**
     * @param string $text
     *
     * @return void
     */
    private function say(string $text): void
    {
        $this->writeln($text);
    }
}
