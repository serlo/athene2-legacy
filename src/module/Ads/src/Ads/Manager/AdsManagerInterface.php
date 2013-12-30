<?php
namespace Ads\Manager;

use Language\Entity\LanguageInterface;

interface AdsManagerInterface
{
    /**
     *
     * @param numeric $id
     * @return PageRevisionInterface;
     
    public function getRevision($id);
    /**
     *
     * @param numeric $id
     * @return PageRepositoryInterface;
     
    public function getPageRepository($id);
    /**
     *
     * @param array $data
     * @param LanguageInterface $language
     * @return PageRepositoryInterface;
     
	public function createPageRepository(array $data,$language);
	/**
	 *
	 * @param RepositoryInterface $repository
	 * @param array $data
     * @return PageRepositoryInterface;
	 
	public function createRevision(PageRepositoryInterface $repository, array $data,UserInterface $user);

*/
}

