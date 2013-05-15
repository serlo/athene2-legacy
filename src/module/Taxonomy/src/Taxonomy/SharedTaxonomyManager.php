<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy;

use Doctrine\ORM\EntityManager;
use Core\Service\LanguageService;
use Taxonomy\Exception\NotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Taxonomy\Factory\EntityTaxonomy;
use Taxonomy\Entity\Taxonomy;
use Core\AbstractManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class SharedTaxonomyManager extends AbstractManager implements SharedTaxonomyManagerInterface, ObjectManagerAwareInterface
{

    /**
     *
     * @var LanguageService
     */
    protected $languageService;

    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;

    protected $options = array(
        'instances' => array(
            'manages' => 'Taxonomy\TermManager',
            'TaxonomyEntityInterface' => 'Taxonomy\Entity\Taxonomy',
            'TermManagerInterface' => 'Taxonomy\TermManager'
        )
    );

    public function __construct()
    {
        parent::__construct($this->options);
    }

    /**
     *
     * @return \Core\Service\LanguageService $languageService
     */
    public function getLanguageService()
    {
        return $this->languageService;
    }

    /**
     *
     * @param \Core\Service\LanguageService $languageService            
     * @return $this
     */
    public function setLanguageService(LanguageService $languageService)
    {
        $this->languageService = $languageService;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::getObjectManager()
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
    
    /*
     * (non-PHPdoc) @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::setObjectManager()
     */
    public function setObjectManager(\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    public function add(TermManagerInterface $termManager)
    {
        $this->addInstance($termManager->getId(), $termManager);
        return $termManager->getId();
    }

    public function get($taxonomy, $subjectService = NULL)
    {
        $className = $this->resolve('manages');
        if (is_numeric($taxonomy)) {
            $entity = $this->getObjectManager()->find($this->resolve('TaxonomyEntityInterface'), taxonomy);
            $name = $this->add($this->createInstance($entity));
        } else 
            if (is_string($taxonomy)) {
                $entity = $this->getObjectManager()
                    ->getRepository($this->resolve('TaxonomyEntityInterface'))
                    ->findOneBy(array(
                    'name' => $taxonomy,
                    //'subject' => $subjectService ? $subjectService->getEntity() : $subjectService
                ));
                $name = $this->add($this->createInstance($entity));
            } else 
                if (! $taxonomy instanceof $className) {
                    $name = $this->add($taxonomy);
                } else {
                    throw new \Exception();
                }
        if (! $this->hasInstance($name)) {
            throw new \Exception();
        }
        return $this->getInstance($name);
    }

    protected function createInstance($entity)
    {
        $instance = parent::createInstance();
        $instance->setEntity($entity);
        return $instance;
    }
}