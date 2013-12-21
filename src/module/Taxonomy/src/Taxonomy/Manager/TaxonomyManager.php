<?php
/**
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Manager;

use Taxonomy\Exception\TermNotFoundException;
use Taxonomy\Exception;

class TaxonomyManager implements TaxonomyManagerInterface
{
    use\ClassResolver\ClassResolverAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Language\Model\LanguageModelAwareTrait;

    public function getTerm($id)
    {
        $entity = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Taxonomy\Entity\TaxonomyTermInterface'), (int) $id);
        
        if (! is_object($entity)) {
            throw new TermNotFoundException(sprintf('Term with id %s not found', $id));
        }
        
        return $entity;
    }

    public function findTermByTaxonomyAndSLugs(array $ancestors)
    {
        if (! count($ancestors)) {
            throw new Exception\RuntimeException('Ancestors are empty');
        }
        
        $terms = $this->getEntity()->getSaplings();
        $ancestorsFound = 0;
        $found = false;
        foreach ($ancestors as &$element) {
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
                if (! is_object($found))
                    break;
            }
        }
        
        if (! is_object($found)) {
            throw new Exception\TermNotFoundException(sprintf('Could not find term with acestors: %s', implode(',', $ancestors)));
        }
        if ($ancestorsFound != count($ancestors)) {
            throw new Exception\TermNotFoundException(sprintf('Could not find term with acestors: %s. Ancestor ratio %s:%s does not equal 1:1', implode(',', $ancestors), $ancestorsFound, count($ancestors)));
        }
        
        if (! $this->hasInstance($found->getId())) {
            $this->addInstance($found->getId(), $this->createService($found));
        }
        
        return $this->getInstance($found->getId());
    }
}