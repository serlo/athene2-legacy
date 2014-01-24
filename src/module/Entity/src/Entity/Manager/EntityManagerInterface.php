<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Manager;

use Entity\Entity\EntityInterface;
use Language\Entity\LanguageInterface;

interface EntityManagerInterface
{

    /**
     * @param string            $type
     * @param array             $data
     * @param LanguageInterface $language
     * @return EntityInterface
     */
    public function createEntity($type, array $data = array(), LanguageInterface $language);

    /**
     * @param int $id
     * @return EntityInterface
     */
    public function getEntity($id);
}