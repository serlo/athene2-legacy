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
namespace Uuid\Entity;

use Doctrine\ORM\Mapping as ORM;
use Uuid\Exception;

/**
 * @ORM\Entity
 * @ORM\Table(name="uuid")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 * "taxonomyTerm" = "Taxonomy\Entity\TaxonomyTerm",
 * "user" = "User\Entity\User",
 * "attachment" = "Attachment\Entity\Container",
 * "blogPost" = "Blog\Entity\Post",
 * "entity" = "Entity\Entity\Entity",
 * "entityRevision" = "Entity\Entity\Revision",
 * "page" = "Page\Entity\PageRepository",
 * "pageRevision" = "Page\Entity\PageRevision",
 * "comment" = "Discussion\Entity\Comment"
 * })
 */
class Uuid implements UuidInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $trashed = false;

    /**
     * @ORM\OneToMany(targetEntity="Flag\Entity\Flag", mappedBy="object")
     */
    protected $flags;

    public function isTrashed()
    {
        return $this->getTrashed();
    }

    public function getTrashed()
    {
        return $this->trashed;
    }

    public function setTrashed($trashed)
    {
        $this->trashed = (bool)$trashed;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }
}
