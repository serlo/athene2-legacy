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
namespace Normalizer\Strategy;

use Taxonomy\Entity\TaxonomyTermInterface;
use DateTime;

class TaxonomyTermStrategy extends AbstractStrategy
{

    /**
     *
     * @return TaxonomyTermInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    protected function getTitle()
    {
        return $this->getObject()->getName();
    }

    protected function getTimestamp()
    {
        return new DateTime();
    }

    protected function getContent()
    {
        return $this->getObject()->getDescription();
    }

    protected function getPreview()
    {
        return $this->getObject()->getName();
    }

    protected function getType()
    {
        return $this->getObject()
            ->getTaxonomy()
            ->getName();
    }

    protected function getRouteName()
    {
        return 'home';
    }

    protected function getRouteParams()
    {
        return [];
    }

    public function isValid($object)
    {
        return $object instanceof TaxonomyTermInterface;
    }
}