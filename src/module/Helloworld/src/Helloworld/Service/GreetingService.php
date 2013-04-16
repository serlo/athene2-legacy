<?php
namespace Helloworld\Service;

use Zend\EventManager\EventManagerInterface;

class GreetingService
{

    private $eventManager;

    public function getGreeting ()
    {
        $this->eventManager->trigger('getGreeting');
        
        if (date("H") <= 11)
            return "Good morning, world!";
        else 
            if (date("H") > 11 && date("H") < 17)
                return "Hello, w123orld! 12";
            else
                return "Good evening, world!";
    }

    public function getEventManager ()
    {
        return $this->eventManager;
    }

    public function setEventManager (EventManagerInterface $em)
    {
        $this->eventManager = $em;
    }
}
    