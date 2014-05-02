<?php
/**
 * This file is part of cocur/background-process.
 *
 * (c) 2013-2014 Florian Eckerstorfer
 */

namespace Cocur\BackgroundProcess;

/**
 * FactoryTest
 *
 * @category  test
 * @package   cocur/background-process
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013-2014 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://braincrafted.com/php-background-processes/ Running background processes in PHP
 * @group     unit
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $mockClass = 'Cocur\BackgroundProcess\MockBackgroundProcess';

    /**
     * Tests the <code>newProcess</code> method.
     *
     * @covers Cocur\BackgroundProcess\Factory::__construct()
     * @covers Cocur\BackgroundProcess\Factory::newProcess()
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
