<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace User\Service;

use User\Entity\UserLog as UserLogEntity;
use Log\Service\LoggerInterface;
use Auth\Service\AuthServiceInterface;
use Doctrine\ORM\EntityManager;
use User\Entity\User;
use Log\Service\AbstractLogger;

class UserLogService extends AbstractLogger implements LoggerInterface
{
    private $entityManager, $authService;
    private $entity;
    
	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * @return AuthServiceInterface
	 */
	public function getAuthService() {
		return $this->authService;
	}

	/**
	 * @param field_type $entityManager
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
	 * @param AuthServiceInterface $authService
	 */
	public function setAuthService(AuthServiceInterface $authService) {
		$this->authService = $authService;
	}
	
	public function logListener($event, $source, $params){
	    $action = isset($params['action']) ? $params['action'] : NULL;
	    $ref = isset($params['ref']) ? $params['ref'] : NULL;
	    $refId = isset($params['refId']) ? $params['refId'] : NULL;
	    $note = isset($params['note']) ? $params['note'] : NULL;
	    $user = isset($params['user']) ? $params['user'] : NULL;
	    $this->log($action, $ref, $refId, $note, $event, $source, $user);
	}

	public function log($action, $ref = NULL, $refId = NULL, $note = NULL, $event = NULL, $source = NULL, User $user = NULL) {
	    $entity = new UserLogEntity();
	    $entity->set('action', $action);
	    $entity->set('ref', $ref);
	    $entity->set('ref_id', $refId);
	    $entity->set('note', $note);
	    $entity->set('event', $event);
	    $entity->set('source', $source);
	    $entity->set('user', $user ? $user : $this->authService->getUser());
	    $this->entityManager->persist($entity);
	    $this->entityManager->flush();
	    return $this;
	}
}