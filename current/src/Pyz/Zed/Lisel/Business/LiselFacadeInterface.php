<?php

namespace Pyz\Zed\Lisel\Business;

use Symfony\Component\HttpFoundation\Request;

interface LiselFacadeInterface
{
    /**
     * @param string $bundle $bundle
     *
     * @return array
     */
    public function getControllerAnnotations(string $bundle): array;

    /**
     * @param string $bundle $bundle
     *
     * @return array
     */
    public function getTransferAnnotations(string $bundle): array;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return void
     */
    public function tourActionV1(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return void
     */
    public function tourActionV2(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return void
     */
    public function timeActionV1(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request $request
     *
     * @return void
     */
    public function statusActionV1(Request $request): void;
}
