<?php

/**
 * This file is part of cocur/background-process.
 *
 * (c) 2013-2015 Florian Eckerstorfer
 */

namespace Cocur\BackgroundProcess;

/**
 * BackgroundProcess.
 *
 * Runs a process in the background.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013-2015 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 *
 * @link      http://braincrafted.com/php-background-processes/ Running background processes in PHP
 */
class BackgroundProcess
{
    /** @var string */
    private $command;

    /** @var int */
    private $pid;

    /** @var $serverOS int */
    protected $serverOS;

    /**
     * Constructor.
     *
     * @param string $command The command to execute
     */
    public function __construct($command)
    {
        $this->command = $command;
        $this->serverOS = $this->serverOS();
    }

    /**
     * Runs the command in a background process.
     *
     * @param string $outputFile File to write the output of the process to; defaults to /dev/null
     *                           currently $outputFile has no effect when used in conjunction with a Windows server
     */
    public function run($outputFile = '/dev/null')
    {
        switch ($this->serverOS) {
            case 1:
                $cmd = '%s &';
                shell_exec(sprintf($cmd, $this->command, $outputFile));
                break;
            case 2:
            case 3:
                $cmd = '%s > %s 2>&1 & echo $!';
                $this->pid = shell_exec(sprintf($cmd, $this->command, $outputFile));
                break;
            default:
                throw new \RuntimeException(sprintf(
                    'Could not execute command "%s" because operating system "%s" is not supported by Cocur\BackgroundProcess.',
                    $this->command,
                    PHP_OS
                ));
        }
    }

    /**
     * Returns if the process is currently running.
     *
     * @return bool TRUE if the process is running, FALSE if not.
     */
    public function isRunning()
    {
        try {
            $result = shell_exec(sprintf('ps %d', $this->pid));
            if (count(preg_split("/\n/", $result)) > 2) {
                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Stops the process.
     *
     * @return bool `true` if the processes was stopped, `false` otherwise.
     */
    public function stop()
    {
        try {
            $result = shell_exec(sprintf('kill %d 2>&1', $this->pid));
            if (!preg_match('/No such process/', $result)) {
                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Returns the ID of the process.
     *
     * @return int The ID of the process
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @return int 1 Windows, 2 Linux, 3 Mac OS X, 4 unknown
     */
    protected function serverOS()
    {
        $os = strtoupper(PHP_OS);

        if (substr($os, 0, 3) === 'WIN') {
            $os = 1;
        } else if ($os == 'LINUX') {
            $os = 2;
        } else if ($os == 'DARWIN') { // Mac OS X
            $os = 3;
        } else {
            $os = 4;
        }

        return $os;
    }
}
