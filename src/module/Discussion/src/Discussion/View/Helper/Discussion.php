<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Discussion\View\Helper;

use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Form\TermForm;
use Uuid\Entity\UuidInterface;
use Zend\View\Helper\AbstractHelper;

class Discussion extends AbstractHelper
{
    use \Discussion\DiscussionManagerAwareTrait, \Common\Traits\ConfigAwareTrait, \User\Manager\UserManagerAwareTrait,
        \Taxonomy\Manager\TaxonomyManagerAwareTrait, \Instance\Manager\InstanceManagerAwareTrait;

    protected $discussions, $object;

    protected $form;

    protected $archived;

    protected $forum;

    /**
     * @var TermForm
     */
    protected $termForm;

    public function __construct(TermForm $termForm)
    {
        $this->form     = array();
        $this->termForm = $termForm;
    }

    /**
     * @return field_type $reference
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param field_type $reference
     * @return self
     */
    public function setObject(UuidInterface $object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return boolean $archived
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param boolean $archived
     * @return self
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

    public function setForum(TaxonomyTermInterface $forum)
    {
        $this->forum = $forum;

        return $this;
    }

    public function getForm($type)
    {
        if (!array_key_exists($type, $this->form) || !$this->form[$type] instanceof $type) {
            $form              = $this->getOption('form')[$type];
            $this->form[$type] = new $form();
        }

        return $this->form[$type];
    }

    public function __invoke(UuidInterface $object = null, $forum = null, $archived = null)
    {
        if ($object !== null) {
            $this->discussions = $this->getDiscussionManager()->findDiscussionsOn($object, $archived);
            $this->setObject($object);
        }
        if ($archived !== null) {
            $this->setArchived($archived);
        }

        if ($forum !== null) {
            $this->setForum($forum);
        }

        return $this;
    }

    public function getUser()
    {
        return $this->getUserManager()->getUserFromAuthenticator();
    }

    public function render()
    {
        return $this->getView()->partial(
            $this->getOption('template'),
            array(
                'discussions' => $this->discussions,
                'isArchived'  => $this->archived,
                'object'      => $this->getObject(),
                'forum'       => $this->getForum()
            )
        );
    }

    public function findForum(array $forums)
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName('root', $instance);
        $term     = $this->getTaxonomyManager()->findTerm(
            $taxonomy,
            [
                'root',
                'discussions'
            ]
        );
        $this->setForum($this->iterForums($forums, $term));

        return $this;
    }

    protected function iterForums(array $forums, TaxonomyTermInterface $current)
    {
        if (empty($forums)) {
            return $current;
        }

        $forum    = current($forums);
        $children = $current->findChildrenByTaxonomyNames(
            [
                'forum'
            ]
        );

        foreach ($children as $child) {
            if ($child->getName() == $forum || $child->getSlug() == $forum) {
                array_shift($forums);

                return $this->iterForums($forums, $child);
            }
        }

        return $this->createForums($forums, $current);
    }

    protected function createForums(array $forums, TaxonomyTermInterface $current)
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName('forum', $instance);

        foreach ($forums as $forum) {
            $form = $this->termForm;
            $form->setData(
                [
                    'term'        => [
                        'name' => $forum
                    ],
                    'parent'      => $current,
                    'taxonomy'    => $taxonomy
                ]);
            $current = $this->getTaxonomyManager()->createTerm($form);
        }

        $this->getTaxonomyManager()->getObjectManager()->flush();

        return $this->getTaxonomyManager()->getTerm($current);
    }

    protected function getDefaultConfig()
    {
        return [
            'template'       => 'discussion/discussions',
            'form'           => array(
                'discussion' => 'Discussion\Form\DiscussionForm',
                'comment'    => 'Discussion\Form\CommentForm'
            ),
            'root'           => 'root',
            'forum'          => 'forum',
            'forum_category' => 'forum-category'
        ];
    }
}