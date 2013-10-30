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
namespace LearningResource\Plugin\Link\Controller;

use Entity\Plugin\Controller\AbstractController;

class LinkController extends AbstractController
{
    public function orderChildrenAction(){
        $entity = $this->getEntityService();
        foreach($entity->getScopesForPlugin('link') as $scope){
            if($scope == $this->params('scope')){
                $data = $this->params()->fromPost()['sortable'];
                $entity->plugin($scope)->orderChildren($data);
            }
        }
        $this->getEntityManager()->getObjectManager()->flush();
        return '';
    }
}