<?php

namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class RestrictedController extends AbstractActionController
{
    public function indexAction(){
        return "lolz";
    }
}