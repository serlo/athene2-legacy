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

use Language\Service\LanguageServiceInterface;
use Uuid\Entity\UuidInterface;
use Uuid\Entity\UuidHolder;

interface AliasManagerInterface
{

    /**
     *
     * @param UuidInterface $uuid            
     * @return \Alias\Entity\AliasInterface
     */
    public function findAliasByUuid(UuidInterface $uuid);

    /**
     *
     * @param string $source
     * @param LanguageServiceInterface $language            
     * @return string
     */
    public function findAliasBySource($source, LanguageServiceInterface $language);

    /**
     *
     * @param string $alias            
     * @param LanguageServiceInterface $language            
     * @return string
     */
    public function findSourceByAlias($alias, LanguageServiceInterface $language);

    /**
     *
     * @param string $source            
     * @param string $alias            
     * @param LanguageServiceInterface $language            
     * @param UuidHolder $uuid            
     * @return Entity\AliasInterface
     */
    public function createAlias($source, $alias, $aliasFallback, UuidHolder $uuid, \Language\Service\LanguageServiceInterface $language);
    
    /**
     * 
     * @param string $name
     * @param string $source
     * @param UuidHolder $object
     * @param \Language\Service\LanguageServiceInterface $language
     */
    public function autoAlias($name, $source, UuidHolder $object, \Language\Service\LanguageServiceInterface $language);
}