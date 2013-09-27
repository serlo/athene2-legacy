<?php
/**
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace User\Controller;

use Zend\View\Model\ViewModel;
use User\Authentication\Adapter\AdapterInterface;
use User\Form\Register as RegisterForm;
use User\Form\Login as LoginForm;

class UserController extends AbstractUserController
{
    use \Common\Traits\AuthenticationServiceAwareTrait,\Common\Traits\ObjectManagerAwareTrait;

    protected function getObjectManager()
    {
        return $this->getUserManager()->getObjectManager();
    }

    /**
     *
     * @var AdapterInterface
     */
    private $authAdapter;

    /**
     *
     * @return AdapterInterface $authAdapter
     */
    public function getAuthAdapter()
    {
        return $this->authAdapter;
    }

    /**
     *
     * @param AdapterInterface $authAdapter            
     * @return $this
     */
    public function setAuthAdapter(AdapterInterface $authAdapter)
    {
        $this->authAdapter = $authAdapter;
        return $this;
    }

    public function loginAction()
    {
        $form = new LoginForm();
        $errorMessages = false;
        
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->params()
                ->fromPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->getAuthAdapter()->setIdentity($data['email']);
                $this->getAuthAdapter()->setPassword($data['password']);
                
                $result = $this->getAuthenticationService()->authenticate($this->getAuthAdapter());
                if ($result->isValid()) {
                    $this->getUserManager()
                        ->findUserByEmail($result->getIdentity())
                        ->updateLoginData();
                    $this->getUserManager()
                        ->getObjectManager()
                        ->flush();
                    $this->redirect()->toUrl($this->params('ref', '/'));
                }
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
            'errorMessages' => $result->getMessages()
        ));
        return $view;
    }

    public function logoutAction()
    {
        $this->getAuthenticationService()->clearIdentity();
        $this->redirect()->toUrl($this->getRefererUrl('/'));
    }

    public function registerAction()
    {
        if ($this->getAuthenticationService()->hasIdentity())
            $this->redirect()->toUrl($this->params('ref', '/'));
        
        $form = new RegisterForm($this->getObjectManager());
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()
                ->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $user = $this->getUserManager()->createUser($form->getData());
                $this->getObjectManager()->flush();
                $this->redirect()->toUrl($this->params('ref', '/'));
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        return $view;
    }

    public function lostPasswordAction()
    {}

    public function emailConfirmAction()
    {}

    public function removeAction()
    {
        $this->getUserManager()->trashUser($this->params('id', null));
        $this->getUserManager()
            ->getObjectManager()
            ->flush();
    }

    public function purgeAction()
    {
        $this->getUserManager()->purgeUser($this->params('id', null));
        $this->getUserManager()
            ->getObjectManager()
            ->flush();
    }

    public function updateAction()
    {}
}