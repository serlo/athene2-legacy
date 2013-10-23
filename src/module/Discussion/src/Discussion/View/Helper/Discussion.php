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
namespace Discussion\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Uuid\Entity\UuidInterface;

class Discussion extends AbstractHelper
{
    use \Discussion\DiscussionManagerAwareTrait,\Common\Traits\ConfigAwareTrait,\User\Manager\UserManagerAwareTrait;

    protected $discussions, $object;

    protected $archived;

    /**
     *
     * @return field_type $object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @param field_type $object            
     * @return $this
     */
    public function setObject(UuidInterface $object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     *
     * @return boolean $archived
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     *
     * @param boolean $archived            
     * @return $this
     */
    public function setArchived($archived)
    {
        $this->archived = (bool) $archived;
        return $this;
    }

    protected function getDefaultConfig()
    {
        return array(
            'template' => 'discussion/discussions',
            'form' => array(
                'discussion' => 'Discussion\Form\DiscussionForm',
                'comment' => 'Discussion\Form\CommentForm'
            )
        );
    }

    protected $form;

    public function __invoke(UuidInterface $object = NULL, $archived = false)
    {
        if ($object !== NULL) {
            $this->discussions = $this->getDiscussionManager()->findDiscussionsOn($object, $archived);
            $this->setArchived($archived);
            $this->form = array();
            $this->setObject($object);
        }
        return $this;
    }

    public function getForm($type)
    {
        if (! array_key_exists($type, $this->form) || ! $this->form[$type] instanceof $type) {
            $form = $this->getOption('form')[$type];
            $this->form[$type] = new $form();
        }
        return $this->form[$type];
    }

    public function render()
    {
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        return $this->getView()->partial($this->getOption('template'), array(
            'user' => $user,
            'discussions' => $this->discussions,
            'archived' => $this->archived,
            'plugin' => $this,
            'object' => $this->getObject()
        ));
    }
}