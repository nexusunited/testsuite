<?php

namespace Pyz\Zed\Lisel\Business;

use Exception;
use Pyz\Shared\Log\LoggerTrait;
use Pyz\Zed\Lisel\Business\Actions\LiselActionInterface;
use Pyz\Zed\Lisel\Business\Exception\AuthException;
use Pyz\Zed\Lisel\Business\Exception\LiselActionException;
use Pyz\Zed\NxsLogger\Business\NxsLoggerFacadeInterface;
use Pyz\Zed\RestAuth\Business\RestAuthFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class LiselActionHandler
{
    use LoggerTrait;

    public const TOUR_TABLE = 'nxs_tour';
    public const TIME_TABLE = 'nxs_time';
    public const STATUS_TABLE = 'nxs_stop_status';

    /**
     * @var \Shared\Zed\NxsLogger\Business\NxsLoggerFacadeInterface
     */
    private $logger;

    /**
     * @var \Shared\Zed\RestAuth\Business\RestAuthFacadeInterface
     */
    private $authFacade;

    /**
     * @param \Shared\Zed\NxsLogger\Business\NxsLoggerFacadeInterface $logger
     * @param \Shared\Zed\RestAuth\Business\RestAuthFacadeInterface $authFacade
     */
    public function __construct(
        NxsLoggerFacadeInterface $logger,
        RestAuthFacadeInterface $authFacade
    ) {
        $this->logger = $logger;
        $this->authFacade = $authFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Shared\Zed\Lisel\Business\Actions\LiselActionInterface $action
     *
     * @throws \Shared\Zed\Lisel\Business\Exception\LiselActionException
     *
     * @return void
     */
    public function handleAction(Request $request, LiselActionInterface $action): void
    {
        try {
            $this->logRequest($request, $action);
            $this->isAuth($request);
            $action->execute($request);
        } catch (Exception $exception) {
            $this->logError($exception->getMessage(), ['Unhandled Exception ' . print_r($exception->getMessage(), true)]);

            throw new LiselActionException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Shared\Zed\Lisel\Business\Actions\LiselActionInterface $action
     *
     * @return void
     */
    protected function logRequest(Request $request, LiselActionInterface $action): void
    {
        $this->logger->log($request->getContent(), $action->getName());
        $this->logInfo(__CLASS__ . ' ' . $action->getName(), ['Request content' => $request->getContent()]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @throws \Shared\Zed\Lisel\Business\Exception\AuthException Exception
     *
     * @return bool
     */
    private function isAuth(Request $request): bool
    {
        if (!$this->authFacade->isAuth($request)) {
            throw new AuthException();
        }
        return true;
    }
}
