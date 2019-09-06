<?php

namespace Pyz\Zed\Helloworld\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Pyz\Zed\Helloworld\Business\HelloworldFacade getFacade()
 * @method \Pyz\Zed\Helloworld\Communication\HelloworldCommunicationFactory getFactory()
 * @method \Pyz\Zed\Helloworld\Persistence\HelloworldQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'test' => 'Greetings!',
        ]);
    }

}
