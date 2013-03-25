<?php
namespace Auth\Service;

interface AuthServiceInterface
{
    /**
     * logs a user in
     * 
     * @param string $username
     * @param string $password
     * @return true on success false on error
     */
    public function login($username, $password);
    
    /**
     * logs an authenticated user out
     * 
     * @return void
     */
    public function logout();
    
    /**
     * is our user logged in?
     * 
     * @return true if yes, false if no
     */
    public function loggedIn();
    

    public function hasRole ($role);
    
    /**
     * is our user allowed to access a specific resource?
     * 
     * @param string $role
     * @param string $resource
     * @param string $privilege
     * @param string $language
     * @param string $subject
     * @return true if yes, false if no
     */
    public function isAllowed($role, $resource = NULL, $privilege = NULL);
}

?>