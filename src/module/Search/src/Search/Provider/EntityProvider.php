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
namespace Search\Provider;

use Doctrine\Common\Collections\Collection;
use Entity\Entity\EntityInterface;
use Entity\Manager\EntityManagerInterface;
use Entity\Options\ModuleOptions;
use Entity\Options\SearchOptions;
use Markdown\Exception\RuntimeException;
use Markdown\Service\RenderServiceInterface;
use Normalizer\NormalizerInterface;
use Search\Entity\Document;
use Uuid\Filter\NotTrashedCollectionFilter;
use Versioning\Filter\HasCurrentRevisionCollectionFilter;
use Zend\Filter\FilterChain;
use Zend\Filter\StripTags;
use Zend\Mvc\Router\RouteInterface;

class EntityProvider implements ProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var RenderServiceInterface
     */
    protected $renderService;

    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @var RouteInterface
     */
    protected $router;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ModuleOptions          $options
     * @param NormalizerInterface    $normalizer
     * @param RenderServiceInterface $renderService
     * @param RouteInterface         $router
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ModuleOptions $options,
        NormalizerInterface $normalizer,
        RenderServiceInterface $renderService,
        RouteInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->renderService = $renderService;
        $this->options       = $options;
        $this->normalizer    = $normalizer;
        $this->router        = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function provide()
    {
        $notTrashed = new NotTrashedCollectionFilter();
        $hasCurrent = new HasCurrentRevisionCollectionFilter();
        $chain      = new FilterChain();
        $chain->attach($notTrashed);
        $chain->attach($hasCurrent);
        $container = [];
        $types     = $this->options->getTypes();

        foreach ($types as $type) {
            if ($type->hasComponent('search')) {
                /* @var $component SearchOptions */
                $component = $type->getComponent('search');
                if ($component->getEnabled()) {
                    $name     = $type->getName();
                    $entities = $this->entityManager->findEntitiesByTypeName($name, true);
                    $entities = $chain->filter($entities);
                    $this->addEntitiesToContainer($entities, $container);
                }
            }
        }

        return $container;
    }

    protected function addEntitiesToContainer(
        Collection $collection,
        array &$container
    ) {
        $stripTags = new StripTags();
        /* @var $entity EntityInterface */
        foreach ($collection as $entity) {
            $normalized = $this->normalizer->normalize($entity);
            $id         = $entity->getId();
            $instance   = $entity->getInstance()->getId();
            $title      = $normalized->getTitle();
            $content    = $normalized->getContent();
            $keywords   = $normalized->getMetadata()->getKeywords();
            $type       = $entity->getType()->getName();
            $link       = $this->router->assemble(
                $normalized->getRouteParams(),
                ['name' => $normalized->getRouteName()]
            );

            try {
                $content = $this->renderService->render($content);
            } catch (RuntimeException $e) {
                // Could not render -> nothing to do.
            }

            $content = $stripTags->filter($content);

            $result      = new Document($id, $title, $content, $type, $link, $keywords, $instance);
            $container[] = $result;
        }
    }
}
