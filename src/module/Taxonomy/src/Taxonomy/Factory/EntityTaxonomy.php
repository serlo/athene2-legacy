<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Factory;

use Taxonomy\Service\TermServiceInterface;
use Core\Structure\AbstractDecorator;

abstract class EntityTaxonomy extends AbstractDecorator implements FactoryInterface
{
    /*
     * (non-PHPdoc) @see \Taxonomy\Factory\FactoryInterface::build()
     */
    public function build (TermServiceInterface $adaptee)
    {
        $this->setConcreteComponent($adaptee);
        $sm = $this->getServiceLocator();
        $em = $sm->get('Entity\EntityManager');
        $this->enableLink('entities', function ($entity) use($em)
        {
            return $em->get($entity);
        });
        return $this;
    }

    public function getServiceLocator ()
    {
        return $this->getAdaptee()->getServiceLocator();
    }
    
    public function getEntities(){
        return $this->getLinks('entities');
    }
    
    public function addEntity($entity){
        $this->addLink('entities',$entity);
        return $this;
    }
}