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
namespace Taxonomy\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Taxonomy\Options\ModuleOptions;
use Taxonomy\Entity\TaxonomyInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Exception;

class TaxonomyHelper extends AbstractHelper
{

    /**
     *
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     *
     * @return ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     * 
     * @param TaxonomyInterface|TaxonomyTermInterface|string $nameOrObject
     * @throws Exception\InvalidArgumentException
     * @return Ambigous \Taxonomy\Options\TaxonomyOptions
     */
    public function getOptions($nameOrObject)
    {
        if ($nameOrObject instanceof TaxonomyInterface) {
            $name = $nameOrObject->getName();
        } elseif ($nameOrObject instanceof TaxonomyTermInterface) {
            $name = $nameOrObject->getTaxonomy()->getName();
        } elseif (is_string($nameOrObject)) {
            $name = $nameOrObject;
        } else {
            throw new Exception\InvalidArgumentException(sprintf('Expected $nameOrObject to be TaxonomyInterface, TaxonomyTermInterface or string but got "%s"', is_object($nameOrObject) ? get_class($nameOrObject) : gettype($nameOrObject)));
        }
        
        return $this->getModuleOptions()->getType($name);
    }

    /**
     *
     * @param ModuleOptions $moduleOptions            
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
        return $this;
    }
}