<?php
namespace Helloworld\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    private $greetingService;
    
    public function indexAction ()
    {
        return new ViewModel(array(
            'greeting' => $this->greetingService->getGreeting()
        ));
    }

    public function setGreetingService ($service)
    {
        $this->greetingService = $service;
    }
}
