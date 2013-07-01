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
namespace Application\Subject\Provider\Topic\Controller;

use Subject\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Application\Entity\LearningObject\Exercise\TextExercise;
// use Application\Entity\LearningObject\Exercise\TextExercise;
class TopicController extends AbstractController {
	public function indexAction() {
		$subjectService = $this->getSubject ();
		
		$topic = $subjectService->getTopic ( explode ( '/', $this->getParam ( 'path' ) ) );
		
		$entities = array ();
		if ($topic->linkAllowed ( 'entities' )) {
			foreach ( $topic->getEntities () as $entity ) {
				if (! $entity->isTrashed ()) {
					$entities [] = $entity;
				}
			}
		}
		
		$view = new ViewModel ( array (
				'topic' => $topic,
				'subject' => $subjectService 
		) );
		
		$taxonomyView = new ViewModel ( array (
				'term' => $topic 
		) );
		$taxonomy = array ();
		$taxonomyView->setTemplate ( 'subject/provider/topic/topic' );
		foreach ( $topic->getChildren () as $child ) {
			$taxView = new ViewModel ( array (
					'term' => $child,
					'subject' => $subjectService 
			) );
			$taxonomyView->addChild ( $taxView->setTemplate ( 'subject/provider/topic/partial' ), 'taxonomy', true );			
		}
		$view->addChild ( $taxonomyView, 'taxonomy' );
		
		$entityView = new ViewModel ( array (
				'taxonomy' => $topic,
				'subject' => $subjectService,
				'acceptsEntities' => $topic->linkAllowed ( 'entities' ) 
		) );
		$entityView->setTemplate ( 'subject/provider/topic/entities' );
		
		$exerciseView = new ViewModel ( array (
				'exercises' => '' 
		) );
		if (is_array ( $entities )) {
			foreach ( $entities as $exercise ) {
				if ($exercise instanceof TextExercise) {
					$exerciseView->addChild ( $exercise->getViewModelFromController ( 'showRevision' ), 'exercises' );
				}
			}
		}
		$exerciseView->setTemplate ( 'subject/provider/topic/exercises' );
		
		$entityView->addChild ( $exerciseView, 'entities' );
		$view->addChild ( $entityView, 'entities' );
		
		$view->setTemplate ( 'subject/provider/topic/show' );
		
		return $view;
	}
}