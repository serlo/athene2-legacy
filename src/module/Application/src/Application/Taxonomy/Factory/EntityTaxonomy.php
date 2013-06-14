<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Application\Taxonomy\Factory;

use Taxonomy\Service\TermServiceInterface;
use Taxonomy\Component\EntityComponent;
use Taxonomy\Factory\FactoryInterface;

class EntityTaxonomy extends AbstractFactory implements FactoryInterface, TermServiceInterface
{
    /*
     * (non-PHPdoc) @see \Taxonomy\Factory\FactoryInterface::build()
     */
    public function build (TermServiceInterface $termService)
    {
        $instance = new \Application\Taxonomy\Term($termService);
        $instance->addComponent(new EntityComponent($termService));
        $instance->setTemplate('taxonomy/default/partial');
        return parent::build($instance, $termService);
    }
}