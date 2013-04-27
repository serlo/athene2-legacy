<?php

namespace Taxonomy\Service;

use Taxonomy\TermManagerInterface;
use Core\Entity\ModelInterface;
use Core\Entity\EntityAdapterInterface;

interface TermServiceInterface extends ModelInterface, EntityAdapterInterface {
	public function setTermManager(TermManagerInterface $termManager);
	public function getTermManager();
}