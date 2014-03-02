<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Subject\View\Helper;

use Subject\Options\ModuleOptions;
use Taxonomy\Entity\TaxonomyTermInterface;
use Zend\View\Helper\AbstractHelper;

class SubjectHelper extends AbstractHelper
{

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @return self
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param TaxonomyTermInterface $subject
     * @return \Subject\Options\SubjectOptions
     */
    public function getOptions(TaxonomyTermInterface $subject)
    {
        return $this->getModuleOptions()->getInstance($subject->getSlug(), $subject->getInstance()->getName());
    }

    /**
     * @return ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     * @param ModuleOptions $moduleOptions
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @param TaxonomyTermInterface $term
     * @param string                $parent
     * @return string
     */
    public function slugify(TaxonomyTermInterface $term, $parent = 'subject')
    {
        return substr($this->processSlugs($term, $parent), 0, -1);
    }

    /**
     * @param TaxonomyTermInterface $term
     * @param string                $parent
     * @return string
     */
    protected function processSlugs(TaxonomyTermInterface $term, $parent)
    {
        return ($term->getTaxonomy()->getName() != $parent) ?
            $this->processSlugs($term->getParent(), $parent) . $term->getSlug() . '/' : '';
    }
}
