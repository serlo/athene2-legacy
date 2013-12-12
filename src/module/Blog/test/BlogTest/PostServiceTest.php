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
namespace BlogTest;

use AtheneTest\TestCase\Model;
use Blog\Service\PostService;
use Blog\Entity\Post;
use DateTime;
use User\Entity\User;
use Taxonomy\Entity\TaxonomyTerm;

class PostServiceTest extends Model
{

    public function setUp()
    {
        $service = new PostService();
        $entity = new Post();
        
        $service->setEntity($entity);
        $this->setObject($service);
    }

    protected function getData()
    {
        return [
            'title' => 'foo',
            'content' => 'bar',
            'author' => new User(),
            'date' => new DateTime('now'),
            'timestamp' => new DateTime('now'),
            'category' => new TaxonomyTerm()
        ];
    }
}
