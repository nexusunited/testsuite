<?php declare(strict_types=1);


namespace PyzTest\Zed\Helloworld\Business;


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
class HelloworldFacadeTest extends UnitScoring
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
     * @scoring 10
     */
    public function testHello()
    {
        $this->assertSame($this->helloWorldFacade->hello(),'hello world');
        //$this->assertSame(true,false);
    }

    /**
     * @scoring 2ß
     */
    public function test2()
    {
        $this->assertTrue(false);
    }

    /**
     * @scoring 30
     */
    public function test3()
    {
        $this->assertTrue(true);
        $this->assertTrue(false);
    }

}