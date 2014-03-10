<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ads\Entity;

use Doctrine\ORM\Mapping as ORM;
use Instance\Entity\InstanceAwareTrait;
use Page\Entity\PageRepositoryInterface;

/**
 * An AdPage for Horizon
 *
 * @ORM\Entity
 * @ORM\Table(name="ad_page")
 */
class AdPage implements AdPageInterface
{
    use InstanceAwareTrait;    
    /**
     * @ORM\ManyToOne(targetEntity="Page\Entity\PageRepository")
     */
    protected $page_repository;

    public function getPageRepository()
    {
        return $this->page_repository;
    }

    public function setPageRepository(PageRepositoryInterface $pageRepository)
    {
        $this->page_repository = $pageRepository;
    }

}
