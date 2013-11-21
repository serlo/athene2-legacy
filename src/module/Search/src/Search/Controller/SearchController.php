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

use Zend\Mvc\Controller\AbstractActionController;
use Search\Form\SearchForm;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    use \Search\SearchServiceAwareTrait;

    public function searchAction()
    {
        $form = new SearchForm();
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('search/form');
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $results = $this->getSearchService()->search($data['query'], array(
                    'entity'
                ));
                $view->setVariable('results', $results);
                $view->setTemplate('search/results');
                return $view;
            }
        }
        return $view;
    }
}