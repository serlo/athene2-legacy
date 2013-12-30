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
namespace Entity\Controller;

use Zend\View\Model\ViewModel;

class TaxonomyController extends AbstractController
{
    use \Language\Manager\LanguageManagerAwareTrait,\Taxonomy\Manager\TaxonomyManagerAwareTrait;

    public function updateAction()
    {
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName('root', $language);
        $entity = $this->getEntity();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            if (array_key_exists('terms', $data)) {
                foreach ($data['terms'] as $termId => $added) {
                    
                    $term = $this->getTaxonomyManager()->getTerm($termId);
                    
                    if ($added == 1) {
                        $this->getTaxonomyManager()->associateWith($termId, 'entities', $entity);
                        $event = 'addToTerm';
                    } elseif ($added == 0) {
                        $this->getTaxonomyManager()->removeAssociation($termId, 'entities', $entity);
                        $event = 'removeFromTerm';
                    }
                    
                    $this->getEventManager()->trigger($event, $this, array(
                        'entity' => $entity,
                        'term' => $term
                    ));
                }
                
                $this->getEntityManager()->flush();
                $this->redirect()->toUrl($this->referer()
                    ->fromStorage());
                
                return false;
            }
        } else {
            $this->referer()->store();
        }
        
        $view = new ViewModel(array(
            'terms' => $taxonomy->getChildren(),
            'entity' => $entity
        ));
        
        $view->setTemplate('entity/taxonomy/update');
        return $view;
    }
}