<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Search\Controller;

use Search\Form\SearchForm;
use Search\SearchServiceAwareTrait;
use Search\SearchServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    use SearchServiceAwareTrait;

    /**
     * @param SearchServiceInterface $searchService
     */
    public function __construct(SearchServiceInterface $searchService)
    {
        $this->searchService = $searchService;
    }

    public function ajaxAction()
    {
        $form = new SearchForm();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data      = $form->getData();
                $container = $this->getSearchService()->search($data['q']);
                $view      = new JsonModel($container->toArray());
                return $view;
            }
        }
        return new JsonModel([]);
    }

    public function searchAction()
    {
        $form = new SearchForm();
        $view = new ViewModel(['form' => $form, 'query' => '']);

        $view->setTemplate('search/search');
        $this->layout('layout/1-col');

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data      = $form->getData();
                $container = $this->getSearchService()->search($data['q']);
                $view->setVariable('container', $container);
                $view->setVariable('query', $data['q']);
                $view->setTemplate('search/results');
            }
        }

        return $view;
    }
}
