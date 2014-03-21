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
use Page\Entity\PageRepositoryInterface;
use Instance\Entity\InstanceInterface;

/**
 * An AdPage for Horizon
 *
 * @ORM\Entity
 * @ORM\Table(name="ad_page")
 */
class AdPage implements AdPageInterface
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Instance\Entity\Instance")
     * @ORM\JoinColumn(name="instance_id", referencedColumnName="id")
     * 
     * @var InstanceInterface
     */
    protected $instance_id;

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Page\Entity\PageRepository")
     * @ORM\JoinColumn(name="page_repository_id", referencedColumnName="id")
     * 
     * @var PageRepositoryInterface
     */
    protected $page_repository_id;

    /**
     *
     * @return InstanceInterface
     */
    public function getInstance()
    {
        return $this->instance_id;
    }

    /**
     *
     * @param InstanceInterface $instance            
     */
    public function setInstance(InstanceInterface $instance)
    {
        $this->instance_id = $instance;
    }

    public function getPageRepository()
    {
        return $this->page_repository_id;
    }

    public function setPageRepository(PageRepositoryInterface $pageRepository)
    {
        $this->page_repository_id = $pageRepository;
    }
}
