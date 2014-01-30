<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Authentication\Controller;

use Common\Traits\AuthenticationServiceAwareTrait;
use User\Form\Login;
use User\Manager\UserManagerAwareTrait;
use User\Manager\UserManagerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthenticationController extends AbstractActionController
{
    use AuthenticationServiceAwareTrait, UserManagerAwareTrait;

    public function __construct(AuthenticationService $authenticationService, UserManagerInterface $userManager)
    {
        $this->authenticationService = $authenticationService;
        $this->userManager           = $userManager;
    }

    public function loginAction()
    {
        $form          = new Login();
        $messages      = array();

        $this->layout('layout/1-col');

        if ($this->getRequest()->isPost()) {

            $post = $this->params()->fromPost();
            $form->setData($post);

            if ($form->isValid()) {
                $data    = $form->getData();
                $adapter = $this->getAuthenticationService()->getAdapter();

                $adapter->setIdentity($data['email']);
                $adapter->setCredential($data['password']);

                $result = $this->getAuthenticationService()->authenticate();

                if ($result->isValid()) {
                    $user = $this->getUserManager()->getUser(
                        $result->getIdentity()->getId()
                    );
                    $user->updateLoginData();

                    $this->getUserManager()->persist($user);
                    $this->getUserManager()->flush();

                    $this->redirect()->toUrl($this->params('ref', '/'));
                }
                $messages = $result->getMessages();
            }
        }

        $view = new ViewModel(array(
            'form'          => $form,
            'errorMessages' => $messages
        ));

        $view->setTemplate('authentication/login');

        return $view;
    }
}
