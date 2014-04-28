<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
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

    public function getForm($type, UuidInterface $object, TaxonomyTermInterface $forum = null)
    {
        $view = $this->getView();
        switch ($type) {
            case 'discussion':
                $form = clone $this->discussionForm;
                if ($forum) {
                    $form->setAttribute(
                        'action',
                        $view->url(
                            'discussion/discussion/start',
                            ['on' => $object->getId(), 'forum' => $forum->getId()]
                        )
                    );
                } else {
                    $form->setAttribute(
                        'data-select-forum-href',
                        $view->url('discussion/discussion/select/forum', ['on' => $object->getId()])
                    );
                }
                return $form;
                break;
            case 'comment':
                $form = clone $this->commentForm;
                $form->setAttribute(
                    'action',
                    $view->url(
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

    protected function getDefaultConfig()
    {
        return [
            'template' => 'discussion/discussions',
            'root'     => 'root',
            'forum'    => 'forum'
        ];
    }
}