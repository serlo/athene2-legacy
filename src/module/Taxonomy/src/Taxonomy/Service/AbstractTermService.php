<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Service;

abstract class AbstractTermService implements TermServiceInterface
{
    
    use \Term\Manager\TermManagerAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Taxonomy\Router\TermRouterAwareTrait;
}