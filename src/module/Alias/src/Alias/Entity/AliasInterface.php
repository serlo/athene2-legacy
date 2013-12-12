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
namespace Alias\Entity;

use Uuid\Entity\UuidInterface;
use Language\Model\LanguageModelAwareInterface;

interface AliasInterface extends LanguageModelAwareInterface
{

    /**
     * Returns the ID
     *
     * @return int
     */
    public function getId();

    /**
     * Returns the source
     *
     * @return string
     */
    public function getSource();

    /**
     * Returns the alias
     *
     * @return string
     */
    public function getAlias();
    
    /**
     * Gets the object
     *
     * @return UuidInterface
     */
    public function getObject();

    /**
     * Sets the source
     *
     * @param string $source      
     * @return self
     */
    public function setSource($source);

    /**
     * Sets the alias
     *
     * @param string $alias            
     * @return self
     */
    public function setAlias($alias);
    
    /**
     * Sets the object
     *
     * @param UuidInterface $uuid            
     * @return self
     */
    public function setObject(UuidInterface $uuid);

}