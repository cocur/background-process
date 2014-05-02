<?php
/**
 * This file is part of cocur/background-process.
 *
 * (c) 2013-2104 Florian Eckerstorfer
 */

namespace Cocur\BackgroundProcess;

/**
 * Factory to create BackgroundProcess objects.
 *
 * @package   cocur/background-process
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013-2014 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://braincrafted.com/php-background-processes/ Running background processes in PHP
 */
class Factory
{
    /** @var string */
    private $className;

    public function __construct($className)
    {
        $this->className = $className;
    }

    public function newProcess($command)
    {
        $className = $this->className;
        return new $className($command);
    }
}
