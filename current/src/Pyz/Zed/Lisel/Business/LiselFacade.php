<?php

namespace Pyz\Zed\Lisel\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Shared\Zed\Lisel\Business\LiselBusinessFactory getFactory()
 */
class LiselFacade extends AbstractFacade implements LiselFacadeInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return void
     */
    public function tourActionV1(Request $request): void
    {
        $this->getFactory()->createLiselControllerHandler()
            ->handleAction(
                $request,
                $this->getFactory()->createTourAction($this->getFactory()->createTourActionRulesV1())
            );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return void
     */
    public function tourActionV2(Request $request): void
    {
        $this->getFactory()->createLiselControllerHandler()
            ->handleAction(
                $request,
                $this->getFactory()->createTourAction($this->getFactory()->createTourActionRulesV2())
            );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return void
     */
    public function timeActionV1(Request $request): void
    {
        $this->getFactory()->createLiselControllerHandler()
            ->handleAction(
                $request,
                $this->getFactory()->createTimeAction($this->getFactory()->createTimeActionRulesV1())
            );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return void
     */
    public function statusActionV1(Request $request): void
    {
        $this->getFactory()->createLiselControllerHandler()
            ->handleAction(
                $request,
                $this->getFactory()->createStatusAction($this->getFactory()->createStatusActionRulesV1())
            );
    }

    /**
     * @param string $controller $controller
     *
     * @return array
     */
    public function getControllerAnnotations(string $controller): array
    {
        return $this->getFactory()->getDocumentorFacade()->getControllerAnnotations($controller);
    }

    /**
     * @param string $transferFile
     *
     * @return array
     */
    public function getTransferAnnotations(string $transferFile): array
    {
        return $this->getFactory()->getDocumentorFacade()->getTransferAnnotations($transferFile);
    }

    /**
     * @return void
     */
    public function triggerEvents(): void
    {
        $this->getFactory()->getLiselAnalyserFacade()->startWorker();
    }

    /**
     * @return void
     */
    public function startRequestsWorker(): void
    {
        $this->getFactory()->getLiselRequestFacade()->startWorker();
    }
}
