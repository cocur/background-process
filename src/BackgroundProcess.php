<?php

/**
 * This file is part of cocur/background-process.
 *
 * (c) 2013-2015 Florian Eckerstorfer
 */

namespace Cocur\BackgroundProcess;

use Exception;
use RuntimeException;

/**
 * BackgroundProcess.
 *
 * Runs a process in the background.
 *
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013-2015 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      https://florian.ec/articles/running-background-processes-in-php/ Running background processes in PHP
 */
class BackgroundProcess
{
    const OS_WINDOWS = 1;
    const OS_NIX     = 2;
    const OS_OTHER   = 3;

    /**
     * @var string
     */
    private $command;

    /**
     * @var int
     */
    private $pid;

    /**
     * @var int
     */
    protected $serverOS;

    /**
     * @param string $command The command to execute
     *
     * @codeCoverageIgnore
     */
    public function __construct($command = '')
    {
        $this->command  = $command;
        $this->serverOS = $this->getOS();
    }

    /**
     * Runs the command in a background process.
     *
     * @param string $outputFile File to write the output of the process to; defaults to /dev/null
     *                           currently $outputFile has no effect when used in conjunction with a Windows server
     */
    public function run($command, $outputFile = '/dev/null')
    {
        $this->command = $command ? $command : $this->command;

        switch ($this->getOS()) {
            case self::OS_WINDOWS:
                $shell = new \COM("WScript.Shell");
                // $exec = $shell->Exec($this->command.' 2> output2.txt &', $output);
                $exec = $shell->Exec($this->command.' 2> NUL &');
                $this->pid = (int) $exec->ProcessID;
                break;
            case self::OS_NIX:
                $this->pid = (int) shell_exec(sprintf('%s > %s 2>&1 & echo $!', $this->command, $outputFile));
                break;
            default:
                throw new RuntimeException(sprintf(
                    'Could not execute command "%s" because operating system "%s" is not supported by '.
                    'Cocur\BackgroundProcess.',
                    $this->command,
                    PHP_OS
                ));
        }
    }

    /**
     * Returns if the process is currently running.
     *
     * @param int $pid Process to check (Optional)
     *
     * @return bool TRUE if the process is running, FALSE if not.
     */
    public function isRunning($pid = null)
    {
        $this->checkSupportingOS('Cocur\BackgroundProcess can only check if a process is running on *nix-based '.
                                 'systems, such as Unix, Linux or Mac OS X, with partial support on Windows. You are running "%s".');

        $pid = $pid && (int) $pid > 0 ? (int) $pid : $this->pid;

        if ($this->getOS() === self::OS_WINDOWS) {
            return $this->winCheck($pid);
        } else {
            try {
                $result = shell_exec(sprintf('ps %d 2>&1', $pid));
                if (count(preg_split("/\n/", $result)) > 2 && !preg_match('/ERROR: Process ID out of range/', $result)) {
                    return true;
                }
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Stops the process.
     *
     * @param int $pid Process to kill (Optional)
     *
     * @return bool `true` if the processes was stopped, `false` otherwise.
     */
    public function stop($pid = null)
    {
        $this->checkSupportingOS('Cocur\BackgroundProcess can only stop a process on *nix-based systems, such as '.
                                 'Unix, Linux or Mac OS X, with partial support on Windows. You are running "%s".');

        $pid = $pid && (int) $pid > 0 ? (int) $pid : $this->pid;

        if ($this->getOS() === self::OS_WINDOWS) {
            return $this->winKill($pid);
        } else {
            try {
                $result = shell_exec(sprintf('kill %d 2>&1', $pid));
                if (!preg_match('/No such process/', $result)) {
                    return true;
                }
            } catch (Exception $e) {
                return false;
            }
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
        $this->checkSupportingOS('Cocur\BackgroundProcess can only return the PID of a process on *nix-based systems, '.
                                 'such as Unix, Linux or Mac OS X, with partial support on Windows. You are running "%s".');

        return $this->pid;
    }

    /**
     * @return int
     */
    protected function getOS()
    {
        $os = strtoupper(PHP_OS);

        if (substr($os, 0, 3) === 'WIN') {
            return self::OS_WINDOWS;
        } else if ($os === 'LINUX' || $os === 'FREEBSD' || $os === 'DARWIN') {
            return self::OS_NIX;
        }

        return self::OS_OTHER;
    }

    /**
     * @param string $message Exception message if the OS is not supported
     *
     * @throws RuntimeException if the operating system is not supported by Cocur\BackgroundProcess
     *
     * @codeCoverageIgnore
     */
    protected function checkSupportingOS($message)
    {
        if ($this->getOS() === self::OS_OTHER) {
            throw new RuntimeException(sprintf($message, PHP_OS));
        }
    }

    /**
     * @param int $pid Process to kill (Windows)
     *
     * @return boolean Was process killed
     */
    protected function winKill($pid = null)
    {
        if (!$pid || (int) $pid < 1) {
            return true;
        }
        try {
            $wmi = new \COM("winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\cimv2");
            $procs = $wmi->ExecQuery("SELECT * FROM Win32_Process WHERE ProcessId='".$pid."'");
            foreach($procs as $proc) {
                $proc->Terminate();
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param int $pid Process to check if running (Windows)
     *
     * @return boolean Is process running
     */
    protected function winCheck($pid = null)
    {
        if (!$pid || (int) $pid < 1) {
            return true;
        }
        try {
            $wmi = new \COM("winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\cimv2");
            $procs = $wmi->ExecQuery("SELECT * FROM Win32_Process WHERE ProcessId='".$pid."'");
            // echo count($procs);
            foreach($procs as $proc) {
                // echo $proc->Name;
                // echo $proc->ProcessID;
                // echo $proc->CommandLine;
                if ($proc->ProcessID === $pid) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
