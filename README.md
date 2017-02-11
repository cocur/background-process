cocur/background-process
========================

> Start processes in the background that continue running when the PHP process exists.

[![Latest Stable Version](http://img.shields.io/packagist/v/cocur/background-process.svg)](https://packagist.org/packages/cocur/background-process)
[![Build Status](http://img.shields.io/travis/cocur/background-process.svg)](https://travis-ci.org/cocur/background-process)
[![Windows Build status](https://ci.appveyor.com/api/projects/status/odmyynd522vuef1y?svg=true)](https://ci.appveyor.com/project/florianeckerstorfer/background-process)
[![Code Coverage](https://scrutinizer-ci.com/g/cocur/background-process/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/cocur/background-process/?branch=master)


Installation
------------

You can install Cocur\BackgroundProcess using [Composer](http://getcomposer.org):

```shell
$ composer require cocur/background-process
```


Usage
-----

The following example will execute the command `sleep 5` in the background. Thus, if you run the following script 
either in the browser or in the command line it will finish executing instantly.

```php
use Cocur\BackgroundProcess\BackgroundProcess;

$process = new BackgroundProcess('sleep 5');
$process->run();
```

You can retrieve the process ID (PID) of the process and check if it's running:

```php
use Cocur\BackgroundProcess\BackgroundProcess;

$process = new BackgroundProcess('sleep 5');
$process->run();

echo sprintf('Crunching numbers in process %d', $process->getPid());
while ($process->isRunning()) {
    echo '.';
    sleep(1);
}
echo "\nDone.\n";
```

If the process runs you can stop it:

```php
// ...
if ($process->isRunning()) {
    $process->stop();
}
```

*Please note: If the parent process continues to run while the child process(es) run(s) in the background you should 
use a more robust solution, for example, the [Symfony Process](https://github.com/symfony/Process) component.*

### Windows Support

Since Version 0.5 Cocur\BackgroundProcess has basic support for Windows included. However, support is very limited at
this time. You can run processes in the background, but it is not possible to direct the output into a file and you
can not retrieve the process ID (PID), check if a process is running and stop a running process.

In practice, the following methods will throw an exception if called on a Windows system:

- `Cocur\BackgroundProcess\BackgroundProcess::getPid()`
- `Cocur\BackgroundProcess\BackgroundProcess::isRunning()`
- `Cocur\BackgroundProcess\BackgroundProcess::stop()`

### Create with existing PID

If you have a long running process and store its PID in the database you might want to check at a later point (when you don't have the BackgroundProcess object anymore) whether the process is still running and stop the process.

```php
use Cocur\BackgroundProcess\BackgroundProcess;

$process = BackgroundProcess::createFromPID($pid);
$process->isRunning(); // -> true
$process->stop();      // -> true
```

Change Log
----------

### Version 0.7 (11 February 2017)

- [#19](https://github.com/cocur/background-process/pull/19) Create `BackgroundProcess` object from PID (by [socieboy](https://github.com/socieboy) and [florianeckerstorfer](https://github.com/florianeckerstorfer))

### Version 0.6 (10 July 2016)

- [#17](https://github.com/cocur/background-process/pull/17) Add ability to append to file on Unix/Linux-based systems (by [bpolaszek](https://github.com/bpolaszek))

### Version 0.5 (24 October 2015)

- Added basic support for Windows

### Version 0.4 (2 April 2014)

- Moved repository to Cocur organization
- Changed namespace to `Cocur`
- PSR-4 compatible namespace
- [#3](https://github.com/cocur/background-process/pull/3) Added `BackgroundProcess::stop()` (by florianeckerstorfer)

### Version 0.3 (15 November 2013)

- Changed namespace to `Braincrafted`


Author
------

[**Florian Eckerstorfer**](http://florian.ec)

- [Twitter](http://twitter.com/Florian_)


License
-------

The MIT license applies to **cocur/background-process**. For the full copyright and license information, please view the LICENSE file distributed with this source code.
