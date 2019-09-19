<?php

namespace Pyz\Zed\LiselAnalyser\Business\Model;

use Pyz\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerCollectionInterface;

class LiselCleaner
{
    /**
     * @var \Shared\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerCollectionInterface
     */
    private $cleanerCollection;

    /**
     * @param \Shared\Zed\LiselAnalyser\Business\Model\Cleaner\CleanerCollectionInterface $cleanerCollection
     */
    public function __construct(CleanerCollectionInterface $cleanerCollection)
    {
        $this->cleanerCollection = $cleanerCollection;
    }

    /**
     * @return void
     */
    public function startWorker(): void
    {
        foreach ($this->cleanerCollection->getPlugins() as $cleaner) {
            $cleaner->clean();
        }
    }
}
