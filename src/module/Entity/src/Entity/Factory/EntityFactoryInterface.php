<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\Factory;

use Core\Entity\ModelInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Auth\Service\AuthServiceInterface;
use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Core\Service\SubjectService;
use Entity\Factory\EntityBuilderInterface;
use Taxonomy\SharedTaxonomyManagerInterface;
use Core\Service\LanguageManager;
use Link\LinkManagerInterface;
use Entity\EntityManagerInterface;
use Versioning\RepositoryManagerAwareInterface;
use Core\OrmEntityManagerAwareInterface;

interface EntityFactoryInterface extends ModelInterface, OrmEntityManagerAwareInterface, RepositoryManagerAwareInterface, EventManagerAwareInterface
{
    /**
     * @return EntityManagerInterface $manager
     */
    public function getManager ();
    
    /**
     * @param EntityManagerInterface $manager
     * @return $this
     */
    public function setManager (EntityManagerInterface $manager);
    
    /**
     * @return LinkManagerInterface $linkManager
     */
    public function getLinkManager();
    
    /**
     * @param LinkManagerInterface $linkManager
     * @return $this
     */
    public function setLinkManager(LinkManagerInterface $linkManager);
    
    /**
     * @return LanguageManager
     */
    public function getLanguageManager();
    
    /**
     * @param LanguageManager $languageManager
     * @return $this
     */
    public function setLanguageManager(LanguageManager $languageManager);
    
    /**
     * @return SharedTaxonomyManagerInterface
     */
    public function getSharedTaxonomyManager();
    
    /**
     * @param SharedTaxonomyManagerInterface $_sharedTaxonomyManager
     */
    public function setSharedTaxonomyManager(SharedTaxonomyManagerInterface $_sharedTaxonomyManager);
    
    /**
     * @return the $factory
     */
    public function getFactory();
    
    /**
     * @param EntityBuilderInterface $factory
     * @return $this
     */
    public function setFactory(EntityBuilderInterface $factory);
    
    /**
     * @return the $languageService
     */
    public function getLanguageService();
    
    /**
     * @return the $subjectService
     */
    public function getSubjectService();
    
    /**
     * @param LanguageService $languageService
     */
    public function setLanguageService(LanguageService $languageService);
    
    /**
     * @param SubjectService $subjectService
     */
    public function setSubjectService(SubjectService $subjectService);
    
    /**
     * @return AuthServiceInterface
     */
    public function getAuthService();
    
    /**
     * @param AuthServiceInterface $authService
     */
    public function setAuthService(AuthServiceInterface $authService);
    
    public function addComponent($name, $component);
    
    public function getComponent($name);
    
    public function build();
    
    public function __call($name, $arguments);
}