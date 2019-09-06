<?php

namespace Pyz\Zed\HelloSpryker\Business;

use Pyz\Client\HelloSpryker\HelloSprykerClientInterface;
use Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface;
use Pyz\Zed\HelloSpryker\Business\Model\Generator\NameGenerator;
use Pyz\Zed\HelloSpryker\Business\Model\Generator\RandomStringGenerator;
use Pyz\Zed\HelloSpryker\Business\Model\GeneratorCollection;
use Pyz\Zed\HelloSpryker\Business\Model\HelloSpryker;
use Pyz\Zed\HelloSpryker\Business\Model\StringGeneratorCollection;
use Pyz\Zed\HelloSpryker\HelloSprykerDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\HelloSpryker\Persistence\HelloSprykerQueryContainer getQueryContainer()
 */
class HelloSprykerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\HelloSpryker\Business\Model\HelloSpryker
     */
    public function createHelloSpryker(): HelloSpryker {
        return new HelloSpryker($this->createStringGeneratorCollection());
    }

    /**
     * @return HelloSprykerClientInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getHelloSprykerClient(): HelloSprykerClientInterface {
        return $this->getProvidedDependency(HelloSprykerDependencyProvider::CLIENT);
    }

    /**
     * @return \Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface
     */
    public function createRandomStringGenerator(): GeneratorInterface {
        return new RandomStringGenerator();
    }

    /**
     * @return \Pyz\Zed\HelloSpryker\Business\Model\Generator\GeneratorInterface
     */
    public function createNameGenerator(): GeneratorInterface {
        return new NameGenerator();
    }

    /**
     * @return \Pyz\Zed\HelloSpryker\Business\Model\GeneratorCollection
     */
    public function createStringGeneratorCollection(): GeneratorCollection {
        return new GeneratorCollection(
            $this->createNameGenerator(),
            $this->createRandomStringGenerator()
        );
    }
}
