<?php

namespace Pyz\Zed\Lisel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Shared\Zed\Lisel\Business\LiselFacade getFacade()
 */
class LiselWorkerStart extends Console
{
    public const COMMAND_NAME = 'lisel:worker:start';

    /**
     * @return void
     */
    public function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Start parsing all new nxs_lisel_requests entries and trigger Lisel events');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->writeNewRequests();
        $this->triggerEventsOnData();
    }

    /**
     * @return void
     */
    protected function triggerEventsOnData(): void
    {
        $this->getFacade()->triggerEvents();
        $this->info('Events are triggered', true);
    }

    /**
     * @return void
     */
    protected function writeNewRequests(): void
    {
        $this->getFacade()->startRequestsWorker();
        $this->info('All new Requests are processed', true);
    }
}
