<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Factory;

use Taxonomy\TaxonomyManagerInterface;
use Core\Structure\AbstractAdapter;

abstract class EntityTaxonomy extends AbstractAdapter implements FactoryInterface
{
    /*
     * (non-PHPdoc) @see \Taxonomy\Factory\FactoryInterface::build()
     */
    public function build (TaxonomyManagerInterface $adaptee)
    {
        $this->setAdaptee($adaptee);
        $sm = $this->getServiceLocator();
        $em = $sm->get('Entity\EntityManager');
        $this->getAdaptee()->enableLink('entities', function ($entity) use($em)
        {
            return $em->get($entity);
        });
        return $this;
    }

    public function getServiceLocator ()
    {
        return $this->getAdaptee()->getServiceLocator();
    }
}