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
namespace Alias;

use Common\Traits;
use Alias\Exception;

class AliasManager implements AliasManagerInterface
{
    use Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait;
    
    /*
     * (non-PHPdoc) @see \Alias\AliasManagerInterface::findSourceByAlias()
     */
    public function findSourceByAlias($alias, \Language\Service\LanguageServiceInterface $language)
    {
        if (! is_string($alias))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($alias)));
        
        /* @var $entity Entity\AliasInterface */
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Alias\Entity\AliasInterface'))
            ->findOneBy(array(
            'alias' => $alias
        ));
            
        if (! is_object($entity))
            throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $alias));
        
        return $entity->getSource();
    }
    
    /*
     * (non-PHPdoc) @see \Alias\AliasManagerInterface::createAlias()
     */
    public function createAlias($source, $alias)
    {
        if (! is_string($alias))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($alias)));
        
        if (! is_string($source))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($source)));
    }
}