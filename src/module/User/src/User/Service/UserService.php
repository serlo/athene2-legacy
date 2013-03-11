<?php
namespace User\Service;

use Doctrine\ORM\EntityManager;
use User\Entity\User;

class UserService implements UserServiceInterface {
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
	/**
	 * @return the $entityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * @param Doctrine\ORM\EntityManager $entityManager
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	public function createListener($e){
	    $data = $e->getParam('data');
	    $form = $e->getParam('form');
	    
	    return $this->create($data, $form);
	}
	
	public function create(array $data, $form){
	    $user = new User();
	    
	    $form->setInputFilter($user->getInputFilter());
	    //$form->setData($data);
	    
	    if ($form->isValid()) {
	    	$user->populate($form->getData());
	    	$this->getEntityManager()->persist($user);
	    	$this->getEntityManager()->flush();
	    } else {
	        print_r($form->getMessages());
	    }
	    
	    return $user;
	}
	
	public function delete($id){
	    
	}
	
	public function updateListener($e){
		
	}
	
	public function update(array $data, $form){
	    
	}
	
	public function read($id){
	    
	}
}