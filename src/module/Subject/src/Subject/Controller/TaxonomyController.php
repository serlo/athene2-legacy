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
namespace Subject\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Entity\Entity\EntityInterface;
use Taxonomy\Exception\TermNotFoundException;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Zend\View\Model\ViewModel;

class TaxonomyController extends AbstractController
{
    public function indexAction()
    {
        try {
            $subject = $this->getSubject();
            $term    = $subject->findChildBySlugs(explode('/', $this->params('path')));
            if (!is_object($term)) {
                return $this->getResponse()->setStatusCode(404);
            }
        } catch (TermNotFoundException $e) {
            return $this->getResponse()->setStatusCode(404);
        }

        $entities = $term->getAssociated('entities')->filter(
            function (EntityInterface $e) {
                return !$e->isTrashed() && $e->hasCurrentRevision();
            }
        );

        foreach($entities as $e){
            $types[$e->getType()->getName()][] = $e;
        }
        $types = new ArrayCollection($types);

        $view = new ViewModel([
            'term'    => $term,
            'terms'   => $term ? $term->getChildren() : $subject->getChildren(),
            'subject' => $subject,
            'links'   => $entities,
            'types'   => $types
        ]);

        $view->setTemplate('subject/taxonomy/page/default');

        return $view;
    }
}