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
namespace Subject\Provider;

use Taxonomy\Router\ParamProviderInterface;
use Taxonomy\Service\TermServiceInterface;
use Subject\Exception;

class ParamProvider implements ParamProviderInterface
{
    use \Subject\Manager\SubjectManagerAwareTrait;
    
    /**
     * 
     * @var TermServiceInterface
     */
    protected $object;
    
    public function setObject($object){
        if(!$object instanceof TermServiceInterface)
            throw new Exception\InvalidArgumentException(sprintf('Expected `Taxonomy\Service\TermServiceInterface` but got `%s`', get_class($object)));
        $this->object = $object;
        return $this;
    }
    
	/**
     * @return TermServiceInterface $reference
     */
    public function getObject ()
    {
        return $this->object;
    }
    
    public function getParams()
    {
        return array(
            'subject' => $this->getObject()->findAncestorByTypeName('subject')->getSlug(),
            'path' => $this->getPathToTermAsUri($this->getObject())
        );
    }

    protected function getPathToTermAsUri(TermServiceInterface $term)
    {
        return substr($this->_getPathToTermAsUri($term), 0, - 1);
    }

    protected function _getPathToTermAsUri(TermServiceInterface $term)
    {
        return ($term->getTaxonomy()->getName() != 'subject') ? $this->_getPathToTermAsUri($term->getParent()) . $term->getSlug() . '/' : '';
    }
}