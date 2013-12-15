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
namespace Language\Model;

interface LanguageModelInterface
{

    /**
     * Returns the code.
     * echo $language->getCode(); // prints: 'de'
     *
     * @return string $code
     */
    public function getCode();

    /**
     * Sets the code
     *
     * @param field_type $code            
     * @return $this
     */
    public function setCode($code);

    /**
     * Gets the id
     *
     * @return int $id
     */
    public function getId();

    /**
     *
     * @return bool
     */
    public function getEntity();
}