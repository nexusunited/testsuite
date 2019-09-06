<?php

namespace Pyz\Zed\HelloSpryker\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Pyz\Zed\HelloSpryker\Business\HelloSprykerFacade getFacade()
 * @method \Pyz\Zed\HelloSpryker\Communication\HelloSprykerCommunicationFactory getFactory()
 * @method \Pyz\Zed\HelloSpryker\Persistence\HelloSprykerQueryContainer getQueryContainer()
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
