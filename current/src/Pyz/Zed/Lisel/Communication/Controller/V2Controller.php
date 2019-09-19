<?php

namespace Pyz\Zed\Lisel\Communication\Controller;

use Exception;
use Pyz\Zed\Lisel\Business\Exception\LiselActionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Shared\Zed\Lisel\Business\LiselFacade getFacade()
 */
class V2Controller extends V1Controller
{
    /**
     * @api Generated\Shared\Transfer\LiselTourTransfer
     *
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tourAction(Request $request): JsonResponse
    {
        try {
            $this->getFacade()->tourActionV2($request);
            return $this->getDefaultJsonResponse();
        } catch (LiselActionException $exception) {
            return $this->getDefaultJsonErrorResponse($exception);
        } catch (Exception $exception) {
            return $this->getDefaultJsonErrorResponse($exception);
        }
    }
}
