<?php declare(strict_types=1);


namespace PyzTest\Zed\Helloworld\Business;


use Codeception\Test\Unit;
use Pyz\Zed\Helloworld\Business\HelloworldFacade;
use PyzTest\Shared\NxsScoring\UnitScoring;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group HelloworldTest
 * Add your own group annotations below this line
 */
class HelloworldFacadeTest extends Unit
{
    /**
     * @var HelloworldFacade
     */
    private $helloWorldFacade;

    public function setUp()
    {
        parent::setUp();
        $this->helloWorldFacade = new HelloworldFacade();
    }

    /**
     * @score 100
     */
    public function testExample1()
    {
        $this->assertSame(false,false);
    }

    /**
     * @score 200
     */
    public function testExample2()
    {
        $this->assertSame(true,false);
    }

}
