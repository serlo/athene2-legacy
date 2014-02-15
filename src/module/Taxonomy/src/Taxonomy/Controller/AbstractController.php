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
namespace Taxonomy\Controller;

use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class AbstractController extends AbstractActionController
{
    use TaxonomyManagerAwareTrait;
    use InstanceManagerAwareTrait;

    public function __construct(InstanceManagerInterface $instanceManager, TaxonomyManagerInterface $taxonomyManager)
    {
        $this->instanceManager = $instanceManager;
        $this->taxonomyManager = $taxonomyManager;
    }

    protected function getTerm($id = null)
    {
        if ($id === null) {
            if ($this->params('id', null) === null) {
                return $this->getTaxonomyManager()->findTaxonomyByName(
                    'root',
                    $this->getInstanceManager()->getInstanceFromRequest()
                )->getChildren()->first();
            } else {
                return $this->getTaxonomyManager()->getTerm($this->params('id'));
            }
        } else {
            return $this->getTaxonomyManager()->getTerm($id);
        }
    }
}