<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace User\Service;

use Zend\Form\Form;
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
    public function create($form);
    
    public function delete($id);
    
    public function updateListener($e);
    
    public function update(array $data, $form);
    
    public function receive($id);
    
	public function hasRole($user, $role, $language = NULL, $subject = NULL);
	
	public function getRoles ($user, $language = NULL, $subject = NULL);
}