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
namespace Subject\Service;

use Core\Service\AbstractEntityDecorator;
use Taxonomy\SharedTaxonomyManagerAwareInterface;
use Doctrine\Common\Collections\Criteria;

class SubjectService implements SubjectServiceInterface, SharedTaxonomyManagerAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait, \Entity\Manager\EntityManagerAwareTrait, \Subject\Manager\SubjectManagerAwareTrait; //, \Common\Traits\EntityDelegatorTrait;
    
    protected $subjectManager;
    
    protected $sharedTaxonomyManager;
    
	/* (non-PHPdoc)
     * @see \Subject\SubjectManagerAwareInterface::getSubjectManager()
     */
    public function getSubjectManager ()
    {
        return $this->subjectManager;
    }

	/* (non-PHPdoc)
     * @see \Subject\SubjectManagerAwareInterface::setSubjectManager()
     */
    public function setSubjectManager (\Subject\SubjectManagerInterface $subject)
    {
        $this->subjectManager = $subject;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\SharedTaxonomyManagerAwareInterface::getSharedTaxonomyManager()
     */
    public function getSharedTaxonomyManager ()
    {
        return $this->sharedTaxonomyManager;
    }

	/* (non-PHPdoc)
     * @see \Taxonomy\SharedTaxonomyManagerAwareInterface::setSharedTaxonomyManager()
     */
    public function setSharedTaxonomyManager (\Taxonomy\SharedTaxonomyManagerInterface $sharedTaxonomyManager)
    {
        $this->sharedTaxonomyManager = $sharedTaxonomyManager;
    }
    
    public function getTaxonomy($name){ 
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("name", $name))
            ->setMaxResults(1);
        $taxonomy = $this->getEntity()->getTaxonomies()->matching($criteria)->current();
        return $this->getSharedTaxonomyManager()->get($taxonomy);
    }
    
    private $decorator;
    
}