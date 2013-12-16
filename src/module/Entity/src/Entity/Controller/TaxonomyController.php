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
namespace Entity\Controller;

use Zend\View\Model\ViewModel;

class TaxonomyController extends AbstractController
{
    use \Language\Manager\LanguageManagerAwareTrait;

    public function updateAction()
    {
        $plugin = $this->getPlugin();
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        
        $subject = $this->getEntityService()
            ->provider()
            ->getSubjectSlug();
        /* @var $plugin \Entity\Plugin\Taxonomy\TaxonomyPlugin */
        $taxonomy = $plugin->getSharedTaxonomyManager()->findTaxonomyByName('subject', $language);
        
        $saplings = $taxonomy->findTermByAncestors(array(
            'mathe'
        ))->getChildren();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            if (array_key_exists('terms', $data)) {
                foreach ($data['terms'] as $term => $added) {
                    if ($added == 1) {
                        if (! $plugin->hasTerm($term)) {
                            $plugin->addToTerm($term);
                            
                            $this->getEventManager()->trigger('addToTerm', $this, array(
                                'entity' => $plugin->getEntityService(),
                                'term' => $plugin->getSharedTaxonomyManager()
                                    ->getTerm($term)
                            ));
                        }
                    } elseif ($added == 0) {
                        if ($plugin->hasTerm($term)) {
                            $plugin->removeFromTerm($term);
                            
                            $this->getEventManager()->trigger('removeFromTerm', $this, array(
                                'entity' => $plugin->getEntityService(),
                                'term' => $plugin->getSharedTaxonomyManager()
                                    ->getTerm($term)
                            ));
                        }
                    }
                }
                $this->getEventManager()->trigger('update', $this, array(
                    'entity' => $this->getEntityService(),
                    'post' => $data
                ));
                $plugin->getObjectManager()->flush();
                $this->redirect()->toUrl($data['ref']);
                return '';
            }
        } else {
            $ref = $this->referer()->toUrl();
        }
        
        $view = new ViewModel(array(
            'terms' => $saplings,
            'plugin' => $plugin,
            'entity' => $this->getEntityService(),
            'ref' => $ref
        ));
        $view->setTemplate('entity/plugin/taxonomy/update');
        return $view;
    }
}