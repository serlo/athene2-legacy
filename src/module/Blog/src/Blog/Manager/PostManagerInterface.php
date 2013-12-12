<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Blog\Manager;

use Doctrine\Common\Collections\Collection;
use Blog\Service\PostServiceInterface;
use User\Model\UserModelInterface;
use DateTime;

interface PostManagerInterface
{

    /**
     *
     * @return Collection PostServiceInterface[]
     */
    public function findAllPosts();

    /**
     *
     * @return PostServiceInterface
     */
    public function getPost($id);

    /**
     *
     * @param UserModelInterface $author            
     * @param string $title            
     * @param string $content            
     * @param string $publish            
     * @return PostServiceInterface
     */
    public function createPost(UserModelInterface $author, $title, $content, DateTime $publish = NULL);
}