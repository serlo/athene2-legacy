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
namespace Entity\Plugin\Pathauto\Provider;

use Token\Provider\ProviderInterface;
use Entity\Exception;
use Taxonomy\Entity\TaxonomyTermInterface;
use Entity\Service\EntityServiceInterface;
use Token\Provider\AbstractProvider;

class TokenProvider extends AbstractProvider implements ProviderInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    protected $data = NULL;
    
    public function getTranslator(){
        return $this->getServiceLocator()->get('translator');
    }

    public function getData()
    {
        if (! is_array($this->data)) {
            $foundPlugin = false;
            $subject = NULL;
            foreach ($this->getObject()->getScopesForPlugin('taxonomy') as $taxonomy) {
                $terms = $this->getObject()
                    ->taxonomy()
                    ->getTerms();
                foreach ($terms as $term) {
                    /* @var $term TaxonomyTermInterface */
                    $subject = $this->findSubject($term);
                }
                $foundPlugin = true;
            }
            
            if (! $foundPlugin)
                throw new Exception\RuntimeException(sprintf('Could not find a taxonomy plugin.'));
            if (! $subject)
                throw new Exception\RuntimeException(sprintf('Could not find the subject. Is this resource assigned to one?'));
            
            if (! $this->getObject()->hasPlugin('repository'))
                throw new Exception\RuntimeException(sprintf('Could not find the repository plugin.'));
            
            $title = $this->getObject()
                ->plugin('repository')
                ->getHead()
                ->get('title');
            $foundPlugin = true;
            $type = $this->getObject()
                ->getEntity()
                ->getType()
                ->getName();
            
            $this->data = array(
                'subject' => $subject,
                'type' => $this->getTranslator()->translate($type),
                'title' => $title
            );
        }
        return $this->data;
    }

    private function findSubject(TaxonomyTermInterface $term)
    {
        if ($term->getTaxonomy()->getName() == 'subject') {
            return $term->getName();
        } else {
            if (! is_object($term->getParent()))
                return NULL;
            return $this->findSubject($term->getParent());
        }
    }
	/* (non-PHPdoc)
     * @see \Token\Provider\AbstractProvider::validObject()
     */
    protected function validObject ($object)
    {
        if(!$object instanceof EntityServiceInterface)
            throw new Exception\InvalidArgumentException(sprintf('Expected EntityServiceInterface but got `%s`', get_class($object)));
    }

}