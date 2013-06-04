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
namespace Application\Subject\DefaultSubject\Controller;

use Application\LearningObject\Exercise\Controller\TextExerciseController;

class ExerciseController extends TextExerciseController
{    
    public function getSharedTaxonomyManager(){
        return $this->getServiceLocator()->get('Taxonomy\SharedTaxonomyManager');
    }
    
    public function getObjectManager(){
        return $this->getServiceLocator()->get('EntityManager');
    }
    
    public function createAction(){
        $term = $this->params()->fromQuery('term');
        if(!$term)
            throw new \InvalidArgumentException();
        
        $entity = $this->getEntityManager()->create($this->getEntityFactory());
        $term = $this->getSharedTaxonomyManager()->getTerm($term);
        
        $term->addEntity($entity);
        
        $this->getObjectManager()->flush();
        
        $this->redirect()->toRoute(get_class($entity), array(
            'action' => 'update',
            'id' => $entity->getId()
        ));
    }
}