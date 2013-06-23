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
namespace Issue\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Issue\Manager\IssueManagerAware;
use Zend\View\Model\ViewModel;
use Issue\Form\IssueForm;
use Auth\Service\AuthServiceAware;

class IssueController extends AbstractActionController implements IssueManagerAware
{
    protected $authService;
    
    /**
     *
     * @var \Issue\Manager\IssueManagerInterface
     */
    protected $issueManager;

    public function indexAction ()
    {
        $view = new ViewModel(array(
            'issues' => array(
                'open' => $this->getIssueManager()->getAllOpenIssues(),
                'closed' => $this->getIssueManager()->getAllClosedIssues()
            )
        ));
        return $view;
    }

    public function showAction ()
    {
        $view = new ViewModel(array(
            'issue' => $this->getIssue()
        ));
        return $view;
    }

    public function createAction ()
    {
        $id = $this->params('id');
        $issue = $this->getIssueManager()->create($id);
        $this->redirect()->toRoute('issue', array('action' => 'update', 'id' => $issue->getId()));
    }

    public function updateAction ()
    {
        $form = new IssueForm();
        $issue = $this->getIssue();
        $form->setAttribute('action', $this->url()->fromRoute('issue', array('action' => 'update', 'id' => $issue->getId())));
        $form->setData($issue->toArray());
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if($form->isValid()){
                $issue->setAuthor($this->auth()->getUser());
                $issue->update(array_merge($form->getData()));
                $this->flashMessenger()->addSuccessMessage('Bearbeitung erfoglreich gespeichert!');
                $this->redirect()->toRoute('issue', array('action' => 'show', 'id' => $issue->getId()));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
            'issue' => $issue,
        ));
        return $view;
    }

    public function deleteAction ()
    {
        $this->getIssueManager()->remove($this->getIssue());
        $this->flashMessenger()->addSuccessMessage('Issue erfolgreich gelöscht.');
        $this->goBack();
    }

    private function getIssue ()
    {
        $id = $this->params('id');
        $issue = $this->getIssueManager()->get($id);
        if (! is_object($issue))
            $this->getResponse()->setStatusCode(404);
        return $issue;
    }
    
    /*
     * (non-PHPdoc) @see \Issue\Manager\IssueManagerAware::getIssueManager()
     */
    public function getIssueManager ()
    {
        return $this->issueManager;
    }
    
    /*
     * (non-PHPdoc) @see \Issue\Manager\IssueManagerAware::setIssueManager()
     */
    public function setIssueManager (\Issue\Manager\IssueManagerInterface $manager)
    {
        $this->issueManager = $manager;
        return $this;
    }
}