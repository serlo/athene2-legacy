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

use Markdown\Exception\RuntimeException;
use Markdown\Service\RenderServiceInterface;
use Normalizer\NormalizerInterface;
use Search\Entity\Document;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Uuid\Filter\NotTrashedCollectionFilter;
use Zend\Mvc\Router\RouteInterface;

class TaxonomyProvider implements ProviderInterface
{
    /**
     * @param TaxonomyManagerInterface $taxonomyManager
     * @param NormalizerInterface      $normalizer
     * @param RenderServiceInterface   $renderService
     * @param RouteInterface           $router
     */
    public function __construct(
        TaxonomyManagerInterface $taxonomyManager,
        NormalizerInterface $normalizer,
        RenderServiceInterface $renderService,
        RouteInterface $router
    ) {
        $this->taxonomyManager = $taxonomyManager;
        $this->renderService   = $renderService;
        $this->normalizer      = $normalizer;
        $this->router          = $router;
    }

    public function provide()
    {
        $container = [];
        $terms     = $this->taxonomyManager->findAllTerms(true);
        $filter    = new NotTrashedCollectionFilter();
        $terms     = $filter->filter($terms);
        /* @var $term TaxonomyTermInterface */
        foreach ($terms as $term) {
            $normalized = $this->normalizer->normalize($term);
            $id         = $term->getId();
            $title      = $term->getName();
            $content    = $term->getDescription();
            $type       = $term->getType();
            $link       = $this->router->assemble(
                $normalized->getRouteParams(),
                ['name' => $normalized->getRouteName()]
            );
            $keywords   = $normalized->getMetadata()->getKeywords();
            $instance   = $term->getInstance()->getId();

            try {
                $content = $this->renderService->render($content);
            } catch (RuntimeException $e) {
                // Could not render -> nothing to do.
            }
            $result      = new Document($id, $title, $content, $type, $link, $keywords, $instance);
            $container[] = $result;
        }
        return $container;
    }
}
