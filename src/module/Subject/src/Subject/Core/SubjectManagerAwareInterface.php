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
namespace Subject\Core;

use Subject\Core\Service\SubjectServiceInterface;

interface SubjectManagerAwareInterface
{
    /**
     * Gets the SubjectManager
     * 
     * @return SubjectManagerInterface
     */
    public function getSubjectManager();
    
    /**
     * Sets the SubjectManager
     * 
     * @param SubjectManagerInterface $subject
     * @return $this
     */
    public function setSubjectManager(SubjectManagerInterface $subject);
}