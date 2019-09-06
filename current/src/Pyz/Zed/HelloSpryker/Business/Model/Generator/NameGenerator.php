<?php


namespace Pyz\Zed\HelloSpryker\Business\Model\Generator;

class NameGenerator implements GeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return 'Test User';
    }
}
