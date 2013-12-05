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
namespace Language\Service;

use Language\Entity\LanguageEntityInterface;

class LanguageService implements LanguageServiceInterface
{

    /**
     *
     * @var LanguageEntityInterface
     */
    protected $entity;

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getCode()
    {
        return $this->getEntity()->getCode();
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity(LanguageEntityInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function setCode($code)
    {
        $this->getEntity()->setCode($code);
        return $this;
    }
}