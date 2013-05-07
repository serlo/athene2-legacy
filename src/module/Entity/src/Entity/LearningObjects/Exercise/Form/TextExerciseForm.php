<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Exercise\Form;

use Zend\Form\Form;
use Entity\LearningObjects\Form\EditorFieldset;
use Entity\LearningObjects\Form\Revision\RevisionWithContentFieldset;

class TextExerciseForm extends Form
{

    function __construct ()
    {
        parent::__construct('textExercise');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new TextExerciseFilter());
        
        $editorFieldset = new EditorFieldset();
        
        $revisionFieldset = new RevisionWithContentFieldset();
        
        $this->add($revisionFieldset);
        $this->add($editorFieldset);
    }
}