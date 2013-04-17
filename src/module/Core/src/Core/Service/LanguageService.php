<?php
namespace Core\Service;
use Doctrine\ORM\EntityManager;

/**      
 */
class LanguageService
{
    private $entityManager;

    /**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * @param EntityManager $entityManager
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
     * 
     */
    function get ()
    {
        throw new \Exception("depr");
    	return 1;
    }
    
    public function getEntity(){
        return $this->getEntityManager()->find('Core\Entity\Language',1);
    }
    
    public function getId(){
    	return 1;
    }
}

?>