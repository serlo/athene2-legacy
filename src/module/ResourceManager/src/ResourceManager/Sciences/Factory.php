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
namespace Application\Subject\Sciences;

use Subject\Factory\AbstractFactory;
use Subject\Service\SubjectServiceInterface;
use Application\Subject\Provider\Topic\TopicProvider;

class Factory extends AbstractFactory {
	/**
	 *
	 * @param SubjectServiceInterface $subjectService        	
	 * @throws \InvalidArgumentException
	 * @return SubjectServiceInterface;
	 */
	public function build(SubjectServiceInterface $subjectService) {
		$decorator = new Decorator ();
		$decorator->addComponent ( new TopicProvider ( $subjectService ), 'topic' );
		return $this->inject ( $decorator, $subjectService );
	}
}