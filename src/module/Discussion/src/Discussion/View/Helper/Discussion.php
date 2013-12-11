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
use Taxonomy\Exception\TermNotFoundException;
use Taxonomy\Service\TermServiceInterface;

class Discussion extends AbstractHelper
{
    use \Discussion\DiscussionManagerAwareTrait,\Common\Traits\ConfigAwareTrait,\User\Manager\UserManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    protected $discussions, $object;

    protected $form;

    protected $archived;

    protected $forum;

    public function __construct()
    {
        $this->form = array();
    }

    /**
     *
     * @return field_type $reference
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @param field_type $reference            
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
        $this->archived = $archived;
        return $this;
    }

    public function getForum()
    {
        return $this->forum;
    }

    public function setForum(TermServiceInterface $forum)
    {
        $this->forum = $forum;
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

    public function __invoke(UuidInterface $object = NULL, $forum = NULL, $archived = NULL)
    {
        if ($object !== NULL) {
            $this->discussions = $this->getDiscussionManager()->findDiscussionsOn($object, $archived);
            $this->setObject($object);
        }
        if ($archived !== NULL) {
            $this->setArchived($archived);
        }
        
        if ($forum !== NULL) {
            $this->setForum($forum);
        }
        return $this;
    }

    public function render()
    {
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        return $this->getView()->partial($this->getOption('template'), array(
            'user' => $user,
            'discussions' => $this->discussions,
            'isArchived' => $this->archived,
            'plugin' => $this,
            'object' => $this->getObject(),
            'forum' => $this->getForum()
        ));
    }

    public function findForum(array $forums)
    {
        $language = $this->getLanguageManager()->getLanguageFromRequest();
        $taxonomy = $this->getSharedTaxonomyManager()->findTaxonomyByName('root', $language);
        $term = $taxonomy->findTermByAncestors([
            'root',
            'discussions'
        ]);
        $this->setForum($this->iterForums($forums, $term));
        return $this;
    }

    protected function iterForums(array $forums, TermServiceInterface $current)
    {
        if (empty($forums)) {
            return $current;
        }
        
        $forum = current($forums);
        
        foreach ($current->findChildrenByTaxonomyNames(['forum']) as $child) {
            if ($child->getSlug() == $forum) {
                array_shift($forums);
                return $this->iterForums($forums, $child);
            }
        }
        
        return $this->createForums($forums, $current);
    }

    protected function createForums(array $forums, TermServiceInterface $current)
    {
        $taxonomy = $this->getSharedTaxonomyManager()->findTaxonomyByName('forum', $this->getLanguageManager()
            ->getLanguageFromRequest());
        
        foreach ($forums as $forum) {
            $current = $this->getSharedTaxonomyManager()->createTerm([
                'term' => [
                    'name' => $forum
                ],
                'parent' => $current,
                'taxonomy' => $taxonomy->getId()
            ]);
        }
        
        $this->getSharedTaxonomyManager()
            ->getObjectManager()
            ->flush();
        
        return $this->getSharedTaxonomyManager()->getTerm($current);
    }

    protected function getDefaultConfig()
    {
        return array(
            'template' => 'discussion/discussions',
            'form' => array(
                'discussion' => 'Discussion\Form\DiscussionForm',
                'comment' => 'Discussion\Form\CommentForm'
            ),
            'root' => 'root',
            'forum' => 'forum',
            'forum_category' => 'forum-category'
        );
    }
}