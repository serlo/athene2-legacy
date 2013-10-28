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

use Blog\Exception;
use Taxonomy\Service\TermServiceInterface;

class BlogManager extends InstanceManager implements BlogManagerInterface
{
    use\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    public function getBlog($id)
    {
        if (! is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected int but got `%s`.', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $category = $this->getSharedTaxonomyManager()->getTerm($id);
            $service = $this->createService($category);
            $this->addInstance($id, $service);
        }
        
        return $this->getInstance($id);
    }

    public function findBlogByCategory($name,\Language\Service\LanguageServiceInterface $language)
    {
        // todo
    }

    /**
     *
     * @param TermServiceInterface $category            
     * @return PostManagerInterface
     */
    protected function createService(TermServiceInterface $category)
    {
        /* @var $postManager PostManagerInterface */
        $postManager = parent::createInstance('Blog\Manager\PostManagerInterface');
        $postManager->setTermService($category);
        $postManager->setBlogManager($this);
        return $postManager;
    }
}