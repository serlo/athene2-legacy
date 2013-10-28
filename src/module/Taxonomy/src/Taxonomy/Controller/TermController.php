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
use Taxonomy\Form\TermForm;

class TermController extends AbstractController
{

    public function organizeAction(){
        $term = $this->getTerm();
    
        $view = new ViewModel(array(
            'term' => $term,
        ));
    
        $view->setTemplate('taxonomy/term/organize');
        return $view;
    }
    
    public function updateAction()
    {
        $id = $this->params('id');
        $term = $this->getTerm($id);
        
        $view = new ViewModel(array(
            'id' => $id,
            'isUpdating' => true
        ));
        
        $form = new TermForm();
        
        $form->setData($term->getArrayCopy());
        $view->setVariable('form', $form);
        
        $form->setAttribute('action', $this->url()
            ->fromRoute('taxonomy/term/action', array(
            'action' => 'update',
            'id' => $id
        )) . '?ref=' . $this->params('ref', '/'));
        
        $view->setTemplate('/taxonomy/term/form');
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $this->getSharedTaxonomyManager()->updateTerm($this->params('id'), $form->getData());
                $this->getSharedTaxonomyManager()
                    ->getObjectManager()
                    ->flush();
                $this->flashMessenger()->addSuccessMessage('Bearbeitung erfoglreich gespeichert!');
                $this->redirect()->toUrl($this->params('ref', '/'));
            }
        }
        
        return $view;
    }

    public function createAction()
    {
        $form = new TermForm();

        $form->setData(array(
            'taxonomy' => $this->params('taxonomy'),
            'parent' => $this->params('parent', null)
        ));
        
        $form->setAttribute('action', $this->url()
            ->fromRoute('taxonomy/term/create', array(
            'parent' => $this->params('parent'),
            'taxonomy' => $this->params('taxonomy')
        )) . '?ref=' . rawurlencode($this->referer()
            ->toUrl()));
        
        $view = new ViewModel(array(
            'form' => $form,
            'isUpdating' => false
        ));
        
        $view->setTemplate('/taxonomy/term/form');
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSharedTaxonomyManager()->createTerm($form->getData(), $this->getLanguageManager()
                    ->getLanguageFromRequest());
                
                $this->getSharedTaxonomyManager()
                    ->getObjectManager()
                    ->flush();
                
                $this->flashMessenger()->addSuccessMessage('Knoten erfolgreich hinzugefügt!');
                $this->redirect()->toUrl($this->params()->fromQuery('ref', '/'));
            }
        }
        return $view;
    }

    public function deleteAction()
    {
        $this->getSharedTaxonomyManager()->deleteTerm($this->getParam('id'));
        
        $this->flashMessenger()->addSuccessMessage('Knoten erfolgreich gelöscht!');
        $this->redirect()->toUrl($this->getRequest()
            ->getHeader('Referer')
            ->getUri());
    }

    public function orderAssociatedAction()
    {
        $associations = $this->params()->fromPost('sortable', array());
        $termService = $this->getTerm($this->params('term'));
        $i = 0;
        
        foreach ($associations as $association) {
            $termService->orderAssociated('entities', $association['id'], $i);
            $i++;
        }
        
        $this->getSharedTaxonomyManager()
            ->getObjectManager()
            ->flush();
        
        return '';        
    }

    public function orderAction()
    {
        $data = $this->params()->fromPost('sortable', array());
        $this->iterWeight($data, $this->params('id'));
        $this->getSharedTaxonomyManager()
            ->getObjectManager()
            ->flush();
        return '';
    }

    protected function iterWeight($terms, $parent = NULL)
    {
        $weight = 1;
        foreach ($terms as $term) {
            $entity = $this->getTerm($term['id']);
            if ($parent) {
                $entity->setParent($this->getTerm($parent)
                    ->getEntity());
            } else {
                $entity->setParent(NULL);
            }
            $entity->setOrder($weight);
            $this->getSharedTaxonomyManager()->getObjectManager()->persist($entity->getEntity());
            if (isset($term['children'])) {
                $this->iterWeight($term['children'], $term['id']);
            }
            $weight ++;
        }
        return true;
    }
}