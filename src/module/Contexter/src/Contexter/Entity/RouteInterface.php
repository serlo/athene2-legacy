<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Contexter\Entity;

use Doctrine\Common\Collections\Collection;
use Instance\Entity\InstanceProviderInterface;

interface RouteInterface extends InstanceProviderInterface
{

    /**
     * @return int
     */
    public function getId();

    /**
     * @return ContextInterface
     */
    public function getContext();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return Collection
     */
    public function getParameters();

    /**
     * @param string $name
     * @return self
     */
    public function setName($name);

    /**
     * @param ContextInterface $context
     * @return self
     */
    public function setContext(ContextInterface $context);

    /**
     * @param array $parameters
     * @return self
     */
    public function addParameters(array $parameters);

    /**
     * @param string $key
     * @param string $value
     * @return self
     */
    public function addParameter($key, $value);
}