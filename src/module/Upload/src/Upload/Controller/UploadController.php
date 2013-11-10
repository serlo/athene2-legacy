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
namespace Upload\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Upload\Form\UploadForm;
use Zend\View\Model\ViewModel;

class UploadController extends AbstractActionController
{
    use \Upload\Manager\UploadManagerAwareTrait;
    
    public function uploadAction()
    {
        $form = new UploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('upload'));
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('upload/form');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getFiles()->toArray();
    
            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getUploadManager()->upload($data);
                $this->getUploadManager()->getObjectManager()->flush();
                $view->setTemplate('upload/success');
            }
        }
        
        return $view;
    }
}