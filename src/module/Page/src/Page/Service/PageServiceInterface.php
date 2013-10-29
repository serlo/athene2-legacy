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
     * @param numeric $id
     * @return PageRevisionInterface
     */
    public function getRevision($id);
    /**
     *
     * @return bool
     */
    public function hasCurrentRevision();
    /**
     *
     * @param PageRevisionInterface  $revision
     * @return this
     */
    public function setCurrentRevision($revision);
    /**
     *
     * @param numeric  $id
     * @return this
     */
    public function deleteRevision($id);
}

