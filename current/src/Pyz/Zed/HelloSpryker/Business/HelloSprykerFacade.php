<?php

namespace Pyz\Zed\HelloSpryker\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\HelloSpryker\Business\HelloSprykerBusinessFactory getFactory()
 */
class HelloSprykerFacade extends AbstractFacade implements HelloSprykerFacadeInterface
{
    /**
     * @return array
     */
    public function generateStrings(): array
    {
        return $this
            ->getFactory()
            ->createHelloSpryker()
            ->generateStrings();
    }
}
