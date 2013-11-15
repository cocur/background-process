<?php
/**
 * This file is part of BraincraftedBackgroundProcess.
 *
 * (c) 2013 Florian Eckerstorfer
 */

namespace Braincrafted\BackgroundProcess;

/**
 * BackgroundProcessTest
 *
 * @category  Test
 * @package   BraincraftedBackgroundProcess
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @group     functional
 */
class BackgroundProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests running a background process.
     *
     * @covers Braincrafted\BackgroundProcess\BackgroundProcess::__construct()
     * @covers Braincrafted\BackgroundProcess\BackgroundProcess::run()
     * @covers Braincrafted\BackgroundProcess\BackgroundProcess::isRunning()
     * @covers Braincrafted\BackgroundProcess\BackgroundProcess::getPid()
     */
    public function testRun()
    {
        $process = new BackgroundProcess('sleep 1');
        $this->assertFalse($process->isRunning());
        $process->run();
        $this->assertNotNull($process->getPid());
        $this->assertTrue($process->isRunning());
    }
}
