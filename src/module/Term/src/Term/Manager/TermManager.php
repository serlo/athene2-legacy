<?php
/**
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 * http://dev/
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Term\Manager;

use Common\Filter\Slugify;
use Language\Entity\LanguageInterface;
use Term\Exception\TermNotFoundException;

class TermManager implements TermManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait, \ClassResolver\ClassResolverAwareTrait;

    public function getTerm($id)
    {
        $instance = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Term\Entity\TermEntityInterface'), $id);

        if (!is_object($instance)) {
            throw new TermNotFoundException($id);
        }

        return $instance;
    }

    public function findTermBySlug($slug, LanguageInterface $language)
    {
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
                ->resolveClassName('Term\Entity\TermEntityInterface'))
            ->findOneBy(array(
                'slug'     => $slug,
                'language' => $language->getId()
            ));

        if (!is_object($entity)) {
            throw new TermNotFoundException(sprintf('Term %s with Language %s not found', $slug, $language->getId()));
        }

        return $entity;
    }

    public function findTermByName($name, LanguageInterface $language)
    {
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
                ->resolveClassName('Term\Entity\TermEntityInterface'))
            ->findOneBy(array(
                'name'     => $name,
                'language' => $language->getId()
            ));

        if (!is_object($entity)) {
            throw new TermNotFoundException(sprintf('Term %s with Language %s not found', $name, $language->getId()));
        }

        return $entity;
    }

    public function createTerm($name, $slug = null, LanguageInterface $language)
    {
        $filter = new Slugify();
        $slug   = ($slug ? $slug : $filter->filter($name));
        var_dump($slug);
        $entity = $this->getClassResolver()->resolve('Term\Entity\TermEntityInterface');

        $entity->setName($name);
        $entity->setLanguage($language);
        $entity->setSlug($slug);
        $this->getObjectManager()->persist($entity);

        return $entity;
    }
}
