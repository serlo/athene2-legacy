<?php
namespace Page\Manager;

use Page\Entity\PageRevisionInterface;
use Page\Entity\PageRepositoryInterface;
use Page\Service\PageServiceInterface;
use Language\Entity\LanguageInterface;

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
     * @param string $string 
     * @param numeric $language_id
     * @return PageServiceInterface
     */
    public function findPageRepositoryBySlug($string,$language_id);
    /**
     *
     * @param array $data
     * @param LanguageInterface $language
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

