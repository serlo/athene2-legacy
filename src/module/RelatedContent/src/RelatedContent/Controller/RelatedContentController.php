<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace RelatedContent\Controller;

use Authorization\Service\AuthorizationAssertionTrait;
use RelatedContent\Form\CategoryForm;
use RelatedContent\Form\ExternalForm;
use RelatedContent\Form\InternalForm;
use RelatedContent\Manager\RelatedContentManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RelatedContentController extends AbstractActionController
{
    use RelatedContentManagerAwareTrait, AuthorizationAssertionTrait;

    public function addCategoryAction()
    {
        $this->assertGranted('related_content.add');
        $form = new CategoryForm();
        $form->setAttribute(
            'action',
            $this->url()->fromRoute(
                'related-content/add-category',
                array(
                    'id' => $this->params('id')
                )
            )
        );

        $view = new ViewModel(array(
            'form' => $form
        ));

        if ($this->getRequest()->isPost()) {
            $form->setData(
                $this->getRequest()->getPost()
            );
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getRelatedContentManager()->addCategory((int)$this->params('id'), $data['title']);
                $this->getRelatedContentManager()->getObjectManager()->flush();
                $this->redirect()->toRoute(
                    'related-content/manage',
                    array(
                        'id' => $this->params('id')
                    )
                );
            }
        }
        $view->setTemplate('related-content/add-category');

        return $view;
    }

    public function addExternalAction()
    {
        $this->assertGranted('related_content.add');
        $form = new ExternalForm();
        $form->setAttribute(
            'action',
            $this->url()->fromRoute(
                'related-content/add-external',
                array(
                    'id' => $this->params('id')
                )
            )
        );

        $view = new ViewModel(array(
            'form' => $form
        ));

        if ($this->getRequest()->isPost()) {
            $form->setData(
                $this->getRequest()->getPost()
            );
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getRelatedContentManager()->addExternal((int)$this->params('id'), $data['title'], $data['url']);
                $this->getRelatedContentManager()->getObjectManager()->flush();
                $this->redirect()->toRoute(
                    'related-content/manage',
                    array(
                        'id' => $this->params('id')
                    )
                );
            }
        }
        $view->setTemplate('related-content/add-external');

        return $view;
    }

    public function addInternalAction()
    {
        $this->assertGranted('related_content.add');
        $form = new InternalForm();
        $form->setAttribute(
            'action',
            $this->url()->fromRoute(
                'related-content/add-internal',
                array(
                    'id' => $this->params('id')
                )
            )
        );

        $view = new ViewModel(array(
            'form' => $form
        ));

        if ($this->getRequest()->isPost()) {
            $form->setData(
                $this->getRequest()->getPost()
            );
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getRelatedContentManager()->addInternal(
                    (int)$this->params('id'),
                    $data['title'],
                    $data['reference']
                );
                $this->getRelatedContentManager()->getObjectManager()->flush();
                $this->redirect()->toRoute(
                    'related-content/manage',
                    array(
                        'id' => $this->params('id')
                    )
                );
            }
        }
        $view->setTemplate('related-content/add-internal');

        return $view;
    }

    public function manageAction()
    {
        $this->assertGranted('related_content.manage');

        $aggregated = $this->getRelatedContentManager()->aggregateRelatedContent((int)$this->params('id'));
        $view       = new ViewModel(array(
            'aggregated' => $aggregated,
            'id'         => $this->params('id')
        ));
        $view->setTemplate('related-content/manage');

        return $view;
    }

    public function orderAction()
    {
        $this->assertGranted('related_content.sort');

        $position = 1;
        if ($this->getRequest()->isPost()) {
            foreach ($this->params()->fromPost('sortable', array()) as $holder) {
                $this->getRelatedContentManager()->positionHolder((int)$holder['id'], (int)$position);
                $position++;
            }
        }
        $this->getRelatedContentManager()->getObjectManager()->flush();

        return false;
    }

    public function removeAction()
    {
        $this->assertGranted('related_content.purge');

        $this->getRelatedContentManager()->removeRelatedContent((int)$this->params('id'));
        $this->getRelatedContentManager()->getObjectManager()->flush();
        $this->redirect()->toReferer();

        return false;
    }
}
