<?php

namespace Pyz\Zed\Helloworld\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\Helloworld\Business\HelloworldBusinessFactory getFactory()
 */
class HelloworldFacade extends AbstractFacade implements HelloworldFacadeInterface
{

    public function hello():string
    {
        return 'hello world';
    }

}
