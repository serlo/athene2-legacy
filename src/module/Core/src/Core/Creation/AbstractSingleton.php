<?php
namespace Core\Creation;

abstract class AbstractSingleton
{

    protected static $instance;

    public function __clone ()
    {
        throw new \Exception('Singleton does not allow multiple instances!');
    }

    public function __construct ()
    {
        if (self::$instance !== NULL)
            throw new \Exception('Singleton does not allow multiple instances!');
    }
}