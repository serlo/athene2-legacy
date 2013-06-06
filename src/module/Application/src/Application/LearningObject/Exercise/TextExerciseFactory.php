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
        return parent::build($decorator, $entityService);
    }
}