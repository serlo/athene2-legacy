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
namespace Subject\Plugin\Taxonomy\Controller;

use Subject\Plugin\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class TaxonomyController extends AbstractController
{

    public function indexAction()
    {
        $subjectService = $this->getSubject();
        $plugin = $this->getPlugin();
        $term = false;
        $entities = array();
        
        if($this->params('path', NULL)){
            $term = $plugin->findTermByAncestors(explode('/', $this->params('path', NULL)));
        }
        
        if ($term && $term->isAssociationAllowed('entities')) {
            foreach ($term->getAssociated('entities')->asService() as $entity) {
                if (! $entity->isVoided()) {
                    $entities[] = $entity;
                }
            }
        }
        
        $view = new ViewModel(array(
            'term' => $term,
            'terms' => $term ? $term->getChildren() : $plugin->getRootFolders($this->params('subject', NULL)),
            'acceptsEntities' => $term ? $term->isAssociationAllowed('entities') : false,
            'subject' => $subjectService,
            'plugin' => $plugin,
            'links' => $entities
        ));
        
        $view->setTemplate($plugin->getTemplate('index'));
        return $view;
    }
}