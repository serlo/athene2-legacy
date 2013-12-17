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
namespace Discussion\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uuid\Entity\UuidInterface;
use Language\Entity\LanguageEntityInterface;
use User\Entity\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Uuid\Entity\UuidEntity;
use Doctrine\Common\Collections\Criteria;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Entity\TaxonomyTermNodeInterface;

/**
 * Comment ORM Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment extends UuidEntity implements CommentInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="comment")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Uuid\Entity\Uuid")
     */
    protected $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children", cascade={"persist"})
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="comment", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $votes;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\LanguageEntity")
     */
    protected $language;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     */
    protected $author;

    /**
     * @ORM\ManyToMany(targetEntity="Taxonomy\Entity\TaxonomyTerm", inversedBy="entities")
     * @ORM\JoinTable(name="term_taxonomy_comment",
     * inverseJoinColumns={@ORM\JoinColumn(name="term_taxonomy_id", referencedColumnName="id")},
     * joinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")}
     * )
     */
    protected $terms;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $archived;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $content;

    public function isDiscussion()
    {
        return ! $this->hasParent();
    }

    public function getArchived()
    {
        return $this->archived;
    }

    public function setArchived($archived)
    {
        $this->archived = $archived;
        return $this;
    }

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->terms = new ArrayCollection();
        $this->archived = false;
    }

    public function getObject()
    {
        return $this->uuid;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setObject(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function setParent(CommentInterface $comment)
    {
        $this->parent = $comment;
        return $this;
    }

    public function setLanguage(LanguageEntityInterface $language)
    {
        $this->language = $language;
        return $this;
    }

    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(CommentInterface $comment)
    {
        $this->children->add($comment);
        return $this;
    }

    public function hasParent()
    {
        return is_object($this->getParent());
    }

    public function getVotes()
    {
        return $this->votes;
    }

    public function countUpVotes()
    {
        return $this->getVotes()
            ->filter(function (VoteInterface $v)
        {
            return $v->getVote() === 1;
        })
            ->count();
    }

    public function countDownVotes()
    {
        return $this->getVotes()
            ->filter(function (VoteInterface $v)
        {
            return $v->getVote() === - 1;
        })
            ->count();
    }

    protected function createVote(UserInterface $user, $vote)
    {
        $entity = new Vote();
        $entity->setUser($user);
        $entity->setVote($vote);
        $entity->setComment($this);
        $this->getVotes()->add($entity);
        return $entity;
    }

    protected function findVotesByUser(UserInterface $user)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('user', $user))
            ->setFirstResult(0)
            ->setMaxResults(1);
        
        return $this->getVotes()->matching($criteria);
    }

    public function upVote(UserInterface $user)
    {
        if ($this->findVotesByUser($user)->count() > 0)
            return NULL;
        
        $this->createVote($user, 1);
        return $this;
    }

    public function downVote(UserInterface $user)
    {
        if ($this->findVotesByUser($user)->count() === 0)
            return NULL;
        
        $this->getVotes()->removeElement($this->findVotesByUser($user)
            ->current());
        return $this;
    }

    public function hasUserVoted(UserInterface $user)
    {
        return $this->findVotesByUser($user)->count() === 1;
    }

    public function addTaxonomyTerm(TaxonomyTermInterface $taxonomyTerm, TaxonomyTermNodeInterface $node = NULL)
    {
        $this->terms->add($taxonomyTerm);
        return $this;
    }

    public function removeTaxonomyTerm(TaxonomyTermInterface $taxonomyTerm, TaxonomyTermNodeInterface $node = NULL)
    {
        $this->terms->removeElement($taxonomyTerm);
        return $this;
    }

    public function getTaxonomyTerms()
    {
        return $this->terms;
    }
}