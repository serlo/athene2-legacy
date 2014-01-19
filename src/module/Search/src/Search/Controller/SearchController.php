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
namespace Search\Controller;

use Search\Form\SearchForm;
use Search\SearchServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    use SearchServiceAwareTrait;

    public function searchAction()
    {
        $form  = new SearchForm();

        $view = new ViewModel([
            'form' => $form,
            'query' => ''
        ]);

        $view->setTemplate('search/form');
        $this->layout('layout/1-col');

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();

                $container = $this->getSearchService()->search($data['q'], array(
                    'entity',
                    'taxonomyTerm'
                ));

                $view->setVariable('container', $container);
                $view->setVariable('query', $data['q']);
                $view->setTemplate('search/results');
            }
        }

        return $view;
    }

    public function ajaxAction()
    {
        $form = new SearchForm();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                
                $results = $this->getSearchService()->search($data['q'], array(
                    'entity',
                    'taxonomyTerm'
                ));

                $results = $this->getSearchService()->ajaxify($results);

                $view = new JsonModel($results);
                return $view;
            }
        }
    }
}
