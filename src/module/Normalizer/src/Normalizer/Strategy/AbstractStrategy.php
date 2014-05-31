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

use Normalizer\Entity\Normalized;
use Normalizer\Exception\RuntimeException;

abstract class AbstractStrategy implements StrategyInterface
{

    protected $object;

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function normalize($object)
    {
        if (!$this->isValid($object)) {
            throw new RuntimeException(sprintf(
                'I don\'t know how to normalize "%s", maybe you used the wrong strategy?',
                get_class($object)
            ));
        }


        $this->setObject($object);

        $normalized = new Normalized([
            'title'       => $this->getTitle(),
            'content'     => $this->getContent(),
            'type'        => $this->getType(),
            'routeName'   => $this->getRouteName(),
            'routeParams' => $this->getRouteParams(),
            'id'          => $this->getId(),
            'metadata'    => [
                'creationDate' => $this->getCreationDate()
            ]
        ]);

        return $normalized;
    }

    /**
     * @return string
     */
    abstract protected function getContent();

    /**
     * @return int
     */
    abstract protected function getId();

    /**
     * @return string
     */
    abstract protected function getPreview();

    /**
     * @return string
     */
    abstract protected function getRouteName();

    /**
     * @return string
     */
    abstract protected function getRouteParams();

    /**
     * @return string
     */
    abstract protected function getCreationDate();

    /**
     * @return string
     */
    abstract protected function getTitle();

    /**
     * @return string
     */
    abstract protected function getType();
}
