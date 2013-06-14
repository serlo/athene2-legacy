<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *http://dev/
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Term\Manager;

use Term\Service\TermServiceInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Core\AbstractManager;
use Term\Exception\TermNotFoundException;
use Core\Service\LanguageManager;


class TermManager extends AbstractManager implements ObjectManagerAwareInterface, TermManagerInterface
{
    /**
     * @var LanguageManager
     */
    protected $languageManager;

    protected $options = array(
        'instances' => array(
            'TermEntityInterface' => 'Term\Entity\Term',
            'manages' => 'Term\Service\TermService'
        )
    );
    

    /**
     * @return \Core\Service\LanguageManager $languageManager
     */
    public function getLanguageManager ()
    {
        return $this->languageManager;
    }

	/**
     * @param \Core\Service\LanguageManager $languageManager
     * @return $this
     */
    public function setLanguageManager (LanguageManager $languageManager)
    {
        $this->languageManager = $languageManager;
        return $this;
    }

	public function __construct(){
        parent::__construct($this->options);
    }

    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;
    
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

    /**
     * 
     * @param TermServiceInterface $termService
     */
    public function add(TermServiceInterface $termService)
    {
        $this->addInstance($termService->getName(), $termService);
        return $termService->getName();
    }
    
    public function get($term)
    {
        if (is_numeric($term)) {
            $return = $this->getById($term);
        } else 
            if ($term instanceof TermServiceInterface) {
                $return = $this->getByService($term);
            } else 
                if (is_string($term)) {
                    $return = $this->getByString($term);
                } else {
                    throw new \InvalidArgumentException();
                }
        
        return $return;
    }

    protected function getById($id)
    {
        $term = $this->getObjectManager()->find($this->resolve('TermEntityInterface'), $id);
        if (! is_object($term))
            throw new TermNotFoundException($id);
        
        if(!$this->hasInstance($term->getName())){
            $this->add($this->createInstance($term));
        }
        
        return $this->getInstance($term->getName());
    }

    protected function getByService(TermServiceInterface $term)
    {
        if (! $this->hasInstance($term->getName())) {
            $this->add($term);
        }
        return $this->getInstance($term->getName());
    }

    protected function getByString($name, $slug = NULL)
    {
        // TODO: get request language (!)
        
        if (! $this->hasInstance($name)) {
            $entity = $this->getObjectManager()
                ->getRepository($this->resolve('TermEntityInterface'))
                ->findOneBy(array(
                'name' => $name
            ));
            if (! is_object($entity)) {
                $entity = $this->getObjectManager()
                    ->getRepository($this->resolve('TermEntityInterface'))
                    ->findOneBy(array(
                    'slug' => $name
                ));
            }
            if (! is_object($entity)){
                $entity = $this->resolve('TermEntityInterface', true);
                $entity->setName($name);
                $entity->setLanguage($this->getLanguageManager()->getRequestLanguage()->getEntity());
                $entity->setSlug(
                    ($slug ? $slug : 
                        strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'))
                    )    
                );
                $em = $this->getObjectManager();
                $em->persist($entity);
                $em->flush();
            }
            $this->add($this->createInstance($entity));
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