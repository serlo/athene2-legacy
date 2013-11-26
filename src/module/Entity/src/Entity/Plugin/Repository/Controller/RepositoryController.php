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
namespace Entity\Plugin\Repository\Controller;

use Versioning\Exception\RevisionNotFoundException;
use Zend\View\Model\ViewModel;
use Entity\Plugin\Controller\AbstractController;
use Zend\Http\Request;

class RepositoryController extends AbstractController
{
    use\User\Manager\UserManagerAwareTrait, \Language\Manager\LanguageManagerAwareTrait;

    public function addRevisionAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        $view = new ViewModel(array(
            'entity' => $entity,
            'plugin' => $plugin
        ));
        
        /* @var $form \Zend\Form\Form */
        $form = $plugin->getRevisionForm();
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $revision = $plugin->commitRevision($form, $user);

                $this->getEventManager()->trigger('add-revision', $this, array(
                    'entity' => $entity,
                    'user' => $user,
                    'language' => $this->getLanguageManager()->getLanguageFromRequest(),
                    'revision' => $revision,
                    'post' => $this->params()
                        ->fromPost()
                ));

                $plugin->getObjectManager()->flush();
                $this->flashMessenger()->addSuccessMessage('Deine Bearbeitung wurde gespeichert. Du erhälst eine Benachrichtigung, sobald deine Bearbeitung geprüft wird.');
                $this->redirect()->toUrl($this->referer()->fromStorage());
                return '';
            }
        } else {
            $this->referer()->store();            
        }
        
        $view->setTemplate('entity/plugin/repository/update-revision');
        $view->setVariable('form', $form);
        
        return $view;
    }

    public function compareAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        
        $revision = $this->_getRevision($repository, $this->params('revision'), FALSE);
        $currentRevision = $this->_getRevision($repository);
        
        $view = new ViewModel(array(
            'currentRevision' => $currentRevision,
            'revision' => $revision,
            'entity' => $entity,
            'plugin' => $plugin
        ));
        
        $view->setTemplate('entity/plugin/repository/compare-revision');
        
        return $view;
    }

    public function historyAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        $currentRevision = $repository->hasCurrentRevision() ? $repository->getCurrentRevision() : NULL;
        $repositoryView = new ViewModel(array(
            'entity' => $entity,
            'revisions' => $repository->getAllRevisions(),
            'trashedRevisions' => $repository->getTrashedRevisions(),
            'currentRevision' => $currentRevision,
            'plugin' => $plugin
        ));
        
        $repositoryView->setTemplate('entity/plugin/repository/history');
        return $repositoryView;
    }

    protected function _getRevision($repository, $id = NULL, $catch = TRUE)
    {
        if ($catch) {
            try {
                if ($id === NULL) {
                    return $repository->getCurrentRevision();
                } else {
                    return $repository->getRevision($id);
                }
            } catch (RevisionNotFoundException $e) {
                return NULL;
            }
        } else {
            if ($id === NULL) {
                return $repository->getCurrentRevision();
            } else {
                return $repository->getRevision($id);
            }
        }
    }

    public function checkoutAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        $repository->checkout($this->params('revision'));
        
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        $this->getEventManager()->trigger('checkout', $this, array(
            'entity' => $entity,
            'revision' => $repository->getRevision($this->params('revision')),
            'user' => $user,
            'language' => $this->getLanguageManager()->getLanguageFromRequest(),            
        ));
        $plugin->getObjectManager()->flush();
        $this->redirect()->toRoute('entity/plugin/repository/history', array(
            'entity' => $entity->getId()
        ));
        return '';
    }
}