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
namespace LearningResource\Plugin\Pathauto\Provider;

use Token\Provider\ProviderInterface;
use Entity\Exception;
use Taxonomy\Service\TermServiceInterface;
use Entity\Service\EntityServiceInterface;

class TokenProvider implements ProviderInterface
{

    protected $data = NULL;

    /**
     *
     * @var EntityServiceInterface
     */
    protected $entityService;

    /**
     *
     * @return EntityServiceInterface $entityService
     */
    public function getEntityService()
    {
        return $this->entityService;
    }

    /**
     *
     * @param EntityServiceInterface $entityService            
     * @return $this
     */
    public function setEntityService(EntityServiceInterface $entityService)
    {
        $this->entityService = $entityService;
        $this->data = NULL;
        return $this;
    }

    public function getData()
    {
        if (! is_array($this->data)) {
            $foundPlugin = false;
            $subject = NULL;
            foreach ($this->getEntityService()->getScopesForPlugin('taxonomy') as $taxonomy) {
                $terms = $this->getEntityService()
                    ->taxonomy()
                    ->getTerms();
                foreach ($terms as $term) {
                    /* @var $term TermServiceInterface */
                    $subject = $this->findSubject($term);
                }
                $foundPlugin = true;
            }
            
            if (! $foundPlugin)
                throw new Exception\RuntimeException(sprintf('Could not find a taxonomy plugin.'));
            if (! $subject)
                throw new Exception\RuntimeException(sprintf('Could not find the subject. Is this resource assigned to one?'));
            
            
            if (!$this->getEntityService()->hasPlugin('repository'))
                throw new Exception\RuntimeException(sprintf('Could not find the repository plugin.'));
            
            $title = $this->getEntityService()
                ->plugin('repository')
                ->getHead()
                ->get('title');
            $foundPlugin = true;
                
            $this->data = array(
                'subject' => $subject,
                'type' => $this->getEntityService()
                    ->getEntity()
                    ->getType()
                    ->getName(),
                'title' => $title
            );
        }
        return $this->data;
    }

    private function findSubject(TermServiceInterface $term)
    {
        if ($term->getTaxonomy()->getName() == 'subject') {
            return $term->getName();
        } else {
            if (! is_object($term->getParent()))
                return NULL;
            return $this->findSubject($term->getParent());
        }
    }
}