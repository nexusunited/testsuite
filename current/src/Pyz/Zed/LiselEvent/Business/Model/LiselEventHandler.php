<?php

namespace Pyz\Zed\LiselEvent\Business\Model;

use Pyz\Zed\LiselEvent\Plugin\LiselEventCollection;
use Pyz\Zed\LiselEvent\Plugin\LiselEventHandlerInterface;
use Pyz\Zed\LiselNotifier\Business\LiselNotifierFacadeInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class LiselEventHandler implements LiselEventHandlerInterface
{
    /**
     * @var \Shared\Zed\LiselEvent\Plugin\LiselEventCollection
     */
    private $eventPlugins;

    /**
     * @var \Shared\Zed\LiselNotifier\Business\LiselNotifierFacadeInterface
     */
    private $notifierFacade;

    /**
     * @param \Shared\Zed\LiselNotifier\Business\LiselNotifierFacadeInterface $notifierFacade
     */
    public function __construct(LiselNotifierFacadeInterface $notifierFacade)
    {
        $this->notifierFacade = $notifierFacade;
    }

    /**
     * @param \Shared\Zed\LiselEvent\Plugin\LiselEventCollection $collection
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function trigger(LiselEventCollection $collection, TransferInterface $transfer): void
    {
        $this->eventPlugins = $collection;
        $this->triggerEventPlugins($transfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    private function triggerEventPlugins(TransferInterface $transfer): void
    {
        foreach ($this->eventPlugins as $eventPlugin) {
            $eventPlugin->setTransferData($transfer);
            if ($eventPlugin->isResponsible()) {
                $this->notifierFacade->notify($eventPlugin);
            }
        }
    }
}
