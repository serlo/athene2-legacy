<?php
namespace Blog\Form;

use Blog\Entity\Post;

use Blog\Stdlib\Model\Registry;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;


class PostFieldset extends Fieldset implements InputFilterProviderInterface
{
  public function __construct()
  {
    parent::__construct('post');

    $em = Registry::get('entityManager');

    $this->setHydrator(new DoctrineEntity($em))
         ->setObject(new Post());

    $this->setLabel('Post');

    $this->add(array(
      'name' => 'id',
      'attributes' => array(
        'type' => 'hidden'
      )
    ));

    $this->add(array(
      'name' => 'title',
      'options' => array(
        'label' => 'Title for this Post'
      ),
      'attributes' => array(
        'type' => 'text'
      )
    ));

    $this->add(array(
      'name' => 'text',
      'options' => array(
        'label' => 'Text-Content for this post'
      ),
      'attributes' => array(
        'type' => 'textarea'
      )
    ));
  }

  /**
   * Define InputFilterSpecifications
   *
   * @access public
   * @return array
   */
  public function getInputFilterSpecification()
  {
    return array(
      'title' => array(
        'required' => true,
        'filters' => array(
          array('name' => 'StringTrim'),
          array('name' => 'StripTags')
        ),
        'properties' => array(
          'required' => true
        )
      ),
      'text' => array(
        'required' => true,
        'filters' => array(
          array('name' => 'StringTrim'),
          array('name' => 'StripTags')
        ),
        'properties' => array(
          'required' => true
        )
      )
    );
  }
}