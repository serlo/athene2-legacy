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

use Contexter\Adapter\AdaptableInterface;
use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class AbstractController extends AbstractActionController implements AdaptableInterface
{
    use TaxonomyManagerAwareTrait;
    use InstanceManagerAwareTrait;

    /**
     * @param InstanceManagerInterface $instanceManager
     * @param TaxonomyManagerInterface $taxonomyManager
     */
    public function __construct(InstanceManagerInterface $instanceManager, TaxonomyManagerInterface $taxonomyManager)
    {
        $this->instanceManager = $instanceManager;
        $this->taxonomyManager = $taxonomyManager;
    }

    /**
     * @param null|int $id
     * @return TaxonomyTermInterface
     */
    public function getTerm($id = null)
    {
        $id = $id ? : $this->params('id');
        if ($id === null) {
            $instance = $this->getInstanceManager()->getInstanceFromRequest();
            return $this->getTaxonomyManager()->findTaxonomyByName('root', $instance)->getChildren()->first();
        } else {
            return $this->getTaxonomyManager()->getTerm($id);
        }
    }
}
