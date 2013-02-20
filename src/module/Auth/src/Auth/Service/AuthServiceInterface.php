<?php
namespace Auth\Service;

interface AuthServiceInterface
{
    public function login($username, $password);
    public function logout();
    public function loggedIn();
    public function hasRole($role);
}

?>