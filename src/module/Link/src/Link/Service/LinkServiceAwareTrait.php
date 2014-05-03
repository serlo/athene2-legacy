<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Link\Service;

trait LinkServiceAwareTrait
{

    /**
     * @var LinkServiceInterface
     */
    protected $linkService;

    /**
     * @return LinkServiceInterface
     *         $linkService
     */
    public function getLinkService()
    {
        return $this->linkService;
    }

    /**
     * @param LinkServiceInterface $linkService
     * @return self
     */
    public function setLinkService(LinkServiceInterface $linkService)
    {
        $this->linkService = $linkService;

        return $this;
    }
}
