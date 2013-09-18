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
namespace Taxonomy\Controller;

use Zend\View\Model\ViewModel;
use Taxonomy\Form\TaxonomyForm;

class TermController extends AbstractController
{
    public function updateAction ()
    {
        $id = $this->params('id');
        $term = $this->getTerm($id);
        
        $view = new ViewModel(array(
            'id' => $id
        ));
        
        $form = new TaxonomyForm();
        
        $form->setData($term->getArrayCopy());
        $view->setVariable('form', $form);
        
        $from = rawurlencode($this->getRefererUrl('/'));
        
        $form->setAttribute('action', $this->url()->fromRoute('taxonomy/term/action', array(
            'action' => 'update',
            'id' => $id,
        )) . '?ref=' . $from)
        ;
        
        $view->setTemplate('taxonomy/term/form');
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $term->update($form->getData());
                $this->flashMessenger()->addSuccessMessage('Bearbeitung erfoglreich gespeichert!');
                $this->redirect()->toUrl($this->params()
                    ->fromQuery('ref'));
            }
        }
        
        return $view;
    }

    public function createAction ()
    {
        $taxonomyId = $this->getSharedTaxonomyManager()->getTaxonomy($this->params('taxonomy'))->getId();
        $parentId = $this->params('parent', null);
        
        $form = new TaxonomyForm();
        $form->setData(array(
            'taxonomy' => $taxonomyId,
            'parent' => $parentId
        ));
        
        $form->setAttribute('action', $this->url()
            ->fromRoute('taxonomy/term/create', array('parent' => $this->params('parent'), 'taxonomy' => $this->params('taxonomy'))) . '?ref=' . rawurlencode($this->getRefererUrl('/')));
        
        $view = new ViewModel();
        
        $view->setTemplate('taxonomy/term/form');
        $view->setVariable('form', $form);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSharedTaxonomyManager()
                    ->getTaxonomy($data['taxonomy'])
                    ->createTerm($form->getData());
                
                $this->flashMessenger()->addSuccessMessage('Knoten erfolgreich hinzugefügt!');
                $this->redirect()->toUrl($this->params()
                    ->fromQuery('ref'));
            }
        }
        return $view;
    }

    public function deleteAction ()
    {
        $this->getSharedTaxonomyManager()->deleteTerm($this->getParam('id'));
        
        $this->flashMessenger()->addSuccessMessage('Knoten erfolgreich gelöscht!');
        $this->redirect()->toUrl($this->getRequest()
            ->getHeader('Referer')
            ->getUri());
    }

    public function orderAction ()
    {
        $data = $this->params()->fromPost('sortables');
        $this->iterWeight($data['children']);
        return $this->response;
    }

    protected function iterWeight ($terms, $parent = NULL)
    {
        $weight = 1;
        foreach ($terms as $term) {
            if( (integer) $term['id'] != -1){
                $entity = $this->getTerm($term['id']);
                if ($parent) {
                    $entity->setParent($this->getTerm($parent)
                        ->getEntity());
                } else {
                    $entity->setParent(NULL);                    
                }
                $entity->setWeight($weight);
                $entity->persistAndFlush();
                if (isset($term['children'])) {
                    $this->iterWeight($term['children'], $term['id']);
                }
                $weight++;
            }
        }
        return true;
    }

    protected function getTerm ($id = NULL)
    {
        if ($id) {
            return $this->getSharedTaxonomyManager()->getTermService($id);
        }
        return $this->getSharedTaxonomyManager()->getTermService($this->getParam('id'));
    }
}