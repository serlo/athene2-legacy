<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Model;

use License\Entity\LicenseAwareInterface;
use Link\Entity\LinkableInterface;
use Versioning\Entity\RepositoryInterface;
use Taxonomy\Model\TaxonomyTermModelAwareInterface;
use Language\Model\LanguageModelAwareInterface;
use Common\Model\Wrapable;
use Uuid\Entity\UuidHolder;
use Datetime;

interface EntityModelInterface extends UuidHolder, Wrapable, LanguageModelAwareInterface, RepositoryInterface, LinkableInterface, LicenseAwareInterface, TaxonomyTermModelAwareInterface
{

    /**
     *
     * @return TypeModelInterface
     */
    public function getType();

    /**
     *
     * @return DateTime
     */
    public function getTimestamp();

    /**
     *
     * @param TypeModelInterface $type            
     * @return self
     */
    public function setType(TypeModelInterface $type);

    /**
     *
     * @param DateTime $date            
     */
    public function setTimestamp(DateTime $date);

    /**
     *
     * @return self
     */
    public function getEntity();
}