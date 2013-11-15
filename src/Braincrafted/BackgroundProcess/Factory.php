<?php
/**
 * This file is part of BraincraftedBackgroundProcess.
 *
 * (c) 2013 Florian Eckerstorfer
 */

namespace Braincrafted\BackgroundProcess;

/**
 * Factory to create BackgroundProcess objects.
 *
 * @package   BraincraftedBackgroundProcess
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013 Florian Eckerstorfer
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