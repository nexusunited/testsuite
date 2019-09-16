<?php

namespace PyzTest\Shared\Custom;

use Codeception\Command\Run;
use Codeception\CustomCommandInterface;
use DateTime;
use Firebase\FirebaseLib;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SubmitCommand extends Run implements CustomCommandInterface
{
    public const DEFAULT_URL = 'https://nxstestsuite.firebaseio.com/';
    public const DEFAULT_TOKEN = 'eOiQojDMdgfRQnZ9df3C5qRtOnvzpIBeGt7sSPhY';
    public const DEFAULT_PATH = '/data';

    /**
     * @var \Firebase\FirebaseLib
     */
    private $firebase;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->firebase = new FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);
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
        $this->addOption('submit', 'su', InputOption::VALUE_OPTIONAL, 'Submit your results', 'Github Account Name');
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

        if ($this->options['submit'] && $this->codecept->getResult()->score && $this->codecept->getResult()->score_total) {
            $submitNameId = $this->options['submit'];
            $resultScore = $this->codecept->getResult()->score;
            $resultScoreTotal = $this->codecept->getResult()->score_total;

            $dateTime = new DateTime();

            $userScoreData = [
                'date' => $dateTime->format('c'),
                'score' => $resultScore,
                'score_total' => $resultScoreTotal,
                'id' => $submitNameId,
            ];

            $this->firebase->set(DEFAULT_PATH . '/' . $submitNameId, $userScoreData);
        }

        if (!$this->codecept->getResult()->wasSuccessful()) {
            exit(1);
        }

        print_r($this->options);
    }
}
