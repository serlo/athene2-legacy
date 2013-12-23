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
namespace Subject\Provider;

use Taxonomy\Router\ParamProviderInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Router\AbstractParamProvider;

class ParamProvider extends AbstractParamProvider implements ParamProviderInterface
{

    public function getParams()
    {
        return array(
            'subject' => $this->getObject()
                ->findAncestorByTypeName('subject')
                ->getSlug(),
            'path' => $this->getPathToTermAsUri($this->getObject())
        );
    }

    protected function getPathToTermAsUri(TaxonomyTermInterface $term)
    {
        return substr($this->_getPathToTermAsUri($term), 0, - 1);
    }

    protected function _getPathToTermAsUri(TaxonomyTermInterface $term)
    {
        return ($term->getTaxonomy()->getName() != 'subject') ? $this->_getPathToTermAsUri($term->getParent()) . $term->getSlug() . '/' : '';
    }
}