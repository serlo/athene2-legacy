<?php
namespace Page\Manager;

use Page\Entity\PageRevisionInterface;
use Page\Entity\PageRepositoryInterface;
use Page\Service\PageServiceInterface;
use Language\Entity\LanguageEntityInterface;

interface PageManagerInterface
{
    /**
     *
     * @param numeric $id
     * @return PageRevisionInterface;
     */
    public function getRevision($id);
    /**
     *
     * @param numeric $id
     * @return PageServiceInterface;
     */
    public function getPageRepository($id);
    /**
     *
     * @param array $data
     * @param LanguageEntityInterface $language
     * @return PageServiceInterface
     */
	public function createPageRepository(array $data,$language);
	/**
	 *
	 * @param RepositoryInterface $repository
	 * @param array $data
	 * @return PageServiceInterface;
	 */
	public function createRevision(PageRepositoryInterface $repository, array $data);
}

