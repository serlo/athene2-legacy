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
namespace RelatedContent\Entity;

use Uuid\Entity\UuidInterface;

interface InternalInterface extends TypeInterface
{
    /**
     *
     * @return UuidInterface
     */
    public function getReference();
    
    /**
     *
     * @return string
     */
    public function getTitle();
    
    /**
     * 
     * @param string $title
     * @return self
     */
    public function setTitle($title);
    
    /**
     * 
     * @param UuidInterface $uuid
     * @return self
     */
    public function setReference(UuidInterface $uuid);
}