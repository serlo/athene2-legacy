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
namespace Blog\Manager;

use Blog\Entity\PostInterface;
use ClassResolver\ClassResolverAwareTrait;
use DateTime;
use Language\Entity\LanguageInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use User\Entity\UserInterface;

interface BlogManagerInterface
{

    /**
     * @param int $id
     * @return TaxonomyTermInterface
     */
    public function getBlog($id);

    /**
     * @param LanguageInterface $languageService
     * @return TaxonomyTermInterface[]
     */
    public function findAllBlogs(LanguageInterface $languageService);

    /**
     * Make changes persistent
     *
     * @return self
     */
    public function flush();

    /**
     * @param int $id
     * @return PostInterface
     */
    public function getPost($id);

    /**
     * @param int      $id
     * @param string   $title
     * @param string   $content
     * @param DateTime $publish
     * @return self
     */
    public function updatePost($id, $title, $content, DateTime $publish = null);

    /**
     * @param int      $id
     * @param string   $title
     * @param string   $content
     * @param DateTime $publish
     * @return PostInterface
     */
    public function createPost(
        TaxonomyTermInterface $taxonomy,
        UserInterface $author,
        $title,
        $content,
        DateTime $publish = null
    );
}