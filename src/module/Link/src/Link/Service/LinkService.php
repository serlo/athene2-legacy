<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace Link\Service;

use Core\OrmEntityManagerAwareInterface;
use Doctrine\ORM\EntityManager;
use Link\Entity\LinkEntityInterface;
use Core\Entity\AbstractEntityAdapter;
use Core\Entity\EntityInterface;

class LinkService extends AbstractEntityAdapter implements OrmEntityManagerAwareInterface, LinkServiceInterface {
	/**
	 * @var EntityManager
	 */
	protected $_entityManager;
	
	/* (non-PHPdoc)
	 * @see \Core\OrmEntityManagerAwareInterface::setEntityManager()
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->_entityManager = $entityManager;
	}

	/* (non-PHPdoc)
	 * @see \Core\OrmEntityManagerAwareInterface::getEntityManager()
	 */
	public function getEntityManager() {
		return $this->_entityManager;		
	}

	public function setEntity(EntityInterface $entity){
		return $this->_setEntity($entity);
	}
	
	protected function _setEntity(LinkEntityInterface $entity){
		return parent::setEntity($entity);
	}
	
	/* (non-PHPdoc)
	 * @see \Link\Service\LinkServiceInterface::getChildren()
	 */
	public function getChildren() {
		return $this->getEntity()->getChildren();
	}

	/* (non-PHPdoc)
	 * @see \Link\Service\LinkServiceInterface::getParents()
	 */
	public function getParents() {
		return $this->getEntity()->getParents();
	}

	/* (non-PHPdoc)
	 * @see \Link\Service\LinkServiceInterface::addParent()
	 */
	public function addParent(LinkServiceInterface $parent) {
		$this->getParents()->add($parent);
		$parent->getChildren()->add($this->getEntity());
		return $this->_flush();
	}

	/* (non-PHPdoc)
	 * @see \Link\Service\LinkServiceInterface::addChild()
	 */
	public function addChild(LinkServiceInterface $child) {
		$this->getChildren()->add($child);
		$child->getParents()->add($this->getEntity());
		return $this->flush();
	}
	
	protected function _flush(){
		$this->getEntityManager()->flush();
		return $this;
	}
}