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
namespace Upload\Entity;

interface UploadInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return \DateTime
     */
    public function getDateTime();

    /**
     *
     * @return string
     */
    public function getLocation();

    /**
     *
     * @return int
     */
    public function getSize();

    /**
     *
     * @return string
     */
    public function getFilename();

    /**
     *
     * @return string
     */
    public function getType();

    /**
     *
     * @param string $location            
     * @return $this
     */
    public function setLocation($location);

    /**
     *
     * @param int $size            
     * @return $this
     */
    public function setSize($size);

    /**
     *
     * @param string $filename            
     * @return $this
     */
    public function setFilename($filename);

    /**
     *
     * @param string $type            
     * @return $this
     */
    public function setType($type);
}