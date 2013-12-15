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
namespace Term\Service;

use Term\Manager\TermManagerInterface;
use Term\Model\TermModelInterface;
use Term\Entity\TermEntityInterface;
use Taxonomy\Manager\TaxonomyManagerInterface;

interface TermServiceInterface extends TermModelInterface
{
    /**
     * @param TaxonomyManagerInterface $manager
     * @return $this
     */
    public function setManager(TermManagerInterface $manager);
    
    /**
     * 
     * @return TaxonomyManagerInterface
     */
    public function getManager();

    /**
     *
     * @return \Language\Model\LanguageModelInterface $language
     */
    public function getLanguage();
    
    /**
     * 
     * @param TermEntityInterface $entity
     * @return $this
     */
    public function setEntity(TermEntityInterface $entity);
}