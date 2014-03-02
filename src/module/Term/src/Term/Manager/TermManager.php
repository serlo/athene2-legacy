<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 * http://dev/
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Term\Manager;

use ClassResolver\ClassResolverAwareTrait;
use Common\Filter\Slugify;
use Common\Traits\ObjectManagerAwareTrait;
use Instance\Entity\InstanceInterface;
use Term\Entity\TermEntityInterface;
use Term\Exception\TermNotFoundException;

class TermManager implements TermManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;

    /**
     * @var TermEntityInterface[]
     */
    protected $terms = [];

    public function getTerm($id)
    {
        $instance = $this->getObjectManager()->find(
            $this->getClassResolver()->resolveClassName('Term\Entity\TermEntityInterface'),
            $id
        );

        if (!is_object($instance)) {
            throw new TermNotFoundException($id);
        }

        return $instance;
    }

    public function findTermBySlug($slug, InstanceInterface $instance)
    {
        $entity = $this->getObjectManager()->getRepository(
            $this->getClassResolver()->resolveClassName('Term\Entity\TermEntityInterface')
        )->findOneBy(
                [
                    'slug'     => $slug,
                    'instance' => $instance->getId()
                ]
            );

        if (!is_object($entity)) {
            throw new TermNotFoundException(sprintf('Term %s with instance %s not found', $slug, $instance->getId()));
        }

        return $entity;
    }

    public function findTermByName($name, InstanceInterface $instance)
    {
        $entity = $this->getObjectManager()->getRepository(
            $this->getClassResolver()->resolveClassName('Term\Entity\TermEntityInterface')
        )->findOneBy(
                [
                    'name'     => $name,
                    'instance' => $instance->getId()
                ]
            );

        if (!is_object($entity)) {
            throw new TermNotFoundException(sprintf('Term %s with instance %s not found', $name, $instance->getId()));
        }

        return $entity;
    }

    public function createTerm($name, InstanceInterface $instance)
    {
        try {
            return $this->findTermByName($name, $instance);
        } catch (TermNotFoundException $e) {
            foreach ($this->terms as $term) {
                if ($term->getName() == $name) {
                    return $term;
                }
            }
        }

        /* @var $entity TermEntityInterface */
        $filter        = new Slugify();
        $slug          = $filter->filter($name);
        $entity        = $this->getClassResolver()->resolve('Term\Entity\TermEntityInterface');
        $this->terms[] = $entity;

        $entity->setName($name);
        $entity->setInstance($instance);
        $entity->setSlug($slug);
        $this->getObjectManager()->persist($entity);

        return $entity;
    }
}
