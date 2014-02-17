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
use Instance\Entity\InstanceInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Zend\Form\FormInterface;

interface BlogManagerInterface
{

    /**
     * @param int $id
     * @return TaxonomyTermInterface
     */
    public function getBlog($id);

    /**
     * @param InstanceInterface $instanceService
     * @return TaxonomyTermInterface[]
     */
    public function findAllBlogs(InstanceInterface $instanceService);

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
     * @param FormInterface $form
     * @return void
     */
    public function updatePost(FormInterface $form);

    /**
     * @param FormInterface         $form
     * @return PostInterface|false
     */
    public function createPost(FormInterface $form);
}