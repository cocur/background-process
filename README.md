cocur/background-process
===================

> Start processes in the background that continue running when the PHP process exists.

[![Latest Stable Version](http://img.shields.io/packagist/v/cocur/background-process.svg)](https://packagist.org/packages/cocur/background-process)
[![Build Status](http://img.shields.io/travis/cocur/background-process.svg)](https://travis-ci.org/cocur/background-process)
[![Windows Build status](https://ci.appveyor.com/api/projects/status/odmyynd522vuef1y?svg=true)](https://ci.appveyor.com/project/florianeckerstorfer/background-process)
[![Code Coverage](https://scrutinizer-ci.com/g/cocur/background-process/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/cocur/background-process/?branch=master)


Installation
------------

You can install *cocur/background-process* using [Composer](http://getcomposer.org):

```shell
$ composer require cocur/background-process:@stable
```

*In a production environment you should replace `@stable` with the [version](https://github.com/cocur/watchman/releases) you want to use.*


Usage
-----

The following example will execute the command `sleep 5` in the background. Thus, if you run the following script either in the browser or in the command line it will instantley finish executing.

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

```
// ...
if ($process->isRunning()) {
    $process->stop();
}
```

*Please note: If the parent process continues to run while the child process(es) run(s) in the background you should use a more robust solution, for example, the [Symfony Process](https://github.com/symfony/Process) component.*


Changelog
---------

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
