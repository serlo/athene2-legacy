<?php
namespace Application\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;

class UpdateBlogPostForm extends Form
{
    public function __construct(ServiceManager $serviceManager)
    {
        parent::__construct('update-blog-post-form');
        $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');

        // The form will hydrate an object of type "BlogPost"
        $this->setHydrator(new DoctrineHydrator($entityManager, 'Application\Entity\BlogPost'));

        // Add the user fieldset, and set it as the base fieldset
        $blogPostFieldset = new BlogPostFieldset($serviceManager);
        $blogPostFieldset->setUseAsBaseFieldset(true);
        $this->add($blogPostFieldset);

        // … add CSRF and submit elements …

        // Optionally set your validation group here
    }
}
?>