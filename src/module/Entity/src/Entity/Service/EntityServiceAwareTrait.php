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
namespace Entity\Service;

trait EntityServiceAwareTrait
{

    /**
     *
     * @var EntityServiceInterface
     */
    protected $entityService;

    /**
     *
     * @return EntityServiceInterface $entityService
     */
    public function getEntityService()
    {
        return $this->entityService;
    }

    /**
     *
     * @param EntityServiceInterface $entityService            
     * @return $this
     */
    public function setEntityService(EntityServiceInterface $entityService)
    {
        $this->entityService = $entityService;
        return $this;
    }
    
    /**
     * 
     * @return boolean
     */
    public function hasEntityService(){
        return is_object($this->getEntityService());
    }
}