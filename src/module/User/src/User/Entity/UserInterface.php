<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace User\Entity;

use Language\Entity\LanguageInterface;
interface UserInterface
{
    
    public function getUserRoles ();
    
    public function getLogs ();
    
    public function getEmail ();
    
    public function getUsername ();
    
    public function getPassword ();
    
    public function getLogins ();
    
    public function getLastLogin ();
    
    public function getDate ();
    
    public function getGivenname ();
    
    public function getLastname ();
    
    public function getGender ();
    
    public function getAdsEnabled ();
    
    public function getRemoved ();
    
    public function setLogs ($logs);
    
    public function setUserRoles ($userRoles);
    
    public function setEmail ($email);
    
    public function setUsername ($username);
    
    public function setPassword ($password);
    
    public function setLogins ($logins);
    
    public function setLastLogin ($lastLogin);
    
    public function setDate ($date);
    
    public function setGivenname ($givenname);
    
    public function setLastname ($lastname);
    
    public function setGender ($gender);
    
    public function setAdsEnabled ($adsEnabled);
    
    public function addRole(RoleInterface $role, LanguageInterface $language = NULL);
    
    public function getRoles(LanguageInterface $language = NULL);
    
    public function populate (array $data = array());
}