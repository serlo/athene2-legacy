<?php
namespace User\Service;

interface UserServiceInterface {
    /**
     * Listener for creating users
     * 
     * @param unknown $e
     * @return \User\Entity\User
     */
    public function createListener($e);
    
    /**
     * creates an user
     * 
     * @param array $data
     * @param unknown $form
     * @return \User\Entity\User
     */
    public function create(array $data, $form);
    
    public function delete($id);
    
    public function updateListener($e);
    
    public function update(array $data, $form);
    
    public function receive($id);
    
	public function hasRole($user, $role, $language = NULL, $subject = NULL);
	
	public function getRoles ($user, $language = NULL, $subject = NULL);
}