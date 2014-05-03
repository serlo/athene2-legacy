<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Normalizer\Strategy;

use DateTime;
use Normalizer\Exception\RuntimeException;
use Taxonomy\Entity\TaxonomyTermInterface;

class TaxonomyTermStrategy extends AbstractStrategy
{

    /**
     * @return TaxonomyTermInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    public function isValid($object)
    {
        return $object instanceof TaxonomyTermInterface;
    }

    protected function getContent()
    {
        return $this->getObject()->getDescription();
    }

    protected function getId()
    {
        return $this->getObject()->getId();
    }

    protected function getPreview()
    {
        return $this->getObject()->getName();
    }

    protected function getRouteName()
    {
        $object = $this->getObject();
        switch ($object->getType()->getName()) {
            case 'blog':
                return 'blog/view';
            case 'forum-category':
            case 'forum':
                return 'discussion/discussions/get';
            case 'topic':
            case 'topic-folder':
            case 'curriculum':
            case 'locale':
            case 'curriculum-topic':
            case 'curriculum-topic-folder':
            case 'topic-final-folder':
            case 'subject':
                return 'taxonomy/term/get';
        }

        throw new RuntimeException(sprintf('No strategy found for %s', $object->getType()->getName()));
    }

    protected function getRouteParams()
    {
        $object = $this->getObject();
        switch ($object->getType()->getName()) {
            case 'blog':
                return ['id' => $object->getId()];
            case 'forum':
            case 'forum-category':
                return ['id' => $object->getId()];
            case 'topic':
            case 'topic-folder':
            case 'curriculum':
            case 'locale':
            case 'curriculum-topic':
            case 'curriculum-topic-folder':
            case 'subject':
            case 'topic-final-folder':
                return ['term' => $object->getId()];
        }

        throw new RuntimeException(sprintf('No strategy found for %s', $object->getType()->getName()));
    }

    protected function getTimestamp()
    {
        return new DateTime();
    }

    protected function getTitle()
    {
        return $this->getObject()->getName();
    }

    protected function getType()
    {
        return $this->getObject()->getTaxonomy()->getName();
    }
}
