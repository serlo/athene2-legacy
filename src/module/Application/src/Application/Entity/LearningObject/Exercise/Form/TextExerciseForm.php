<?php

/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Application\Entity\LearningObject\Exercise\Form;

use Zend\Form\Form;
use Entity\Form\Fieldset;
use Application\Entity\Provider\Repository\Form\RevisionFieldset;

class TextExerciseForm extends Form {
	function __construct() {
		parent::__construct ( 'text-exercise' );
		$this->setAttribute ( 'method', 'post' );
		
		$editor = new Fieldset ();
		
		$revision = new RevisionFieldset ();
		$revision->add ( array (
				'name' => 'content',
				'type' => 'Zend\Form\Element\Textarea',
				'attributes' => array (
						'class' => 'ckeditor' 
				) 
		) );
		$revision->setInputFilter ( array (
				'content' => array (
						'required' => true 
				) 
		) );
		
		$this->add ( $revision );
		$this->add ( $editor );
	}
}