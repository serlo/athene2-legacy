<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Type;

trait TypeManagerAwareTrait
{
    /**
     * The type manager
     *
     * @var TypeManagerInterface
     */
    protected $typeManager;

    /**
     * Gets the type manager
     *
     * @return TypeManagerInterface $typeManager
     */
    public function getTypeManager()
    {
        return $this->typeManager;
    }

    /**
     * Sets the type manager
     *
     * @param TypeManagerInterface $typeManager
     * @return self
     */
    public function setTypeManager(TypeManagerInterface $typeManager)
    {
        $this->typeManager = $typeManager;
        return $this;
    }
}
