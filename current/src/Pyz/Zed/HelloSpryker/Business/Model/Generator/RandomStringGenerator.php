<?php


namespace Pyz\Zed\HelloSpryker\Business\Model\Generator;


class RandomStringGenerator implements GeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return 'Random String';
    }
}
