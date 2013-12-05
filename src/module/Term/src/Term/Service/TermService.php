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
namespace Term\Service;

use Term\Manager\TermManagerInterface;
use Term\Entity\TermEntityInterface;
use Language\Model\LanguageModelInterface;

class TermService implements TermServiceInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Language\Model\LanguageModelAwareTrait;

    /**
     *
     * @var TaxonomyManagerInterface
     */
    protected $manager;

    /**
     *
     * @var TermEntityInterface
     */
    protected $entity;

    public function getManager()
    {
        return $this->manager;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getLanguage()
    {
        return $this->getEntity()->getLanguage();
    }

    public function getName()
    {
        return $this->getEntity()->getName();
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getSlug()
    {
        return $this->getEntity()->getSlug();
    }

    public function setLanguage(LanguageModelInterface $language)
    {
        $language = $language->getEntity();
        $this->getEntity()->setLanguage($language);
        return $this;
    }

    public function setName($name)
    {
        $this->getEntity()->setName($name);
        return $this;
    }

    public function setSlug($slug)
    {
        $this->getEntity()->setSlug($slug);
        return $this;
    }

    public function setManager(TermManagerInterface $manager)
    {
        $this->manager = $manager;
        return $this;
    }

    public function setEntity(TermEntityInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }
}