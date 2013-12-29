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
namespace Flag\Manager;

trait FlagManagerAwareTrait
{

    /**
     *
     * @var FlagManagerInterface
     */
    protected $flagManager;

    /**
     *
     * @return FlagManagerInterface $flagManager
     */
    public function getFlagManager()
    {
        return $this->flagManager;
    }

    /**
     *
     * @param FlagManagerInterface $flagManager            
     * @return self
     */
    public function setFlagManager(FlagManagerInterface $flagManager)
    {
        $this->flagManager = $flagManager;
        return $this;
    }
}