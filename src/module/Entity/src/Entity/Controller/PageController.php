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

use Versioning\Exception\RevisionNotFoundException;
use Entity\Exception\EntityNotFoundException;
use Alias\Exception\AliasNotFoundException;

class PageController extends AbstractController
{
    use\Alias\AliasManagerAwareTrait;

    public function indexAction()
    {
        try {
            $entity = $this->getEntity();
        } catch (EntityNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        if (! $this->params('forwarded')) {
            try {
                $alias = $this->getAliasManager()->findAliasByObject($entity->getUuidEntity());
                $this->redirect()->toUrl('/alias/' . $alias->getAlias());
            } catch (AliasNotFoundException $e) {}
        }
        
        try {
            $model = new \Zend\View\Model\ViewModel(array(
                'entity' => $entity
            ));
            $model->setTemplate('entity/page/default');
            return $model;
        } catch (RevisionNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }
}