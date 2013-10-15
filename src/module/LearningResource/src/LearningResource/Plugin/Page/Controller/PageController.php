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
namespace LearningResource\Plugin\Page\Controller;

use Entity\Plugin\Controller\AbstractController;
use Versioning\Exception\RevisionNotFoundException;
use Entity\Exception\EntityNotFoundException;

class PageController extends AbstractController
{
    use \Language\Manager\LanguageManagerAwareTrait;

    public function indexAction()
    {
        try {
            $page = $plugin = $this->getPlugin();
            $entity = $this->getEntityService();
        } catch (EntityNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        try {
            $model = new \Zend\View\Model\ViewModel(array('entity' => $entity, 'plugin' => $page));
            $page->hydrate($model);
            $model->setTemplate($page->getTemplate());
            return $model;
        } catch (RevisionNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }

    public function getEntityService($slug = NULL)
    {
        if (! $this->entityService) {
            
            if (! $slug) {
                $slug = $this->params('slug');
            }
            
            $this->entityService = $this->getEntityManager()->findEntityBySlug($slug, $this->getLanguageManager()
                ->getLanguageFromRequest());
        }
        
        return $this->entityService;
    }
}