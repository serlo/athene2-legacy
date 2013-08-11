<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;
use User\Entity\User;
use Zend\Form\Form;

class UserService implements UserServiceInterface
{

    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    protected $user;

    /**
     *
     * @return the
     *         $entityManager
     */
    public function getEntityManager ()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param Doctrine\ORM\EntityManager $entityManager            
     */
    public function setEntityManager (EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createListener ($e)
    {
        $data = $e->getParam('data');
        $form = $e->getParam('form');
        
        return $this->create($data, $form);
    }

    public function create ($data)
    {
        $user = new User();
        $user->populate($data);
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        return $user;
    }

    public function delete ($id)
    {}

    public function updateListener ($e)
    {}

    public function update (array $data, $form)
    {}

    public function receive ($id)
    {}

    public function getRoles ($user, $language = NULL, $subject = NULL)
    {
        $user = $this->get($user);
        $return = array();
        
        if (! $user)
            return $return;
        
        $userRolesCollection = $user->getUserRoles();
        
        foreach ($this->getEntityManager()
            ->getRepository('User\Entity\Role')
            ->findAll() as $role) {
            
            $roleCriteria = Criteria::create()->where(Criteria::expr()->eq("role", $role->getId()));
            $userRoles = $userRolesCollection->matching($roleCriteria);
            
            foreach ($userRoles as $userRole) {
                if (((($userRole->exists('language') && $language !== NULL) && ($userRole->__get('language')->id == $language)) || (! $userRole->exists('language')) || ($language === NULL))/* && (
                        (
                            (
                                $userRole->exists('subject') && $subject !== NULL
                            ) &&  (
                                $userRole->__get('subject')->id == $subject
                            )
                        ) || (
                            ! $userRole->exists('subject')
                        ) || (
                            $language === NULL
                        )
                    )*/)
                    {
                    $return[] = $role->get('name');
                }
            }
        }
        
        return $return;
    }

    public function get ($user)
    {
        if (! $user instanceof User) {
            if (is_numeric($user)) {
                $user = $this->getEntityManager()->find('User\Entity\User', $user);
            } else {
                $user = $this->getEntityManager()
                    ->getRepository('User\Entity\User')
                    ->findOneBy(array(
                    'email' => $user
                ));
            }
        }
        return $user;
    }

    public function hasRole ($user, $role, $language = NULL, $subject = NULL)
    {
        return array_search($role, $this->getRoles($user, $language, $subject)) !== FALSE;
    }
}