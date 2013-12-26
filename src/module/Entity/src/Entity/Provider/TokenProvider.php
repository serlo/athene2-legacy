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
namespace Entity\Provider;

use Token\Provider\ProviderInterface;
use Token\Provider\AbstractProvider;
use Entity\Entity\EntityInterface;

class TokenProvider extends AbstractProvider implements ProviderInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    protected $data = NULL;

    public function getTranslator()
    {
        return $this->getServiceLocator()->get('translator');
    }

    public function getData()
    {
        if (! is_array($this->data)) {
            $path = $this->getObject()->getId();
            
            /* @var $term \Taxonomy\Entity\TaxonomyTermInterface */
            foreach ($this->getObject()->getTaxonomyTerms() as $term) {
                $path = $term->slugify('root');
                break;
            }
            
            $title = $this->getObject()
                ->getHead()
                ->get('title');
            
            $type = $this->getObject()
                ->getType()
                ->getName();
            
            $this->data = array(
                'path' => $path,
                'type' => $this->getTranslator()->translate($type),
                'title' => $title,
                'id' => $this->getObject()->getId(),
            );
        }
        
        return $this->data;
    }

    protected function validObject($object)
    {
        $this->isValid($object);
    }

    protected function isValid(EntityInterface $object)
    {}
}