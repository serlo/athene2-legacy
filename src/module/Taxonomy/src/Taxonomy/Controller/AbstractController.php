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
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Form\TermForm;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class AbstractController extends AbstractActionController
{
    use TaxonomyManagerAwareTrait;
    use InstanceManagerAwareTrait;

    /**
     * @var \Taxonomy\Form\TermForm
     */
    protected $termForm;

    /**
     * @param InstanceManagerInterface $instanceManager
     * @param TaxonomyManagerInterface $taxonomyManager
     * @param TermForm                 $termForm
     */
    public function __construct(
        InstanceManagerInterface $instanceManager,
        TaxonomyManagerInterface $taxonomyManager,
        TermForm $termForm
    ) {
        $this->instanceManager = $instanceManager;
        $this->taxonomyManager = $taxonomyManager;
        $this->termForm        = $termForm;
    }

    /**
     * @param null|int $id
     * @return TaxonomyTermInterface
     */
    public function getTerm($id = null)
    {
        $id = $this->params('id', $id);
        $id = $this->params('term', $id);
        if ($id === null) {
            $instance = $this->getInstanceManager()->getInstanceFromRequest();
            $root     = $this->getTaxonomyManager()->findTaxonomyByName('root', $instance)->getChildren()->first();
            if (!is_object($root)) {
                $root = $this->getTaxonomyManager()->createRoot($this->termForm);
                $this->getTaxonomyManager()->flush();
            }
            return $root;
        } else {
            return $this->getTaxonomyManager()->getTerm($id);
        }
    }
}
