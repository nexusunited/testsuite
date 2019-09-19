<?php

namespace Pyz\Zed\Lisel\Business\Actions;

use Symfony\Component\HttpFoundation\Request;

interface LiselActionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function execute(Request $request): void;
}
