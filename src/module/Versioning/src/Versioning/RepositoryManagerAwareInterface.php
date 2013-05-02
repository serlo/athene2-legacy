<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Versioning;

interface RepositoryManagerAwareInterface
{

    /**
     * Set repository manager
     *
     * @param RepositoryManagerInterface $repositoryManager            
     */
    public function setRepositoryManager (RepositoryManagerInterface $repositoryManager);

    /**
     *
     * @return RepositoryManagerInterface
     */
    public function getRepositoryManager ();
}