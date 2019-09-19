<?php

namespace Pyz\Zed\DateFormat\Business;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\DateFormat\Business\DateFormatBusinessFactory getFactory()
 */
class DateFormatFacade extends AbstractFacade implements DateFormatFacadeInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param array|null $keys
     * @param array $options
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function format(TransferInterface $transfer, ?array $keys = null, array $options = [])
    {
        return $this->getFactory()->createDateTransformator()->format($transfer, $keys, $options);
    }

    /**
     * @param array $transfer
     * @param array|null $keys
     * @param array $options
     *
     * @return array
     */
    public function formatArray(array $transfer, ?array $keys = null, array $options = [])
    {
        return $this->getFactory()->createDateTransformator()->formatArray($transfer, $keys, $options);
    }
}
