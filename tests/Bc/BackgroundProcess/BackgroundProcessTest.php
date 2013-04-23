<?php

namespace Bc\BackgroundProcess;

/**
 * BackgroundProcessTest
 *
 * @group functional
 */
class BackgroundProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests running a background process.
     *
     * @covers Bc\BackgroundProcess\BackgroundProcess::__construct()
     * @covers Bc\BackgroundProcess\BackgroundProcess::run()
     * @covers Bc\BackgroundProcess\BackgroundProcess::isRunning()
     * @covers Bc\BackgroundProcess\BackgroundProcess::getPid()
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
