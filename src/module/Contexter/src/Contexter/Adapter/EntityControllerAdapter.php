<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Contexter\Adapter;

use Entity\Controller\AbstractController;
use Instance\Manager\InstanceManagerAwareTrait;

/**
 * Class EntityControllerAdapter
 *
 * @package Contexter\Adapter
 * @method
 */
class EntityControllerAdapter extends AbstractAdapter
{
    use InstanceManagerAwareTrait;

    public function getProvidedParams()
    {
        /* @var $controller AbstractController */
        $params        = $this->getRouteParams();
        $controller    = $this->getController();
        $entity = $controller->getEntity($params['entity']);

        $array = [
            'type'     => $entity->getType()->getName(),
            'instance' => $this->getInstanceManager()->getInstanceFromRequest()->getName()
        ];

        foreach($entity->getTaxonomyTerms() as $term){
            while($term->hasParent()){
                $array[$term->getTaxonomy()->getName()][] = $term->getSlug();
                $term = $term->getParent();
            }
        }

        return $array;
    }

    public function isValidController($controller){
        return $controller instanceof AbstractController;
    }
}
