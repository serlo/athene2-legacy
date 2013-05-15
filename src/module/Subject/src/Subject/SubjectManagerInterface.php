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
namespace Subject;

use Subject\Service\SubjectServiceInterface;

interface SubjectManagerInterface
{

    /**
     *
     * @param int|string
     * @return SubjectServiceInterface
     */
    public function get($id);

    /**
     *
     * @param SubjectServiceInterface $subject            
     * @return $his
     */
    public function add(SubjectServiceInterface $subject);

    /**
     *
     * @param
     *            int|string|SubjectServiceInterface
     * @return SubjectServiceInterface
     */
    public function has($id);
    
    /**
     * @return array
     */
    public function getAllSubjects();
}