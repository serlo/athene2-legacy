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
namespace Taxonomy\Model;

use Taxonomy\Entity\TaxonomyInterface;
use Taxonomy\Model\TaxonomyTermModelInterface;
use Doctrine\Common\Collections\Collection;
use Language\Entity\LanguageInterface;
use Term\Entity\TermEntityAwareInterface;
use Taxonomy\Entity\TaxonomyTypeInterface;
use Common\Model\Wrapable;
use Uuid\Entity\UuidHolder;


interface TaxonomyTermModelInterface extends TermEntityAwareInterface, Wrapable, UuidHolder
{
}