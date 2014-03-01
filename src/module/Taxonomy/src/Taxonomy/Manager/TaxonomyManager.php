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
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Exception\RuntimeException;
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
        $type      = $this->getTypeManager()->findTypeByName($name);
        $criteria  = ['type' => $type->getId(), 'instance' => $instance->getId()];
        $entity    = $this->getObjectManager()->getRepository($className)->findOneBy($criteria);

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
        $this->bind($term, $form);
        $this->getEventManager()->trigger('create', $this, ['term' => $term]);

        return $term;
    }

    public function updateTerm(FormInterface $form)
    {
        $term = $this->bind($form->getObject(), $form);
        $this->getEventManager()->trigger('update', $this, ['term' => $term]);

        return $term;
    }

    public function associateWith($term, TaxonomyTermAwareInterface $object, $position = null)
    {
        if (!$term instanceof TaxonomyTermInterface) {
            $term = $this->getTerm($term);
        }

        $taxonomy = $term->getTaxonomy();

        if (!$this->getModuleOptions()->getType($taxonomy->getName())->isAssociationAllowed($object)) {
            throw new Exception\RuntimeException(sprintf(
                'Taxonomy "%s" can\'t be associated with "%s"',
                $taxonomy->getName(),
                get_class($object)
            ));
        }

        $term->associateObject($object);
        if ($position !== null) {
            $term->positionAssociatedObject($object, (int)$position);
        }
        $this->getEventManager()->trigger('associate', $this, ['object' => $object, 'term' => $term]);
        $this->getObjectManager()->persist($term);
    }

    public function removeAssociation($id, TaxonomyTermAwareInterface $object)
    {
        $term = $this->getTerm($id);
        $term->removeAssociation($object);
        $this->getEventManager()->trigger('dissociate', $this, ['object' => $object, 'term' => $term]);
        $this->getObjectManager()->persist($term);
    }

    /**
     * @param TaxonomyTermInterface $object
     * @param FormInterface         $form
     * @return TaxonomyTermInterface
     * @throws \Taxonomy\Exception\RuntimeException
     */
    protected function bind(TaxonomyTermInterface $object, FormInterface $form)
    {
        if (!$form->isValid()) {
            throw new RuntimeException(print_r($form->getMessages(), true));
        }
        $processingForm = clone $form;
        $data           = $form->getData(FormInterface::VALUES_AS_ARRAY);
        $processingForm->bind($object);
        $processingForm->setData($data);
        $processingForm->isValid();
        $this->objectManager->persist($object);
        return $object;
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
    }
}
