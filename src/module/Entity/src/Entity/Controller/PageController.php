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
namespace Entity\Controller;

use Alias\AliasManagerAwareTrait;
use Alias\Exception\AliasNotFoundException;
use Entity\Exception\EntityNotFoundException;
use Versioning\Exception\RevisionNotFoundException;
use Zend\View\Model\ViewModel;

class PageController extends AbstractController
{
    use AliasManagerAwareTrait;

    public function indexAction()
    {
        try {
            $entity = $this->getEntity();
        } catch (EntityNotFoundException $e) {
            $this->getResponse()->setStatusCode(404);

            return;
        }

        if (!$this->params('forwarded')) {
            try {
                $alias = $this->getAliasManager()->findAliasByObject($entity);
                $this->redirect()->toUrl('/alias/' . $alias->getAlias());
            } catch (AliasNotFoundException $e) {
            }
        }

        try {
            $model = new ViewModel([
                'entity' => $entity
            ]);
            $model->setTemplate('entity/page/default');
            $this->layout('layout/3-col');

            return $model;
        } catch (RevisionNotFoundException $e) {
            return $this->getResponse()->setStatusCode(404);
        }
    }
}
