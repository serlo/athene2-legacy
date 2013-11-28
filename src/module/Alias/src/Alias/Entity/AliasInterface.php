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

use Language\Entity\LanguageInterface;
use Uuid\Entity\UuidInterface;

interface AliasInterface
{

    /**
     *
     * @return $this;
     */
    public function getId();

    /**
     *
     * @return $this;
     */
    public function getSource();

    /**
     *
     * @param string $source            
     * @return $this;
     */
    public function setSource($source);

    /**
     *
     * @return $this;
     */
    public function getAlias();

    /**
     *
     * @param string $alias            
     * @return $this;
     */
    public function setAlias($alias);

    /**
     *
     * @return LanguageInterface
     */
    public function getLanguage();

    /**
     *
     * @param LanguageInterface $language            
     * @return $this;
     */
    public function setLanguage(LanguageInterface $language);

    /**
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setUuid(UuidInterface $uuid);

    /**
     *
     * @return $this
     */
    public function getUuid();
}