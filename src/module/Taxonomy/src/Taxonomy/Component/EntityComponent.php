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
namespace Taxonomy\Component;

use Core\Component\AbstractComponent;
use Core\Component\ComponentInterface;
use Taxonomy\Service\TermServiceInterface;
use Core\Collection\DecoratorCollection;

class EntityComponent extends AbstractComponent implements ComponentInterface
{
    protected $publicMethods = array('getEntities', 'addEntity');
    
    /**
     * 
     * @var TermServiceInterface
     */
    protected $termService;
    
    /*
     * (non-PHPdoc) @see \Taxonomy\Factory\FactoryInterface::build()
     */
    public function __construct(TermServiceInterface $termService)
    {
        $this->termService = $termService;
        $sm = $termService->getServiceLocator();
        $em = $sm->get('Entity\Manager\EntityManager');
        $termService->enableLink('entities', function ($collection) use($em)
        {
            /*$entity = $em->get($entity);
            if(is_object($entity) && $entity->providesMethod('isCheckedOut') && !$entity->isCheckedOut()){
                return null;
            }
            return $entity;*/
        	return new \Entity\Collection\EntityCollection($collection, $em);
        });
        return $this;
    }
    
    public function getEntities(){
        $entities = $this->termService->getLinks('entities');
        return $entities;
    }
    
    public function addEntity($entity){
        $this->termService->addLink('entities',$entity);
        return $this;
    }
}