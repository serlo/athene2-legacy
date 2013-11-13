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
namespace RelatedContent\Manager;

use RelatedContent\Entity;
use Doctrine\Common\Collections\Collection;

interface RelatedContentManagerInterface
{
    /**
     * 
     * @param int $id
     * @return Collection
     */
    public function aggregateRelatedContent($id);

    /**
     * 
     * @param int $id
     * @return Entity\ContainerInterface
     */
    public function getContainer($id);

    /**
     * 
     * @param int $container
     * @param string $title
     * @param string $url
     * @return Entity\ExternalInterface
     */
    public function addExternalRelation($container, $title, $url);
    
    /**
     * 
     * @param int $container
     * @param string $title
     * @param int $related
     * @return Entity\InternalInterface
     */
    public function addInternalRelation($container, $title, $related);

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function removeExternalRelation($id);

    /**
     * 
     * @param int $id
     * @return $this
     */
    public function removeInternalRelation($id);
}