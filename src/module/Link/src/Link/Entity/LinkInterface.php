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
namespace Link\Entity;

use Type\Entity\TypeAwareInterface;

interface LinkInterface extends TypeAwareInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return int
     */
    public function getPosition();

    /**
     *
     * @return LinkableInterface
     */
    public function getChild();

    /**
     *
     * @return LinkableInterface
     */
    public function getParent();

    /**
     *
     * @param int $position            
     * @return self
     */
    public function setPosition($position);

    /**
     *
     * @param LinkableInterface $child            
     * @return self
     */
    public function setChild(LinkableInterface $child);

    /**
     *
     * @param LinkableInterface $parent            
     * @return self
     */
    public function setParent(LinkableInterface $parent);
}