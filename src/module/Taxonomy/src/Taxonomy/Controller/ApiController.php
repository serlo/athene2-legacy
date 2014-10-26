<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Controller;

use Taxonomy\Entity\TaxonomyTermInterface;
use Zend\View\Model\JsonModel;

class ApiController extends AbstractController
{
    public function typesAction()
    {
        $type     = $this->params('type');
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName($type, $instance);
        $data     = [
            'name'     => $taxonomy->getName(),
            'instance' => $taxonomy->getInstance()->getId(),
            'id'       => $taxonomy->getId(),
            'saplings' => [],
        ];
        foreach ($taxonomy->getChildren() as $term) {
            $data['saplings'][] = $this->ajaxify($term);
        }
        $view = new JsonModel($data);
        return $view;
    }

    protected function ajaxify(TaxonomyTermInterface $term)
    {
        $data = [
            'id'       => $term->getId(),
            'name'     => $term->getName(),
            'type'     => $term->getType()->getName(),
            'url'      => $this->url()->fromRoute('uuid/get', ['uuid' => $term->getId()]),
            'children' => []
        ];

        foreach ($term->getChildren() as $child) {
            $data['children'][] = $this->ajaxify($child);
        }

        return $data;
    }
}
