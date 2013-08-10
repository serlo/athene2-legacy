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
namespace Language\Manager;

use Core\Service\LanguageManager;

trait LanguageManagerAwareTrait
{
    /**
     * 
     * @var LanguageManager
     */
    protected $languageManager;
    
	/**
     * @return \Core\Service\LanguageManager $languageManager
     */
    public function getLanguageManager ()
    {
        return $this->languageManager;
    }

	/**
     * @param \Core\Service\LanguageManager $languageManager
     * @return $this
     */
    public function setLanguageManager (LanguageManager $languageManager)
    {
        $this->languageManager = $languageManager;
        return $this;
    }

}