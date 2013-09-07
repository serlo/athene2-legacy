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
namespace Subject\Plugin\Curriculum;

use Taxonomy\Service\TermServiceInterface;
use Subject\Plugin\Topic\TopicPlugin;

class CurriculumPlugin extends TopicPlugin
{    
    public function getTermManager(){
        return $this->getSharedTaxonomyManager()
            ->get('curriculum');        
    }

    public function get ($folder)
    {
        return $this->getSharedTaxonomyManager()
            ->get('school-type')
            ->get($folder);
    }

    public function getAll ()
    {
        $terms = $this->getSubjectService()->getTaxonomy('curriculum');
        $terms = $terms->getTerms();
        return $terms;
    }

    public function getEnabledEntityTypes ()
    {
        return $this->getSubjectService()->topic()->getEnabledEntityTypes();
    }
    
    public function isTypeEnabled($type){
        return $this->getSubjectService()->topic()->isTypeEnabled($type);
    }

    public function getEntityTypeLabel ($type, $label)
    {
        return $this->getSubjectService()->topic()->getEntityTypeLabel($type, $label);
    }

    public function getTemplateForEntityType ($type)
    {
        return $this->getSubjectService()->topic()->getTemplateForEntityType($type);
    }

    public function getRootFolders ($curriculum){
        return $this->getSharedTaxonomyManager()->get('subject')->get($curriculum)->getChildrenByTaxonomyName('school-type');
    }
}