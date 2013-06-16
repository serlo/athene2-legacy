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
namespace Application\Taxonomy\Controller;

use Zend\View\Model\ViewModel;
use Taxonomy\Controller\AbstractController;
use Taxonomy\Entity\TaxonomyFactory;
use Application\Taxonomy\Form\TaxonomyForm;

class TermController extends AbstractController
{

    public function updateAction ()
    {
        $id = $this->params()->fromPost('id') ? $this->params()->fromPost('id') : $this->params()->fromQuery('id');
        $term = $this->getTerm($id);
        
        $view = new ViewModel(array(
            'id' => $id
        ));
        
        $form = $term->getForm();
        $view->setVariable('form', $form);
        $from = '/';
        if (is_object($this->getRequest()->getHeader('Referer')))
            $from = rawurlencode($this->getRequest()
                ->getHeader('Referer')
                ->getUri());
        
        $form->setAttribute('action', $this->url()->fromRoute('taxonomy/term', array(
            'action' => 'update'
        )) . '?ref=' . $from)
        ;
        
        $view->setTemplate('taxonomy/term/update');
        
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
        $taxonomyId = $this->params()->fromQuery('taxonomy');
        $parentId = $this->params()->fromQuery('parent');
        
        $form = new TaxonomyForm();
        $form->setData(array(
            'taxonomy' => $taxonomyId,
            'parent' => $parentId
        ));
        $form->setAttribute('action', $this->url()
            ->fromRoute('taxonomy/term', array(
            'action' => 'create'
        )) . '?ref=' . rawurlencode($this->getRequest()
            ->getHeader('Referer')
            ->getUri()));
        
        $view = new ViewModel();
        
        $view->setTemplate('taxonomy/term/update');
        $view->setVariable('form', $form);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSharedTaxonomyManager()
                    ->get($data['taxonomy'])
                    ->create($form->getData());
                
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
            return $this->getSharedTaxonomyManager()->getTerm($id);
        }
        return $this->getSharedTaxonomyManager()->getTerm($this->getParam('id'));
    }
}