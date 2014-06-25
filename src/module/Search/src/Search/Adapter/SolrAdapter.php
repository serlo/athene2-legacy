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
namespace Search\Adapter;

use Normalizer\NormalizerInterface;
use Search\Result;
use Solarium\Client;
use Uuid\Manager\UuidManagerInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mvc\Router\RouteInterface;

class SolrAdapter implements AdapterInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var RouteInterface
     */
    protected $router;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @var UuidManagerInterface
     */
    protected $uuidManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param Client               $client
     * @param NormalizerInterface  $normalizer
     * @param RouteInterface       $router
     * @param TranslatorInterface  $translator
     * @param UuidManagerInterface $uuidManager
     */
    public function __construct(
        Client $client,
        NormalizerInterface $normalizer,
        RouteInterface $router,
        TranslatorInterface $translator,
        UuidManagerInterface $uuidManager
    ) {
        $this->client      = $client;
        $this->normalizer  = $normalizer;
        $this->router      = $router;
        $this->uuidManager = $uuidManager;
        $this->translator  = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function search($query, $limit)
    {
        $container  = new Result\Container();
        $queryClass = $this->client->createSelect();
        $types      = ['article', 'topic', 'course', 'video'];
        $types      = implode(' OR ', $types);
        $queryClass->createFilterQuery('typeFilter')->setQuery('content_type:(' . $types . ')');
        $queryClass->setQuery($query);
        $queryClass->setRows($limit);
        $queryClass->addSort('score', $queryClass::SORT_ASC);
        $queryClass->setQueryDefaultOperator($queryClass::QUERY_OPERATOR_OR);
        $resultSet = $this->client->select($queryClass);

        foreach ($resultSet as $document) {
            $id         = $document['id'];
            $object     = $this->uuidManager->getUuid($id);
            $normalized = $this->normalizer->normalize($object);
            $title      = $normalized->getTitle();
            $content    = $normalized->getContent();
            $type       = $this->translator->translate($normalized->getType());
            $link       = $this->router->assemble(
                $normalized->getRouteParams(),
                ['name' => $normalized->getRouteName()]
            );
            $keywords   = $normalized->getMetadata()->getKeywords();
            $item       = new Result\Result($id, $title, $content, $type, $link, $keywords);
            $container->addResult($item);
        }

        return $container;
    }
}
