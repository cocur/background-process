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
     * currently $outputFile has no effect when used in conjunction with a Windows server
     *
     * @return void
     */
    public function run($outputFile = '/dev/null')
    {
	    switch($this->serverOS){
		    case 1:
				$cmd = "%s &";
			    shell_exec(sprintf($cmd, $this->command, $outputFile));
			    break;
		    case 2:
			case 3:
			    $cmd = "%s > %s 2>&1 & echo $!";
			    $this->pid = shell_exec(sprintf($cmd, $this->command, $outputFile));
			    break;
		    default:
			    die("we don't recognise you're server's OS");
			    break;
	    }
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
        } catch(\Exception $e) {}

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
        } catch (\Exception $e) {}

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
	 * serverOS() protected method
	 * returns integer, 1 if Windows, 2 if Linux, 3 if other
	 *
	 * @return int
	 */
	protected function serverOS()
	{
		$sys = strtoupper(PHP_OS);

		if(substr($sys,0,3) == "WIN") { $os = 1; } #Windows
		elseif($sys == "Linux") { $os = 2; } #Linux
		elseif($sys == "Darwin") { $os = 3; } #Mac OS X
		else { $os = 4; }

		return $os;
	}
}
