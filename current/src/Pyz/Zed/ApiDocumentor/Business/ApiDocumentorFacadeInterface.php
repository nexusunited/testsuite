<?php

namespace Pyz\Zed\ApiDocumentor\Business;

interface ApiDocumentorFacadeInterface
{
    /**
     * @param string $transferFile
     *
     * @return array
     */
    public function getTransferAnnotations(string $transferFile): array;

    /**
     * @param string $controller
     *
     * @return array
     */
    public function getControllerAnnotations(string $controller): array;
}
