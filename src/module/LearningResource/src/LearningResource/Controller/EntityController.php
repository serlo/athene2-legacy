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
namespace LearningResource\Entity\Controller;

use Entity\Controller\AbstractController;

class EntityController extends AbstractController {
	public function deleteAction ()
	{
		$entity = $this->getEntity();
		$entity->getManager()->delete($entity);
	
		$this->flashMessenger()->addSuccessMessage('Löschung erfolgreich!');
		$ref = $this->getRequest()
		->getHeader('Referer')
		->getUri();
		$ref = $ref ? $ref : '/';
		$this->redirect()->toUrl($ref);
	
		return null;
	}
	
	public function purgeAction ()
	{
		throw new \Exception('Not implemented');
		// TODO
	}

    public function createAction ()
    {
        $term = $this->params()->fromQuery('term');
        if (! $term)
            throw new \InvalidArgumentException();
        
        $entity = $this->getEntityManager()->create($this->getEntityFactory());
        $term = $this->getSharedTaxonomyManager()->getTerm($term);
        
        $term->addEntity($entity);
        
        $this->getObjectManager()->flush();
        
        $this->redirect()->toRoute($entity->getRoute(), array(
            'action' => 'update',
            'id' => $entity->getId()
        ));
    }
}