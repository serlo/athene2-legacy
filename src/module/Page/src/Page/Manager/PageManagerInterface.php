<?php
namespace Page\Manager;

use Instance\Entity\InstanceInterface;
use Page\Entity\PageRepositoryInterface;
use Page\Entity\PageRevisionInterface;
use User\Entity\UserInterface;

interface PageManagerInterface
{
    /**
     * @param numeric $id
     * @return PageRevisionInterface;
     */
    public function getRevision($id);

    /**
     * @param numeric $id
     * @return PageRepositoryInterface;
     */
    public function getPageRepository($id);

    /**
     * @param array             $data
     * @param InstanceInterface $instance
     * @return PageRepositoryInterface;
     */
    public function createPageRepository(array $data, InstanceInterface $instance);

    /**
     * @param RepositoryInterface $repository
     * @param array               $data
     * @return PageRepositoryInterface;
     */
    public function createRevision(PageRepositoryInterface $repository, array $data, UserInterface $user);
}

