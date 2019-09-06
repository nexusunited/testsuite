<?php


namespace Pyz\Zed\HelloSpryker\Business\Model;

class HelloSpryker implements HelloSprykerInterface
{
    /**
     * @var \Pyz\Zed\HelloSpryker\Business\Model\GeneratorCollectionInterface
     */
    private $generatorCollection;

    public function __construct(GeneratorCollectionInterface $generatorCollection)
    {
        $this->generatorCollection=$generatorCollection;
    }

    /**
     * @return array
     */
    public function generateStrings(): array
    {
        $strings = [];
        /** @var \Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface $generator */
        foreach ($this->generatorCollection as $generator) {
            $strings = $generator->generate();
        }
        return $strings;
    }
}
