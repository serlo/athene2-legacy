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
namespace Taxonomy\Manager;

use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Traits\FlushableTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Persistence\ObjectManager;
use Instance\Entity\InstanceInterface;
use Taxonomy\Entity\TaxonomyInterface;
use Taxonomy\Entity\TaxonomyTermAwareInterface;
use Taxonomy\Exception;
use Taxonomy\Hydrator\TaxonomyTermHydrator;
use Taxonomy\Options\ModuleOptions;
use Type\TypeManagerAwareTrait;
use Type\TypeManagerInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Form\FormInterface;

class TaxonomyManager implements TaxonomyManagerInterface
{
    use ClassResolverAwareTrait, ObjectManagerAwareTrait;
    use TypeManagerAwareTrait, EventManagerAwareTrait;
    use FlushableTrait;

    /**
     * @var TaxonomyTermHydrator
     */
    protected $hydrator;
    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    public function __construct(
        ClassResolverInterface $classResolver,
        ModuleOptions $moduleOptions,
        ObjectManager $objectManager,
        TypeManagerInterface $typeManager
    ) {
        $this->classResolver = $classResolver;
        $this->moduleOptions = $moduleOptions;
        $this->objectManager = $objectManager;
        $this->typeManager   = $typeManager;
    }

    public function getTerm($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyTermInterface');
        $entity    = $this->getObjectManager()->find($className, $id);

        if (!is_object($entity)) {
            throw new Exception\TermNotFoundException(sprintf('Term with id %s not found', $id));
        }

        return $entity;
    }

    public function getTaxonomy($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyInterface');
        $entity    = $this->getObjectManager()->find($className, $id);

        if (!is_object($entity)) {
            throw new Exception\RuntimeException(sprintf('Term with id %s not found', $id));
        }

        return $entity;
    }

    public function findTaxonomyByName($name, InstanceInterface $instance)
    {
        $className = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyInterface');

        $type = $this->getTypeManager()->findTypeByName($name);

        $entity = $this->getObjectManager()->getRepository($className)->findOneBy(
            [
                'type'     => $type->getId(),
                'instance' => $instance->getId()
            ]
        );

        if (!is_object($entity)) {
            /* @var $entity \Taxonomy\Entity\TaxonomyInterface */
            $entity = $this->getClassResolver()->resolve('Taxonomy\Entity\TaxonomyInterface');
            $entity->setInstance($instance);
            $entity->setType($type);

            if ($this->getObjectManager()->isOpen()) {
                // todo: use entitymanager
                $this->getObjectManager()->persist($entity);
                $this->getObjectManager()->flush($entity);
            }
        }

        return $entity;
    }

    public function findTerm(TaxonomyInterface $taxonomy, array $ancestors)
    {
        if (!count($ancestors)) {
            throw new Exception\RuntimeException('Ancestors are empty');
        }

        $terms          = $taxonomy->getChildren();
        $ancestorsFound = 0;
        $found          = false;
        foreach ($ancestors as &$element) {
            if (is_string($element) && strlen($element) > 0) {
                $element = strtolower($element);
                foreach ($terms as $term) {
                    $found = false;
                    if (strtolower($term->getSlug()) == strtolower($element)) {
                        $terms = $term->getChildren();
                        $found = $term;
                        $ancestorsFound++;
                        break;
                    }
                }
                if (!is_object($found)) {
                    break;
                }
            }
        }

        if (!is_object($found)) {
            throw new Exception\TermNotFoundException(sprintf(
                'Could not find term with acestors: %s',
                implode(',', $ancestors)
            ));
        }

        if ($ancestorsFound != count($ancestors)) {
            throw new Exception\TermNotFoundException(sprintf(
                'Could not find term with acestors: %s. Ancestor ratio %s:%s does not equal 1:1',
                implode(',', $ancestors),
                $ancestorsFound,
                count($ancestors)
            ));
        }

        return $found;
    }

    public function createTerm(FormInterface $form)
    {
        $term = $this->getClassResolver()->resolve('Taxonomy\Entity\TaxonomyTermInterface');
        $data = $form->getData();

        $form->bind($term);
        $form->setData($data);

        if ($form->isValid()) {
            $term = $form->getObject();

            $this->getEventManager()->trigger('create', $this, ['term' => $term]);
            $this->getObjectManager()->persist($term);

            return $term;
        } else {
            throw new Exception\RuntimeException(sprintf('Validation failed'));
        }
    }

    public function updateTerm(FormInterface $form)
    {
        if ($form->isValid()) {
            $term = $form->getObject();

            $this->getEventManager()->trigger('update', $this, ['term' => $term]);
            $this->getObjectManager()->persist($term);
        } else {
            throw new Exception\RuntimeException(sprintf('Validation failed'));
        }

        return $term;
    }

    public function associateWith($id, $association, TaxonomyTermAwareInterface $object, $position = null)
    {
        $term = $this->getTerm($id);

        $taxonomy = $term->getTaxonomy();

        if (!$this->getModuleOptions()->getType($taxonomy->getName())->isAssociationAllowed($association)) {
            throw new Exception\RuntimeException(sprintf(
                'Taxonomy "%s" does not allow associations "%s"',
                $taxonomy->getName(),
                $association
            ));
        }

        $term->associateObject($association, $object);

        if ($position !== null) {
            $term->positionAssociatedObject($association, $object, (int)$position);
        }

        $this->getEventManager()->trigger(
            'associate',
            $this,
            array(
                'object' => $object,
                'term'   => $term
            )
        );

        $this->getObjectManager()->persist($term);

        return $this;
    }

    public function removeAssociation($id, $association, TaxonomyTermAwareInterface $object)
    {
        $term = $this->getTerm($id);

        $term->removeAssociation($association, $object);

        $this->getEventManager()->trigger(
            'dissociate',
            $this,
            array(
                'object' => $object,
                'term'   => $term
            )
        );

        $this->getObjectManager()->persist($term);

        return $this;
    }

    /**
     * @return ModuleOptions $moduleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     * @param ModuleOptions $moduleOptions
     * @return self
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;

        return $this;
    }
}
