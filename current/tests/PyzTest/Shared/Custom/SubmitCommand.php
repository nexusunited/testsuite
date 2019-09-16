<?php

namespace PyzTest\Shared\Custom;

use Codeception\Command\Run;
use Codeception\CustomCommandInterface;
use DateTime;
use Firebase\FirebaseLib;
use Maknz\Slack\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SubmitCommand extends Run implements CustomCommandInterface
{
    /**
     * Slack Emoji
     * https://www.webfx.com/tools/emoji-cheat-sheet/
     */
    public const TROPHY = ':trophy:';
    public const GEM = ':gem:';

    /**
     * Firebase settings
     */
    public const DEFAULT_URL = 'https://nxstestsuite.firebaseio.com/';
    public const DEFAULT_TOKEN = 'eOiQojDMdgfRQnZ9df3C5qRtOnvzpIBeGt7sSPhY';
    public const DEFAULT_PATH = '/data';
    public const RANGLIST_UPDATE = '========:speak_no_evil: Ranglist Update==========';
    public const GITHUB_DEFAULT_ACCOUNT = 'Github Account Name';

    /**
     * @var \Firebase\FirebaseLib
     */
    private $firebase;

    /**
     * @var \Maknz\Slack\Client
     */
    private $slackClient;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->firebase = new FirebaseLib(self::DEFAULT_URL, self::DEFAULT_TOKEN);
        $this->slackClient = new Client(RatingExtension::SLACK_HOOK_URL);
    }

    /**
     * @return string
     */
    public static function getCommandName(): string
    {
        return 'run';
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->addOption('submit', 'su', InputOption::VALUE_OPTIONAL, 'Submit your results', self::GITHUB_DEFAULT_ACCOUNT);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $input->setOption('no-exit', true);
        parent::execute($input, $output);

        if ($this->options['submit'] !== self::GITHUB_DEFAULT_ACCOUNT && $this->codecept->getResult()->score && $this->codecept->getResult()->score_total) {
            $submitNameId = $this->options['submit'];
            $resultScore = $this->codecept->getResult()->score;
            $resultScoreTotal = $this->codecept->getResult()->score_total;

            $dateTime = new DateTime();

            $userScoreData = [
                'date' => $dateTime->format('c'),
                'score' => $resultScore,
                'id' => $submitNameId,
                'score_total' => $resultScoreTotal,
                'passed' => count($this->codecept->getResult()->passed()),
                'errors' => count($this->codecept->getResult()->errors()),
                'failures' => count($this->codecept->getResult()->failures()),
            ];

            $this->firebase->set(self::DEFAULT_PATH . '/' . $submitNameId, $userScoreData);
            $this->topListToSlack();
        }

        if (!$this->codecept->getResult()->wasSuccessful()) {
            exit(1);
        }
    }

    /**
     * @param int $number
     *
     * @return int
     */
    private function flipNum(int $number): int
    {
        return -1 * abs($number);
    }

    /**
     * @return void
     */
    private function topListToSlack(): void
    {
        //post toplist to slack in php clientside since firebase functions cannot call external urls in free version
        $top10 = $this->firebase->get(self::DEFAULT_PATH, ['orderBy' => '"score"', 'limitToLast' => 5]);

        $players = json_decode($top10, true);
        usort($players, static function ($a, $b) {
            return $a['score'] <=> $b['score'];
        });
        $rangNumber = 1;
        $playerLabels = self::RANGLIST_UPDATE . "\n";
        foreach (array_reverse($players) as $playerNum => $playerDetailArray) {
            $playerLabel = self::TROPHY . ' ' . $playerDetailArray['id'] . ' (' . $playerDetailArray['score'] . ' ' . self::GEM . ')' . "\n";
            if ($rangNumber > 3) {
                $playerLabel = $playerDetailArray['id'] . ' (' . $playerDetailArray['score'] . ' ' . self::GEM . ')' . "\n";
            }
            $playerLabels .= $rangNumber . '. ' . $playerLabel;
            $rangNumber++;
        }
        $this->slackClient->send($playerLabels);
    }
}
