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
namespace Subject\Controller;

use Zend\View\Model\ViewModel;

class TaxonomyController extends AbstractController
{
    use\Taxonomy\Manager\TaxonomyManagerAwareTrait;

    public function indexAction()
    {
        $subject = $this->getSubject();
        $term = false;
        $entities = array();
        
        if ($this->params('path', NULL)) {
            $term = $subject->findChildBySlugs(explode('/', $this->params('path', NULL)));
        }
        
        foreach ($term->getAssociated('entities') as $entity) {
            if (! $entity->getTrashed()) {
                $entities[] = $entity;
            }
        }
        
        $view = new ViewModel(array(
            'term' => $term,
            'terms' => $term ? $term->getChildren() : $subject->getChildren(),
            'subject' => $subject,
            'links' => $entities
        ));
        
        $view->setTemplate('subject/taxonomy/page/default');
        return $view;
    }
}