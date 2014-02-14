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
namespace RelatedContent\Manager;

use Authorization\Service\AuthorizationAssertionTrait;
use ClassResolver\ClassResolverAwareTrait;
use Common\Traits\FlushableTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Common\Traits\RouterAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use RelatedContent\Entity;
use RelatedContent\Entity\ContainerInterface;
use RelatedContent\Exception;
use RelatedContent\Result\CategoryResult;
use RelatedContent\Result\ExternalResult;
use RelatedContent\Result\InternalResult;
use Uuid\Manager\UuidManagerAwareTrait;

class RelatedContentManager implements RelatedContentManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;
    use RouterAwareTrait, UuidManagerAwareTrait;
    use FlushableTrait, AuthorizationAssertionTrait;

    public function getContainer($id)
    {
        /* @var $related Entity\ContainerInterface */
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\ContainerInterface');
        $related   = $this->getObjectManager()->find($className, $id);

        if (!is_object($related)) {
            return $this->createContainer($id);
        }

        return $related;
    }

    public function aggregateRelatedContent($id)
    {
        $related = $this->getContainer($id);

        return $results = $this->aggregate($related);
    }

    public function addExternal($container, $title, $url)
    {
        $this->assertGranted('related_content.add');

        /* @var $external Entity\ExternalInterface */
        $external  = $this->getClassResolver()->resolve('RelatedContent\Entity\ExternalInterface');
        $container = $this->getContainer($container);
        $holder    = $this->createHolder($container);

        $external->setTitle($title);
        $external->setHolder($holder);
        $external->setUrl($url);

        $this->getObjectManager()->persist($external);

        return $external;
    }

    public function addInternal($container, $title, $reference)
    {
        $this->assertGranted('related_content.add');

        /* @var $internal Entity\InternalInterface */
        $internal  = $this->getClassResolver()->resolve('RelatedContent\Entity\InternalInterface');
        $object    = $this->getUuidManager()->getUuid($reference);
        $container = $this->getContainer($container);
        $holder    = $this->createHolder($container);

        $internal->setTitle($title);
        $internal->setHolder($holder);
        $internal->setReference($object);

        $this->getObjectManager()->persist($internal);

        return $internal;
    }

    public function addCategory($container, $name)
    {
        $this->assertGranted('related_content.add');

        $container = $this->getContainer($container);
        $holder    = $this->createHolder($container);
        /* @var $internal Entity\CategoryInterface */
        $internal = $this->getClassResolver()->resolve('RelatedContent\Entity\CategoryInterface');
        $internal->setTitle($name);
        $internal->setHolder($holder);
        $this->getObjectManager()->persist($internal);

        return $internal;
    }

    public function removeRelatedContent($id)
    {
        $this->assertGranted('related_content.purge');

        /* @var $object Entity\HolderInterface */
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\HolderInterface');
        $object    = $this->getObjectManager()->find($className, $id);

        if (!is_object($object)) {
            throw new Exception\RelationNotFoundException(sprintf('Could not find internal by id `%d`', $id));
        }

        $this->getObjectManager()->remove($object);
    }

    public function positionHolder($holder, $position)
    {
        $this->assertGranted('related_content.sort');

        /* @var $holder Entity\HolderInterface */
        $className = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\HolderInterface');
        $holder    = $this->getObjectManager()->find($className, $holder);

        if (!is_object($holder)) {
            throw new Exception\RuntimeException(sprintf('Holder not found by id `%d`', $holder));
        }

        $holder->setPosition($position);
        $this->getObjectManager()->persist($holder);
    }

    /**
     *
     * @param int $id
     * @return Entity\ContainerInterface
     */
    protected function createContainer($id)
    {
        /* @var $container Entity\ContainerInterface */
        $uuid      = $this->getUuidManager()->getUuid($id);
        $container = $this->getClassResolver()->resolveClassName('RelatedContent\Entity\ContainerInterface');
        $container = new $container($uuid);

        $this->getObjectManager()->persist($container);

        return $container;
    }

    /**
     *
     * @param Entity\ContainerInterface $container
     * @return Entity\HolderInterface
     */
    protected function createHolder(Entity\ContainerInterface $container)
    {
        /* @var $holder Entity\HolderInterface */
        $holder = $this->getClassResolver()->resolve('RelatedContent\Entity\HolderInterface');
        $holder->setContainer($container);
        $holder->setPosition(999);
        $this->getObjectManager()->persist($holder);
        $this->getObjectManager()->flush($holder);

        return $holder;
    }

    /**
     *
     * @param ContainerInterface $related
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    protected function aggregate(ContainerInterface $related)
    {
        $collection = new ArrayCollection();
        foreach ($related->getHolders() as $holder) {
            $specific = $holder->getSpecific();
            if ($specific instanceof Entity\InternalInterface) {
                $result = new InternalResult();
                $result->setObject($specific);
                $result->setRouter($this->getRouter());
            } elseif ($specific instanceof Entity\ExternalInterface) {
                $result = new ExternalResult();
                $result->setObject($specific);
            } elseif ($specific instanceof Entity\CategoryInterface) {
                $result = new CategoryResult();
                $result->setObject($specific);
            } else {
                throw new Exception\RuntimeException(sprintf('Could not find a result type for `%s`',
                    get_class($specific)));
            }
            $collection->add($result);
        }

        return $collection;
    }
}
