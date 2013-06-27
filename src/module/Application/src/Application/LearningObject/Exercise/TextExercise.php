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
namespace Application\LearningObject\Exercise;

use Entity\Service\EntityServiceInterface;
use Entity\Factory\AbstractEntity;

class TextExercise extends AbstractEntity implements EntityServiceInterface, TextExerciseInterface, ExerciseInterface
{
    public function getContent ()
    {
        return $this->getRepository()->getCurrentRevision()->get('content');
    }
    
    public function getSolution ()
    {
        return $this->findChild('Solution\TextSolution');
    }
    
    public function getFormData(){
        try{
            return array(
                'id' => $this->getId(),
                'revision' => array(
                    'content' => $this->getContent(),
                )
            );            
        } catch (\Versioning\Exception\RevisionNotFoundException $e) {
            return array(
                'id' => $this->getId(),
                'revision' => array(
                    'content' => '',
                )
            );
        }
            
    }
}