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
namespace Application\Entity\Provider\Repository;

use Entity\Provider\ProviderFactory;

class Factory implements ProviderFactory {
	/* (non-PHPdoc)
	 * @see \Entity\Provider\ProviderFactory::createProvider()
	 */
	public function createProvider(\Entity\Service\EntityServiceInterface $entityService) {
		$provider = new Provider($entityService);
		$provider->setIdentity('repository');
		return $provider;
	}
}