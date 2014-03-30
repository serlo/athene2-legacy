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

use Discussion\Exception\RuntimeException;
use Discussion\Form\CommentForm;
use Discussion\Form\DiscussionForm;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Form\TermForm;
use Uuid\Entity\UuidInterface;
use Zend\View\Helper\AbstractHelper;
use ZfcTwig\View\TwigRenderer;

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

    /**
     * @var \Discussion\Form\CommentForm
     */
    protected $commentForm;

    /**
     * @var \Discussion\Form\DiscussionForm
     */
    protected $discussionForm;

    /**
     * @var \ZfcTwig\View\TwigRenderer
     */
    protected $renderer;

    /**
     * @var array
     */
    protected $inMemory = [];

    public function __construct(
        TermForm $termForm,
        CommentForm $commentForm,
        DiscussionForm $discussionForm,
        TwigRenderer $renderer
    ) {
        $this->renderer       = $renderer;
        $this->form           = [];
        $this->termForm       = $termForm;
        $this->commentForm    = $commentForm;
        $this->discussionForm = $discussionForm;
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

    public function findForum(array $path)
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName('root', $instance);
        $term     = $this->getTaxonomyManager()->findTerm($taxonomy, ['root']);
        $this->setForum($this->iterForums($path, $term));
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

    public function getDiscussions(UuidInterface $object)
    {
        return $this->getDiscussionManager()->findDiscussionsOn($object);
    }

    public function getForm($type, $object, $forum = null)
    {
        switch ($type) {
            case 'discussion':
                $form = clone $this->discussionForm;
                $form->setAttribute(
                    'action',
                    $this->getView()->url(
                        'discussion/discussion/start',
                        ['on' => $object->getId(), 'forum' => $forum->getId()]
                    )
                );
                return $form;
                break;
            case 'comment':
                $form = clone $this->commentForm;
                $form->setAttribute(
                    'action',
                    $this->getView()->url(
                        'discussion/discussion/comment',
                        ['discussion' => $object->getId()]
                    )
                );
                return $form;
                break;
            default:
                throw new RuntimeException();
        }
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

    public function getObject()
    {
        return $this->object;
    }

    public function getUser()
    {
        return $this->getUserManager()->getUserFromAuthenticator();
    }

    public function render()
    {
        return $this->renderer->render(
            $this->getOption('template'),
            [
                'discussions' => $this->discussions,
                'isArchived'  => $this->archived,
                'object'      => $this->getObject(),
                'forum'       => $this->getForum()
            ]
        );
    }

    public function setObject(UuidInterface $object)
    {
        $this->object = $object;
        return $this;
    }

    protected function createForums(array $forums, TaxonomyTermInterface $current)
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName('forum', $instance);

        foreach ($forums as $forum) {
            $data = [
                'term'     => [
                    'name' => $forum
                ],
                'parent'   => $current,
                'taxonomy' => $taxonomy
            ];
            $key  = hash('sha512', serialize($data));
            if (!isset($this->inMemory[$key])) {
                $form = clone $this->termForm;
                $form->setData($data);
                $current              = $this->getTaxonomyManager()->createTerm($form);
                $this->inMemory[$key] = $current;
                unset($form);
            }
        }
        $this->getTaxonomyManager()->flush($current);
        return $this->getTaxonomyManager()->getTerm($current);
    }

    protected function getDefaultConfig()
    {
        return [
            'template'       => 'discussion/discussions',
            'root'           => 'root',
            'forum'          => 'forum',
            'forum_category' => 'forum-category'
        ];
    }

    /**
     * @param TaxonomyTermInterface[] $forums
     * @param TaxonomyTermInterface   $current
     * @return TaxonomyTermInterface
     */
    protected function iterForums(array $forums, TaxonomyTermInterface $current)
    {
        if (empty($forums)) {
            return $current;
        }

        /* @var TaxonomyTermInterface $current */
        $forum    = current($forums);
        $children = $current->findChildrenByTaxonomyNames(['forum']);

        foreach ($children as $child) {
            if ($child->getName() == $forum || $child->getSlug() == $forum) {
                array_shift($forums);
                return $this->iterForums($forums, $child);
            }
        }
        return $this->createForums($forums, $current);
    }
}