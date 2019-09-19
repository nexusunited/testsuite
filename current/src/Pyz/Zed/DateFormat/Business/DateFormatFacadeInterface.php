<?php

namespace Pyz\Zed\DateFormat\Business;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface DateFormatFacadeInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param array|null $keys
     * @param array $options
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function format(TransferInterface $transfer, ?array $keys, array $options);

    /**
     * @param array $transfer $transfer
     * @param array|null $keys
     * @param array $options
     *
     * @return array
     */
    public function formatArray(array $transfer, ?array $keys = null, array $options = []);
}
