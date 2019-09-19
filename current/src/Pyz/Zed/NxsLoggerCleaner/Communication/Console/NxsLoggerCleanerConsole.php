<?php

namespace Pyz\Zed\NxsLoggerCleaner\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Shared\Zed\NxsLoggerCleaner\Communication\NxsLoggerCleanerCommunicationFactory getFactory()
 */
class NxsLoggerCleanerConsole extends Console
{
    public const COMMAND_NAME = 'nxs:logger:remove-entries';
    public const AFTERCOMMAND_MESSAGE = 'Old nxs_logging entries were deleted';

    /**
     * @return void
     */
    public function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Remove old nxs_logging entries older than specified in the configuration.');

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
        $this->removeOldEntries();
    }

    /**
     * @return void
     */
    protected function removeOldEntries(): void
    {
        $this->getFactory()->getNxsLoggerFacade()->removeOldLoggingEntries();
        $this->info(
            self::AFTERCOMMAND_MESSAGE,
            true
        );
    }
}
