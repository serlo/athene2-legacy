<?php
namespace Page\Manager;

use Page\Entity\PageRepositoryInterface;
use Page\Entity\PageRevisionInterface;
use User\Entity\UserInterface;
use Zend\Form\FormInterface;

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
     * @param FormInterface $form
     * @return PageRepositoryInterface
     */
    public function createPageRepository(FormInterface $form);

    /**
     * @param RepositoryInterface $repository
     * @param array               $data
     * @return PageRepositoryInterface;
     */
    public function createRevision(PageRepositoryInterface $repository, array $data, UserInterface $user);
}

