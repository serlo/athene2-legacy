<?php
namespace Page\Service;

use Page\Service\PageServiceInterface;
use Page\Entity\PageRevisionInterface;

interface PageServiceInterface
{

    /**
     *
     * @return PageManagerInterface
     */
    public function getManager();

    /**
     *
     * @return PageRevisionInterface
     */
    public function getCurrentRevision();


    /**
     *
     * @return bool
     */
    public function hasCurrentRevision();

    /**
     *
     * @param PageRevisionInterface $revision            
     * @return self
     */
    public function setCurrentRevision($revision);

    /**
     *
     * @param numeric $id            
     * @return self
     */
    public function deleteRevision($id);

    /**
     *
     * @return numeric
     */
    public function getRepositoryId();
}

