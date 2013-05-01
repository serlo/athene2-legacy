<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by Aeneas Rekkas & www.serlo.org
 */
namespace Math\Controller\Exercises;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Entity\EntityManagerInterface;

class IndexController extends AbstractActionController
{

    /**
     * @var EntityManagerInterface
     */
    private $_entityManager;

    /**
     * @return \Entity\EntityManagerInterface $_entityManager
     */
    public function getEntityManager ()
    {
        return $this->_entityManager;
    }

    /**
     * @param \Entity\EntityManagerInterface $_entityManager            
     * @return $this
     */
    public function setEntityManager (EntityManagerInterface $_entityManager)
    {
        $this->_entityManager = $_entityManager;
        return $this;
    }

    public function indexAction ()
    {
        $id = $this->getParam('id');
        //die('luluz');
        $entity = $this->getEntityManager()->get($id);
        $revision = $entity->getComponent('repository')->getCurrentRevision();
        
        
        $view = new ViewModel(array(
            'subject' => $entity->getSubject()->get('name'),
        	'content' => $revision->get('content'),
        ));
        $view->setTemplate('math/exercises/index/index');
        return $view;
    }
}