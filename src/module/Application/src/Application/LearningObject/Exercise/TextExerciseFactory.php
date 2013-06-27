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

use Entity\Factory\AbstractFactory;
use Entity\Service\EntityServiceInterface;
use Entity\Components\LinkComponent;
use Entity\Components\RepositoryComponent;
use Application\LearningObject\Component\TopicComponent;
use Application\LearningObject\Exercise\Form\TextExerciseForm;

class TextExerciseFactory extends AbstractFactory
{
    /**
     * 
     * @param SubjectServiceInterface $entityService
     * @throws \InvalidArgumentException
     * @returns SubjectServiceInterface;
     */
    public function build(EntityServiceInterface $entityService){        
        $decorator = new TextExercise();
        $decorator->addComponent(new LinkComponent($entityService));
        $decorator->addComponent(new RepositoryComponent($entityService));
        $decorator->addComponent(new TopicComponent($entityService));
        $decorator->setForm(new TextExerciseForm());
        
        // Get a controller
        $controller = $entityService->getServiceLocator()->get('Application\LearningObject\Exercise\Controller\TextExerciseController');
        
        // Set the route handling the controller
        $controller->setRoute('entity/exercise/text');
        
        // Set the factory
        $controller->setEntityFactory(get_class($this));
        
        // Set the entities decorator
        $controller->setEntityClass(get_class($decorator));
        
        // Finally, after factoring the controller, we inject it into the decorator
        $decorator->setController($controller);
        
        return parent::build($decorator, $entityService);
    }
}