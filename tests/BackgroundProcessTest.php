<?php
/**
 * This file is part of cocur/background-process.
 *
 * (c) 2013-2014 Florian Eckerstorfer
 */

namespace Cocur\BackgroundProcess;
use org\bovigo\vfs\vfsStream;

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
    public function setUp()
    {
        vfsStream::setup('fixtures', null, ['test.txt' => null]);
    }

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
            $this->markTestSkipped('Checking if a process is running is not supported on Windows.');

            return;
        }

        $process = new BackgroundProcess('sleep 3');

        $this->assertFalse($process->isRunning());
        $process->run();
        $this->assertTrue($process->isRunning());
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::run()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getOS()
     */
    public function runShouldWriteOutputToFile()
    {
        if (preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Writing output to file is not supported on Windows.');

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
            $this->markTestSkipped('Checking PID is not supported on Windows.');

            return;
        }

        $process = new BackgroundProcess('sleep 1');
        $process->run();

        $this->assertNotNull($process->getPid());
    }

    /**
     * @test
     * @covers Cocur\BackgroundProcess\BackgroundProcess::stop()
     * @covers Cocur\BackgroundProcess\BackgroundProcess::getOS()
     */
    public function stopShouldStopRunningProcess()
    {
        if (preg_match('/^WIN/', PHP_OS)) {
            $this->markTestSkipped('Stopping a process is not supported on Windows.');

            return;
        }

        $process = new BackgroundProcess('sleep 5');
        $process->run();

        $this->assertTrue($process->stop());
        $this->assertFalse($process->isRunning());
    }
}
