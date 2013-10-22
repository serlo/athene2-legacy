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
     * @param PageRevisionInterface  $revision
     * @param array $array
     * @return this
     */
    public function editCurrentRevision(PageRevisionInterface $revision, array $array);
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
}

?>