<?php

namespace Page\Permission;

 use Zend\Permissions\Rbac\AssertionInterface;
 use Zend\Permissions\Rbac\Rbac;

 class HasPermission implements AssertionInterface
 {
     protected $userId;
     protected $article;

     public function __construct($userId)
     {
         $this->userId = $userId;
     }

     public function setArticle($article)
     {
         $this->article = $article;
     }

     public function assert(Rbac $rbac)
     {
         if (!$this->article) {
             return false;
         }
         return $this->userId == $article->getUserId();
     }
 }

 // User is assigned the foo role with id 5
 // News article belongs to userId 5
 // Jazz article belongs to userId 6

 $rbac = new Rbac();
 $user = $mySessionObject->getUser();
 $news = $articleService->getArticle(5);
 $jazz = $articleService->getArticle(6);

 $rbac->addRole($user->getRole());
 $rbac->getRole($user->getRole())->addPermission('edit.article');

 $assertion = new AssertUserIdMatches($user->getId());
 $assertion->setArticle($news);

 // true always - bad!
 if ($rbac->isGranted($user->getRole(), 'edit.article')) {
     // hacks another user's article
 }

 // true for user id 5, because he belongs to write group and user id matches
 if ($rbac->isGranted($user->getRole(), 'edit.article', $assertion)) {
     // edits his own article
 }

 $assertion->setArticle($jazz);

 // false for user id 5
 if ($rbac->isGranted($user->getRole(), 'edit.article', $assertion)) {
     // can not edit another user's article
 }