<?php
/**
 * This file is part of BraincraftedBackgroundProcess.
 *
 * (c) 2013 Florian Eckerstorfer
 */

namespace Braincrafted\BackgroundProcess;

/**
 * FactoryTest
 *
 * @category  Test
 * @package   BraincraftedBackgroundProcess
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://braincrafted.com/php-background-processes/ Running background processes in PHP
 * @group     unit
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $mockClass = 'Braincrafted\BackgroundProcess\MockBackgroundProcess';

    /**
     * Tests the <code>newProcess</code> method.
     *
     * @covers Braincrafted\BackgroundProcess\Factory::__construct()
     * @covers Braincrafted\BackgroundProcess\Factory::newProcess()
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
