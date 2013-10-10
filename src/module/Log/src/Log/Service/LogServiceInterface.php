<?php
namespace Log\Service;

interface LogServiceInterface
{

    /**
     * Returns a logger
     * 
     * @param string $name
     * @return LoggerInterface
     */
    public function get($name);
}