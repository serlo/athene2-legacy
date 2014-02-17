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
namespace Migrator\Worker;

use Blog\Form\CreatePostForm;
use Blog\Manager\BlogManagerInterface;
use Doctrine\ORM\EntityManager;
use Flag\Manager\FlagManagerInterface;
use Instance\Manager\InstanceManagerInterface;
use Migrator\Converter\ConverterChain;
use Migrator\Converter\PreConverterChain;
use Taxonomy\Manager\TaxonomyManagerInterface;
use User\Manager\UserManagerInterface;
use Uuid\Manager\UuidManagerInterface;
use Versioning\RepositoryManagerInterface;

class BlogWorker implements Worker
{
    /**
     * @var EntityManager
     */
    protected $objectManager;

    /**
     * @var TaxonomyManager
     */
    protected $taxonomyManager;

    /**
     * @var UuidManagerInterface
     */
    protected $uuidManager;

    /**
     * @var ConverterChain
     */
    protected $converterChain;

    /**
     * @var \User\Manager\UserManagerInterface
     */
    protected $userManager;

    /**
     * @var FlagManagerInterface
     */
    protected $flagManager;

    /**
     * @var RepositoryManagerInterface
     */
    protected $repositoryManager;

    /**
     * @var BlogManagerInterface
     */
    protected $blogManager;

    protected $workload = [];

    public function __construct(
        EntityManager $objectManager,
        TaxonomyManagerInterface $taxonomyManager,
        InstanceManagerInterface $instanceManager,
        UuidManagerInterface $uuidManager,
        UserManagerInterface $userManagerInterface,
        PreConverterChain $converterChain,
        FlagManagerInterface $flagManager,
        RepositoryManagerInterface $repositoryManager,
        BlogManagerInterface $blogManager
    ) {
        $this->objectManager     = $objectManager;
        $this->taxonomyManager   = $taxonomyManager;
        $this->instanceManager   = $instanceManager;
        $this->uuidManager       = $uuidManager;
        $this->userManager       = $userManagerInterface;
        $this->converterChain    = $converterChain;
        $this->flagManager       = $flagManager;
        $this->repositoryManager = $repositoryManager;
        $this->blogManager       = $blogManager;
    }

    public function migrate(array & $results, array &$workload)
    {
        $i = 0;

        $user     = $this->userManager->getUserFromAuthenticator();
        $instance = $this->instanceManager->getInstance(1);
        /** @var $posts \Migrator\Entity\Blog[] */
        $posts    = $this->objectManager->getRepository('Migrator\Entity\Blog')->findBy(
            [],
            ['publish' => 'desc']
        );
        $category = $this->taxonomyManager->getTerm(8);

        $total = count($posts);
        $i     = 0;
        foreach ($posts as $post) {

            $i++;
            echo (($i / $total) * 100) . " ($i of $total)\n";

            $author = isset($results['user'][$post->getAuthor()]) ? $results['user'][$post->getAuthor()] : 1;

            $form = new CreatePostForm($this->objectManager);

            $content = $this->converterChain->convert(
                utf8_encode($post->getContent())
            );
            $title   = utf8_encode(html_entity_decode($post->getTitle()));
            $date    = new \DateTime();
            $t       = $date->getTimestamp();

            $date->setDate(date("y", $t), date("m", $t), date("d", $t));

            $form->setData(
                [
                    'blog'     => $category,
                    'author'   => $author,
                    'title'    => $title,
                    'content'  => $content,
                    'publish'  => $date,
                    'instance' => 1
                ]
            );
            if (!$form->isValid()) {
                throw new \Exception(print_r($form->getMessages(), true));
            } else {
                $newPost = $this->blogManager->createPost($form);
                $newPost->setTimestamp($post->getDate());
                $newPost->setPublish($post->getPublish());
            }
        }

        $this->objectManager->flush();

        return $results;
    }

    public function getWorkload()
    {
        return $this->workload;
    }
}
