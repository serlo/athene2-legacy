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

use Uuid\Entity\UuidInterface;
use Uuid\Entity\UuidHolder;
use Language\Entity\LanguageInterface;

interface AliasManagerInterface
{
    /**
     *
     * @param string $source
     * @param LanguageInterface $language            
     * @return string
     */
    public function findAliasBySource($source, LanguageInterface $language);

    /**
     *
     * @param string $alias            
     * @param LanguageInterface $language            
     * @return string
     */
    public function findSourceByAlias($alias, LanguageInterface $language);

    /**
     *
     * @param string $source            
     * @param string $alias            
     * @param LanguageInterface $language            
     * @param UuidInterface $uuid            
     * @return Entity\AliasInterface
     */
    public function createAlias($source, $alias, $aliasFallback, UuidInterface $uuid, LanguageInterface $language);
    
    /**
     *
     * @param string $alias
     * @param string $aliasFallback
     * @param LanguageInterface $language
     * @param UuidInterface $uuid
     * @return Entity\AliasInterface
     */
    public function editAlias( $alias, $aliasFallback, UuidInterface $uuid, LanguageInterface $language);
    
    
    /**
     * 
     * @param string $name
     * @param string $source
     * @param UuidInterface $object
     * @param LanguageInterface $language
     * @return self
     */
    public function autoAlias($name, $source, UuidInterface $object, LanguageInterface $language);
}