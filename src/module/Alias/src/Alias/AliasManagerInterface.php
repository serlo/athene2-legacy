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
use Language\Model\LanguageModelInterface;

interface AliasManagerInterface
{
    /**
     *
     * @param string $source
     * @param LanguageModelInterface $language            
     * @return string
     */
    public function findAliasBySource($source, LanguageModelInterface $language);

    /**
     *
     * @param string $alias            
     * @param LanguageModelInterface $language            
     * @return string
     */
    public function findSourceByAlias($alias, LanguageModelInterface $language);

    /**
     *
     * @param string $source            
     * @param string $alias            
     * @param LanguageModelInterface $language            
     * @param UuidInterface $uuid            
     * @return Entity\AliasInterface
     */
    public function createAlias($source, $alias, $aliasFallback, UuidInterface $uuid, LanguageModelInterface $language);
    
    /**
     * 
     * @param string $name
     * @param string $source
     * @param UuidInterface $object
     * @param LanguageModelInterface $language
     * @return self
     */
    public function autoAlias($name, $source, UuidInterface $object, LanguageModelInterface $language);
}