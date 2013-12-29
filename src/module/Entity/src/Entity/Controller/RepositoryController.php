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
use Entity\Options\ModuleOptions;
use Zend\Form\Form;

class RepositoryController extends AbstractController
{
    use \User\Manager\UserManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait,\Versioning\RepositoryManagerAwareTrait;

    /**
     *
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     *
     * @param ModuleOptions $moduleOptions            
     * @return self
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
        return $this;
    }

    public function addRevisionAction()
    {
        $entity = $this->getEntity();
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        /* @var $form \Zend\Form\Form */
        $form = $this->getForm($entity);
        
        $view = new ViewModel(array(
            'entity' => $entity,
            'form' => $form
        ));
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $language = $this->getLanguageManager()->getLanguageFromRequest();
                
                $this->getRepositoryManager()
                    ->getRepository($entity)
                    ->commitRevision($data, $user);
                
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
        $user = $this->getUserManager()->getUserFromAuthenticator();
        $repository = $this->getRepositoryManager()->getRepository($entity);
        $revision = $repository->findRevision($this->params('revision'));
        $repository->checkoutRevision($revision->getId());
        
        $this->getEventManager()->trigger('checkout', $this, array(
            'entity' => $entity,
            'revision' => $revision
        ));
        
        $this->getEntityManager()->flush();
        
        $this->redirect()->toRoute('entity/repository/history', array(
            'entity' => $entity->getId()
        ));
        
        return false;
    }

    /**
     *
     * @param EntityInterface $entity            
     * @param string $id            
     * @return \Versioning\Service\RevisionInterface NULL
     */
    protected function getRevision(EntityInterface $entity, $id = null)
    {
        $repository = $this->getRepositoryManager()->getRepository($entity);
        try {
            if ($id === NULL) {
                return $entity->getCurrentRevision();
            } else {
                return $repository->findRevision($id);
            }
        } catch (RevisionNotFoundException $e) {
            return NULL;
        }
    }

    /**
     *
     * @param EntityInterface $entity            
     * @return Form
     */
    protected function getForm(EntityInterface $entity)
    {
        $form = $this->moduleOptions->getType($entity->getType()
            ->getName())
            ->getComponent('repository')
            ->getForm();
        return $this->getServiceLocator()->get($form);
    }
}