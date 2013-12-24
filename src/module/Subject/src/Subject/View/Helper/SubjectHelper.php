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
namespace Subject\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Subject\Options\ModuleOptions;
use Taxonomy\Entity\TaxonomyTermInterface;
use Subject\Exception;

class SubjectHelper extends AbstractHelper
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
     * @param TaxonomyTermInterface $subject
     * @return \Subject\Options\SubjectOptions
     */
    public function getOptions(TaxonomyTermInterface $subject)
    {
        return $this->getModuleOptions()->getInstance($subject->getSlug(), $subject->getLanguage()->getCode());
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