<?php

namespace Pyz\Zed\CmsBlock;

use Spryker\Zed\CmsBlock\CmsBlockDependencyProvider as CmsBlockCmsBlockDependencyProvider;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin\CmsBlockCategoryConnectorUpdatePlugin;
use Spryker\Zed\CmsBlockProductConnector\Communication\Plugin\CmsBlockProductAbstractUpdatePlugin;

class CmsBlockDependencyProvider extends CmsBlockCmsBlockDependencyProvider
{
    /**
     * @return array
     */
    protected function getCmsBlockUpdatePlugins()
    {
        $plugins = parent::getCmsBlockUpdatePlugins();

        return array_merge($plugins, [
            new CmsBlockCategoryConnectorUpdatePlugin(),
        ]);
    }
}
