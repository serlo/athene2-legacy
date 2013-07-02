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
namespace Application\Entity\LearningObject\Exercise;

use Entity\Factory\AbstractFactory;
use Entity\Service\EntityServiceInterface;

class TextExerciseFactory extends AbstractFactory {
	public function build(EntityServiceInterface $entityService) {
		$decorator = new TextExercise ();
		$decorator->setForm(new Form\TextExerciseForm());

		// $decorator->addComponent ( new LinkComponent ( $entityService ) );
		// $decorator->addComponent ( new TopicComponent ( $entityService ) );

		$factory = new \Application\Entity\Provider\Repository\Factory();
		$decorator->addComponent ( $factory->createProvider( $entityService ), 'repository' );
		
		$decorator = $this->inject ( $decorator, $entityService );
		
		$decorator->orderFields(array('content'));
		return $decorator;
	}
} 