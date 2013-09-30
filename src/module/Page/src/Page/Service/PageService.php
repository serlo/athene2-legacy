<?php
namespace Page\Service;

use Doctrine\ORM\EntityManager;



class PageService implements PageServiceInterface  {

	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * Sets the EntityManager
	 *
	 * @param EntityManager $em
	 * @access protected
	 * @return PostController
	 */
	public function setEntityManager(EntityManager $em)
	{
		$this->entityManager = $em;
		return $this;
	}

	/**
	 * Returns the EntityManager
	 *
	 * Fetches the EntityManager from ServiceLocator if it has not been initiated
	 * and then returns it
	 *
	 * @access protected
	 * @return EntityManager
	 */

	public function getEntityManager()
	{

		return $this->entityManager;
	}



	public function create() {
		return "page";
	}
	
	public function read() {
		$repository = $this->getEntityManager()->getRepository('Page\Entity\Page');
		$posts      = $repository->findAll();
		return $posts;
	}
	
	public function getPage($slug) {
		$repository = $this->getEntityManager()->getRepository('Page\Entity\Page');
		$revisions      = $repository->findBy(array('slug' => $slug));
		return $revisions;
	}
	
	public function getRevision($id) {
		$repository = $this->getEntityManager()->getRepository('Page\Entity\PageRevision');
		$revisions      = $repository->findOneBy(array('id' => $id));

		return $revisions;
	}
	
	public function update(){
	}
	
	public function delete(){
	}

}

?>