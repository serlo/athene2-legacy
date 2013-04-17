<?php
namespace Log\Service;

class LogService implements LogServiceInterface
{
    private $logggers, $logger;

    public function addLogger ($name, $logger)
    {
        $this->loggers[$name] = $logger;
    }
    
    public function get($name){
        if(!array_key_exists($name, $this->loggers))
            throw new \Exception('Logger does not exist');
        
        if(is_object($this->loggers[$name])){
            return $this->loggers[$name];
        } else {
            $this->loggers[$name] = new $this->loggers[$name];
            return $this->loggers[$name];
        }
    }

    public function setLogger($name){
        $this->logger = $this->getLogger($name);
    }
    
    public function log ($action, $refTable = NULL, $refId = NULL, $note = NULL, $logger = null)
    {
        if(!$this->logger && !$logger)
            throw new \Exception('No logger set!');
        
        if($logger){
            $this->getLogger($logger)->log($action, $refTable, $refId, $note);
        } else {
            $this->logger->log($action, $refTable, $refId, $note);
        }
        return $this;
    }
}