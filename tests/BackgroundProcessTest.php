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
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::run()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::isRunning()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getOS()
     */
    public function runShouldRunCommand()
    {
        if (preg_match('/^WIN/', PHP_OS)) {
            $command = sprintf('tests\\fixtures\\cmd.bat', __DIR__);
        } else {
            $command = sprintf('./tests/fixtures/cmd.sh', __DIR__);
        }

        $checkFile = __DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'runShouldRunCommand.log';

        $process = new BackgroundProcess($command);
        $process->run();

        sleep(1);

        $this->assertStringStartsWith('ok', file_get_contents($checkFile));

        unlink($checkFile);
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::isRunning()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getOS()
     */
    public function isRunningShouldReturnIfProcessIsRunning()
    {
        if (preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Cocur\BackgroundProcess\BackgroundProcess::isRunning() is not supported on '.
                                   'Windows.');

            return;
        }

        $process = new BackgroundProcess('sleep 3');

        $this->assertFalse($process->isRunning());
        $process->run();
        $this->assertTrue($process->isRunning());
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::isRunning()
     * @expectedException \RuntimeException
     */
    public function isRunningShouldThrowExceptionIfWindows()
    {
        if (!preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Cocur\BackgroundProcess\BackgroundProcess::isRunning() is supported on *nix '.
                                   'systems and does not need to throw an exception.');

            return;
        }

        $process = new BackgroundProcess('sleep 1');
        $process->isRunning();
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::run()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getOS()
     */
    public function runShouldWriteOutputToFile()
    {
        if (preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Cocur\BackgroundProcess\BackgroundProcess::run() does not support writing output '.
                                   'into a file on Windows.');

            return;
        }

        $outputFile = __DIR__.'/fixtures/runShouldWriteOutputToFile.log';

        $process = new BackgroundProcess('ls');
        $process->run($outputFile);

        sleep(1);
        $this->assertNotNull(file_get_contents($outputFile));
        unlink($outputFile);
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getPid()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getOS()
     */
    public function getPidShouldReturnPidOfProcess()
    {
        if (preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Cocur\BackgroundProcess\BackgroundProcess::getPid() is not supported on Windows.');

            return;
        }

        $process = new BackgroundProcess('sleep 3');
        $process->run();

        $this->assertNotNull($process->getPid());
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getPid()
     * @expectedException \RuntimeException
     */
    public function getPidShouldThrowExceptionIfWindows()
    {
        if (!preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Cocur\BackgroundProcess\BackgroundProcess::getPid() is supported on *nix systems '.
                                   'and does not need to throw an exception.');

            return;
        }

        $process = new BackgroundProcess('sleep 1');
        $process->getPid();
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::stop()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getOS()
     */
    public function stopShouldStopRunningProcess()
    {
        if (preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Cocur\BackgroundProcess\BackgroundProcess::stop() is not supported on Windows.');

            return;
        }

        $process = new BackgroundProcess('sleep 5');
        $process->run();

        $this->assertTrue($process->stop());
        $this->assertFalse($process->isRunning());
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::stop()
     * @expectedException \RuntimeException
     */
    public function stopShouldThrowExceptionIfWindows()
    {
        if (!preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Cocur\BackgroundProcess\BackgroundProcess::stop() is supported on *nix systems '.
                                   'and does not need to throw an exception.');

            return;
        }

        $process = new BackgroundProcess('sleep 1');
        $process->stop();
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::createFromPID()
     */
    public function createFromPIDShouldCreateObjectFromPID()
    {
        if (preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Cocur\BackgroundProcess\BackgroundProcess::createFromPID() is not supported on Windows.');

            return;
        }
        $process = new BackgroundProcess('sleep 1');
        $process->run();
        $pid = $process->getPid();

        $newProcess = BackgroundProcess::createFromPID($pid);

        $this->assertEquals($pid, $newProcess->getPid());
        $this->assertTrue($newProcess->stop());
    }
}
