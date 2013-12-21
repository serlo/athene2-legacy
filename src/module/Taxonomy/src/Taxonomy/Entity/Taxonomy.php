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
namespace Taxonomy\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Language\Entity\LanguageEntityInterface;
use Taxonomy\Exception;

/**
 * A Taxonomy.
 *
 * @ORM\Entity
 * @ORM\Table(name="taxonomy")
 */
class Taxonomy implements TaxonomyInterface
{
    use \Type\Entity\TypeAwareTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="TaxonomyTerm", mappedBy="taxonomy")
     * @ORM\OrderBy({"weight" = "ASC"})
     */
    protected $terms;

    /**
     * @ORM\ManyToOne(targetEntity="Language\Entity\LanguageEntity")
     */
    protected $language;

    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTerms()
    {
        return $this->terms;
    }

    public function addTerm($term)
    {
        $this->getTerms()->add($term);
    }

    public function getName()
    {
        return $this->getType()->getName();
    }

    public function setLanguage(LanguageEntityInterface $language)
    {
        $this->language = $language;
        return $this;
    }

    public function getChildren()
    {
        $collection = new ArrayCollection();
        $terms = $this->getTerms();
        foreach ($terms as $entity) {
            if (! $entity->hasParent() || ($entity->hasParent() && $entity->getParent()->getTaxonomy() !== $this)) {
                $collection->add($entity);
            }
        }
        return $collection;
    }

    public function findTermBySlugPath(array $slugs)
    {
        $terms = $this->getChildren();
        $ancestorsFound = 0;
        $found = NULL;
        foreach ($slugs as &$element) {
            if (is_string($element) && strlen($element) > 0) {
                $element = strtolower($element);
                foreach ($terms as $term) {
                    $found = false;
                    if (strtolower($term->getSlug()) == strtolower($element)) {
                        $terms = $term->getChildren();
                        $found = $term;
                        $ancestorsFound ++;
                        break;
                    }
                }
                if (! is_object($found)) {
                    break;
                }
            }
        }
        
        if (! is_object($found)) {
            throw new Exception\TermNotFoundException(sprintf('Could not find term with acestors: %s', implode(',', $slugs)));
        }
        if ($ancestorsFound != count($slugs)) {
            throw new Exception\TermNotFoundException(sprintf('Could not find term with acestors: %s. Ancestor ratio %s:%s does not equal 1:1', implode(',', $slugs), $ancestorsFound, count($slugs)));
        }
        
        return $found;
    }
}