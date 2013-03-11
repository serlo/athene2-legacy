<?php
namespace Auth\Service;

interface HashServiceInterface
{
    public function hash_password($password, $salt = FALSE);
    public function find_salt($password);
}

?>