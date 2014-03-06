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
use Zend\View\Model\ViewModel;

class PageController extends AbstractController
{
    use AliasManagerAwareTrait;

    public function indexAction()
    {
        $entity = $this->getEntity();

        if (!$entity) {
            return false;
        }

        if (!$this->params('forwarded', false)) {
            try {
                $alias = $this->getAliasManager()->findAliasByObject($entity);
                return $this->redirect()->toRoute('alias', ['alias' => $alias->getAlias()]);
            } catch (AliasNotFoundException $e) {
                // No Alias found? Well, then we got nothing to do!
            }
        }

        $model = new ViewModel(['entity' => $entity]);
        $model->setTemplate('entity/page/default');

        if ($this->params('isXmlHttpRequest', false)) {
            $model->setTemplate('entity/view/default');
        }

        $this->layout('layout/3-col');
        return $model;
    }
}
