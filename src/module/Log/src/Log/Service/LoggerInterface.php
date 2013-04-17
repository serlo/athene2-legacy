<?php
namespace Log\Service;

interface LoggerInterface
{
    public function log($action, $refTable = NULL, $refId = NULL, $note = NULL);
	public function logListener($event, $source, $params);
	public function logOn($eventManager, $class, array $functions);
}