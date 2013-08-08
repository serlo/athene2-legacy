<?php
namespace Link;

use Link\Entity\LinkEntityInterface;
use Link\Service\LinkServiceInterface;

interface LinkManagerInterface {
	
	/**
	 * returns an LinkServiceInterface compatible instance
	 * 
	 * @param int $id
	 * @return LinkServiceInterface
	 */
	public function get($id);	
	
	/**
	 * Adds an LinkServiceInterface compatible instance
	 * 
	 * @param LinkServiceInterface $linkService
	 * @return $this
	 */
	public function add(LinkEntityInterface $linkService);
	
	/**
	 * Checks if the LinkService is already registered
	 *
	 * @param string $name
	 * @return bool
	 */
	public function has($name);
}