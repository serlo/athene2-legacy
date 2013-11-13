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
namespace RelatedContent\Entity;

interface ExternalInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     *
     * @return string
     */
    public function getUrl();

    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     *
     * @param string $title            
     * @return $this
     */
    public function setTitle($title);

    /**
     *
     * @param string $url            
     * @return $this
     */
    public function setUrl($url);

    /**
     *
     * @param ContainerInterface $container            
     * @return $this
     */
    public function setContainer(ContainerInterface $container);
}