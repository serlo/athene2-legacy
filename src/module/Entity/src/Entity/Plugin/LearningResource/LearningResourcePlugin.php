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
namespace Entity\Plugin\LearningResource;

use Entity\Plugin\AbstractPlugin;
use Entity\Plugin\LearningResource\Exception;
use Entity\Service\EntityServiceInterface;

class LearningResourcePlugin extends AbstractPlugin
{
    protected $checkedDependencies = false;
    
    protected function getDefaultConfig()
    {
        return array();
    }
    
    public function setEntityService(EntityServiceInterface $entityService){
        $return = parent::setEntityService($entityService);
        $this->checkedDependencies = false;
        $this->checkDependencies();
        return $return;
    }

    public function checkDependencies()
    {
        if($this->checkedDependencies = true)
            return true;
        
        $entityService = $this->getEntityService();
        
        if (! $entityService->hasPlugin('page'))
            throw new Exception\UnstatisfiedDependencyException('Missing dependency: page');
        
        if (! $entityService->hasPlugin('license'))
            throw new Exception\UnstatisfiedDependencyException('Missing dependency: license');
        
        if (! $entityService->hasPlugin('repository'))
            throw new Exception\UnstatisfiedDependencyException('Missing dependency: repository');
        
        if (! $entityService->hasPlugin('metadata'))
            throw new Exception\UnstatisfiedDependencyException('Missing dependency: metadata');
        
        if (! $entityService->hasPlugin('taxonomy')) {
            if (count($entityService->getScopesForPlugin('link'))) {
                $statisfyable = false;
                foreach ($entityService->getScopesForPlugin('link') as $scope) {
                    /* @var $linked \Entity\Plugin\Link\LinkPlugin */
                    $linked = $entityService->plugin($scope);
                    if ($linked->hasParents()) {
                        foreach ($linked->findParents() as $parent) {
                            $parent->plugin('learningResource')->checkDependencies();
                            $statisfyable = false;
                        }
                    }
                }
                if(!$statisfyable)
                    throw new Exception\UnstatisfiedDependencyException('Missing dependency taxonomy, and not resolvable through parents');
            } else {
                throw new Exception\UnstatisfiedDependencyException('Missing dependency: taxonomy');
            }
        }
        $this->checkedDependencies = true;
        return true;
    }
}