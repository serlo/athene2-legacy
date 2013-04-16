<?php
namespace Log\Service;

abstract class AbstractLogger
{
    function logOn ($em, $class, array $functions)
    {
        $log = $this;
        foreach($functions as $logFunction){
        	$em->attach($class.'::'.$logFunction, function ($e) use ($log) {
        		$log->logListener($e->getName(), get_class($e->getTarget()), $e->getParams());
        	});
        }
    }
}