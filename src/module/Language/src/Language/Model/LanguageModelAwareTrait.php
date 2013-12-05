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

trait LanguageModelAwareTrait
{

    /**
     *
     * @var LanguageModelInterface
     */
    protected $language;

    /**
     *
     * @return LanguageModelInterface $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     *
     * @param LanguageModelInterface $language            
     * @return $this
     */
    public function setLanguage(LanguageModelInterface $language)
    {
        $this->language = $language;
        return $this;
    }
}