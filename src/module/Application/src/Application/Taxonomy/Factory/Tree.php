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
use Application\Taxonomy\Term;
use Taxonomy\Factory\FactoryInterface;

class Tree extends AbstractFactory implements FactoryInterface, TermServiceInterface
{
    
    public function build (TermServiceInterface $termService)
    {
        $instance = new Term($termService);
        $instance->setTemplate('taxonomy/default/partial');
        return parent::build($instance, $termService);
    }
}