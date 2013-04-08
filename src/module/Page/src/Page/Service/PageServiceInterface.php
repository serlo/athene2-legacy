<?php
namespace Page\Service;

use Core\Service\LanguageService;
use Page\Entity\Page;

interface PageServiceInterface
{
    public function checkoutRevision($id, $rid, LanguageService $ls = NULL);
    public function get($field);
    public function set($field, $value);
    public function prepareRevision($id, $rid = NULL);
    public function addRevision ($id, array $data);
    public function removeRevision($pageId, $revisionId);

    /**
     * @return Page
     */
    public function create(array $data, LanguageService $ls = NULL);
}