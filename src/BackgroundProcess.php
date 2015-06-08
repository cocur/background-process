<?php
/**
 * This file is part of cocur/background-process.
 *
 * (c) 2013-2014 Florian Eckerstorfer
 */

namespace Cocur\BackgroundProcess;

/**
 * BackgroundProcess
 *
 * Runs a process in the background.
 *
 * @package   cocur/background-process
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013-2014 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://braincrafted.com/php-background-processes/ Running background processes in PHP
 */
class BackgroundProcess
{
    /** @var string */
    private $command;

    /** @var integer */
    private $pid;

    /**
     * Constructor.
     *
     * @param string $command The command to execute
     */
    public function __construct($command)
    {
        $this->command = $command;
    }

    /**
     * Runs the command in a background process.
     *
     * @param string $outputFile File to write the output of the process to; defaults to /dev/null
     *
     * @return void
     */
    public function run($outputFile = '/dev/null')
    {
        $this->pid = shell_exec(sprintf(
            '%s > %s 2>&1 & echo $!',
            $this->command,
            $outputFile
        ));
    }

    /**
     * Returns if the process is currently running.
     *
     * @return boolean TRUE if the process is running, FALSE if not.
     */
    public function isRunning()
    {
        try {
            $result = shell_exec(sprintf('ps %d', $this->pid));
            if(count(preg_split("/\n/", $result)) > 2) {
                return true;
            }
        } catch(Exception $e) {}

        return false;
    }

    /**
     * Stops the process.
     *
     * @return boolean `true` if the processes was stopped, `false` otherwise.
     */
    public function stop()
    {
        try {
            $result = shell_exec(sprintf('kill %d 2>&1', $this->pid));
            if (!preg_match('/No such process/', $result)) {
                return true;
            }
        } catch (Exception $e) {}

        return false;
    }

    /**
     * Returns the ID of the process.
     *
     * @return integer The ID of the process
     */
    public function getPid()
    {
        return $this->pid;
    }
    
    /**
     * Returns if a process with the provided $pid is currently running.
     *
     * @return boolean TRUE if the process is running, FALSE if not.
     */
    public static function isProcessRunning($pid)
    {
        $process = new static(null);
        $process->pid = $pid;
        
        return $process->isRunning();
    }
}
