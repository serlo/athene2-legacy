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
namespace Entity\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Entity\Options\ModuleOptions;
use Entity\Entity\EntityInterface;
use Entity\Options\EntityOptions;

class EntityHelper extends AbstractHelper
{

    /**
     *
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     *
     * @return ModuleOptions $moduleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
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

    /**
     * 
     * @param EntityInterface $entity
     * @return EntityOptions
     */
    public function getOptions(EntityInterface $entity)
    {
        return $this->getModuleOptions()->getType($entity->getType()
            ->getName());
    }
}