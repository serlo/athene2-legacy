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
namespace Application\Taxonomy\Controller;

use Zend\View\Model\ViewModel;
use Taxonomy\Controller\AbstractController;

class TaxonomyController extends AbstractController
{

    public function showAction(){

        $id = $this->params()->fromRoute('id');
        $taxonomy = $this->getSharedTaxonomyManager()->get($id);
    
        $view = new ViewModel(array(
            'term' => $taxonomy,
        ));
    
        $view->setTemplate('taxonomy/taxonomy/tree');
        foreach($taxonomy->getRootTerms() as $child){
            $taxView = new ViewModel(array('term' => $child));
            $view->addChild($taxView->setTemplate('taxonomy/term/partial'), 'taxonomy', true);
        }
        
        return $view;
    }
}