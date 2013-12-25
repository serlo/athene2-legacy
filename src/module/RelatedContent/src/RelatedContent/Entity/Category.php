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
namespace RelatedContent\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="related_content_category")
 */
class Category extends AbstractType implements CategoryInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Holder", inversedBy="category")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;
    
    public function getTitle()
    {
        return $this->name;
    }
    
    public function setTitle($title)
    {
        $this->name = $title;
        return $this;
    }
}