cocur/background-process
===================

> Start processes in the background that continue running when the PHP process exists.

[![Build Status](https://travis-ci.org/cocur/background-process.png?branch=master)](https://travis-ci.org/cocur/background-process)


By [Florian Eckerstorfer](http://florianeckerstorfer.com).

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
echo "\nDone.\n"
```

*Please note: If the parent process continues to run while the child process(es) run(s) in the background you should use a more robust solution, for example, the [Symfony Process](https://github.com/symfony/Process) component.*


Changelog
---------

### Version 0.4 (2 April 2014)

- Moved repository to Cocur organization
- Changed namespace to `Cocur`

### Version 0.3 (15 November 2013)

- Changed namespace to `Braincrafted`

License
-------

```
The MIT License (MIT)

Copyright (c) 2013 Florian Eckerstorfer

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
```
