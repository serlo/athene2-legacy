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
use Zend\View\Model\JsonModel;

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
                $upload = $this->getUploadManager()->upload($data['file']);
                $this->getUploadManager()->getObjectManager()->flush();
                return new JsonModel(array(
                    'success' => true,
                    'location' => $upload->getLocation(),
                    'size' => $upload->getSize(),
                    'id' => $upload->getId(),
                    'type' => $upload->getType(),
                    'filename' => $upload->getFilename()
                ));
            }
        }
        
        return $view;
    }
}