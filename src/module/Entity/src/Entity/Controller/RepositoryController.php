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

use Versioning\Exception\RevisionNotFoundException;
use Zend\View\Model\ViewModel;
use Entity\Entity\EntityInterface;

class RepositoryController extends AbstractController
{
    use \User\Manager\UserManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    public function addRevisionAction()
    {
        $entity = $this->getEntity();
        $user = $this->getUserManager()
            ->getUserFromAuthenticator()
            ->getEntity();
        
        $view = new ViewModel(array(
            'entity' => $entity
        ));
        
        /* @var $form \Zend\Form\Form */
        $form = $this->getEntityManager()->getRevisionForm($entity);
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $revision = $this->getEntityManager()->commitRevision($entity, $form, $user);
                
                $this->getEventManager()->trigger('add-revision', $this, array(
                    'entity' => $entity,
                    'user' => $user,
                    'language' => $this->getLanguageManager()
                        ->getLanguageFromRequest(),
                    'revision' => $revision,
                    'post' => $this->params()
                        ->fromPost()
                ));
                
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage('Your revision has been saved, it will be available once it get\'s approved');
                $this->redirect()->toUrl($this->referer()
                    ->fromStorage());
                return false;
            }
        } else {
            $this->referer()->store();
        }
        
        $view->setTemplate('entity/repository/update-revision');
        $view->setVariable('form', $form);
        
        return $view;
    }

    public function compareAction()
    {
        $entity = $this->getEntity();
        
        $revision = $this->getRevision($entity, $this->params('revision'));
        $currentRevision = $this->getRevision($entity);
        
        $view = new ViewModel(array(
            'currentRevision' => $currentRevision,
            'revision' => $revision,
            'entity' => $entity
        ));
        
        $view->setTemplate('entity/repository/compare-revision');
        
        return $view;
    }

    public function historyAction()
    {
        $entity = $this->getEntity();
        
        $currentRevision = $entity->hasCurrentRevision() ? $entity->getCurrentRevision() : NULL;
        
        $view = new ViewModel(array(
            'entity' => $entity,
            'revisions' => $entity->getRevisions(),
            'currentRevision' => $currentRevision
        ));
        
        $view->setTemplate('entity/repository/history');
        return $view;
    }

    public function checkoutAction()
    {
        $entity = $this->getEntity();
        $revision = $entity->getRevision($this->params('revision'));
        $entity->setCurrentRevision($revision);
        
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        $this->getEventManager()->trigger('checkout', $this, array(
            'entity' => $entity,
            'revision' => $revision,
            'user' => $user,
            'language' => $this->getLanguageManager()
                ->getLanguageFromRequest()
        ));
        
        $this->getEntityManager()->flush();
        
        $this->redirect()->toRoute('entity/repository/history', array(
            'entity' => $entity->getId()
        ));
        
        return false;
    }

    protected function getRevision(EntityInterface $repository, $id = null)
    {
        try {
            if ($id === NULL) {
                return $repository->getCurrentRevision();
            } else {
                return $repository->getRevision($id);
            }
        } catch (RevisionNotFoundException $e) {
            return NULL;
        }
    }
}