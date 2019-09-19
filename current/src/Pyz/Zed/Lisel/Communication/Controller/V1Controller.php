<?php

namespace Pyz\Zed\Lisel\Communication\Controller;

use Exception;
use Pyz\Zed\Lisel\Business\Exception\LiselActionException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function get_class;

/**
 * @method \Shared\Zed\Lisel\Business\LiselFacadeInterface getFacade()
 */
class V1Controller extends SprykerAbstractController
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
            $this->getFacade()->tourActionV1($request);
            return $this->getDefaultJsonResponse();
        } catch (LiselActionException $exception) {
            return $this->getDefaultJsonErrorResponse($exception);
        } catch (Exception $exception) {
            return $this->getDefaultJsonErrorResponse($exception);
        }
    }

    /**
     * @api Generated\Shared\Transfer\LiselTimeTransfer
     *
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function timeAction(Request $request): JsonResponse
    {
        try {
            $this->getFacade()->timeActionV1($request);
            return $this->getDefaultJsonResponse();
        } catch (LiselActionException $exception) {
            return $this->getDefaultJsonErrorResponse($exception);
        } catch (Exception $exception) {
            return $this->getDefaultJsonErrorResponse($exception);
        }
    }

    /**
     * @api Generated\Shared\Transfer\LiselStopStatusTransfer
     *
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function statusAction(Request $request): JsonResponse
    {
        try {
            $this->getFacade()->statusActionV1($request);
            return $this->getDefaultJsonResponse();
        } catch (LiselActionException $exception) {
            return $this->getDefaultJsonErrorResponse($exception);
        } catch (Exception $exception) {
            return $this->getDefaultJsonErrorResponse($exception);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $doc = [];
        $transferFile = $request->get('transfer');
        if ($transferFile !== null) {
            $doc['transfer_annotation'] = $this->getFacade()->getTransferAnnotations($transferFile);
        } else {
            $doc['annotations'] = $this->getFacade()->getControllerAnnotations(get_class($this));
        }
        return $doc;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getDefaultJsonResponse(): JsonResponse
    {
        return $this->jsonResponse(['code' => 200, 'status' => 'OK']);
    }

    /**
     * @param \Exception $exception
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getDefaultJsonErrorResponse(Exception $exception): JsonResponse
    {
        return $this->jsonResponse(['message' => $exception->getMessage(), 'code' => $exception->getCode(), 'type' => 'Error'], 500);
    }
}
