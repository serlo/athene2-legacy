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
namespace Subject\Manager;

use Subject\Service\SubjectServiceInterface;
use Language\Service\LanguageServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;

interface SubjectManagerInterface
{

    /**
     *
     * @param
     *            int
     * @return SubjectServiceInterface
     */
    public function getSubject($id);

    /**
     *
     * @param string $name            
     * @param LanguageServiceInterface $language            
     * @return SubjectServiceInterface
     */
    public function findSubjectByString($name, LanguageServiceInterface $language);

    /**
     *
     * @param LanguageServiceInterface $language            
     * @return ArrayCollection
     */
    public function findSubjectsByLanguage(LanguageServiceInterface $language);
}