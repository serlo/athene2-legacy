<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Factory;

use Core\Structure\AbstractAdapter;
use Taxonomy\TaxonomyManagerInterface;

class Tree extends AbstractAdapter implements FactoryInterface
{
    /*
     * (non-PHPdoc) @see \Taxonomy\Factory\FactoryInterface::build()
     */
    public function build (TaxonomyManagerInterface $adaptee)
    {
        return $this;
    }

    public function getServiceLocator ()
    {
        return $this->getAdaptee()->getServiceLocator();
    }
}