<?php
/**
 * This file is part of cocur/background-process.
 *
 * (c) 2013-2014 Florian Eckerstorfer
 */

namespace Cocur\BackgroundProcess;

/**
 * BackgroundProcessTest
 *
 * @category  test
 * @package   cocur/background-process
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013-2104 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @group     functional
 */
class BackgroundProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests running a background process.
     *
     * @covers Cocur\BackgroundProcess\BackgroundProcess::__construct()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::run()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::isRunning()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getPid()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::stop()
     */
    public function testRun()
    {
        $process = new BackgroundProcess('sleep 5');
        $this->assertFalse($process->isRunning(), 'process should not run');
        $process->run();
        $this->assertNotNull($process->getPid(), 'process should have a pid');
        $this->assertTrue($process->isRunning(), 'process should run');
        $this->assertTrue($process->stop(), 'stop process');
        $this->assertFalse($process->isRunning(), 'processes should not run anymore');
        $this->assertFalse($process->stop(), 'cannot stop process that is not running');
    }
}
