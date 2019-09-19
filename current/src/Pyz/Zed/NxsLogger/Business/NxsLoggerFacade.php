<?php

namespace Pyz\Zed\NxsLogger\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Shared\Zed\NxsLogger\Business\NxsLoggerBusinessFactory getFactory()
 * @method \Shared\Zed\NxsLogger\Persistence\NxsLoggerEntityManagerInterface getEntityManager()
 */
class NxsLoggerFacade extends AbstractFacade implements NxsLoggerFacadeInterface
{
    /**
     * @param string $message
     * @param string $ident
     *
     * @return \Generated\Shared\Transfer\NxsLoggerTransfer
     */
    public function log(string $message, string $ident = 'DEFAULT_IDENTIFICATION')
    {
        return $this->getFactory()->createLogger()->log($message, $ident);
    }

    /**
     * @return void
     */
    public function removeOldLoggingEntries(): void
    {
        $this->getEntityManager()->removeOldLoggingEntries(
            $this->getFactory()->getNxsLoggerEntryMaxAge()
        );
    }
}
