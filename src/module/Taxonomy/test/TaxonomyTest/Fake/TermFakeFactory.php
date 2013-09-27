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
namespace TaxonomyTest\Fake;

class TermFakeFactory
{

    protected $tree = array(
        array(
            'id' => 1,
            'slug' => 'some',
            'children' => array(
                array(
                    'id' => 2,
                    'slug' => 'foo',
                    'children' => array(
                        array(
                            'id' => 3,
                            'slug' => 'bar'
                        ),
                        array(
                            'id' => 4,
                            'slug' => 'test'
                        )
                    )
                )
            )
        )
    );

    public function createTree($taxonomy)
    {
        $roots = array();
        foreach ($this->tree as $leaf) {
            $term = new TermFake();
            $this->hydrate($term, $leaf, $taxonomy);
            $roots[] = $term;
        }
        return $roots;
    }

    private function hydrate($term, $leaf, $taxonomy)
    {
        $term->setId($leaf['id']);
        $term->setSlug($leaf['slug']);
        $term->setTaxonomy($taxonomy);
        if (array_key_exists('children', $leaf)) {
            $children = array();
            foreach ($leaf['children'] as $child) {
                $termFake = new TermFake();
                $this->hydrate($termFake, $child, $taxonomy);
                $children[] = $termFake;
            }
            $term->setChildren($children);
        }
    }
}