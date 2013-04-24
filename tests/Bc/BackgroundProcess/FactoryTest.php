<?php
/**
 * This file is part of BcBackgroundProcess.
 *
 * (c) 2013 Florian Eckerstorfer
 */

namespace Bc\BackgroundProcess;

/**
 * FactoryTest
 *
 * @category  Test
 * @package   BcBackgroundProcess
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://braincrafted.com/php-background-processes/ Running background processes in PHP
 * @group     unit
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $mockClass = 'Bc\BackgroundProcess\MockBackgroundProcess';

    /**
     * Tests the <code>newProcess</code> method.
     *
     * @covers Bc\BackgroundProcess\Factory::__construct()
     * @covers Bc\BackgroundProcess\Factory::newProcess()
     */
    public function testNewProcess()
    {
        $factory = new Factory($this->mockClass);
        $this->assertInstanceOf($this->mockClass, $factory->newProcess('sleep 1'));
    }
}

class MockBackgroundProcess
{
}
