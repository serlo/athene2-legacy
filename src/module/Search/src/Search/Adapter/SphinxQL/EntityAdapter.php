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
namespace Search\Adapter\SphinxQL;

use Entity\Collection\EntityCollection;
use Doctrine\Common\Collections\ArrayCollection;
class EntityAdapter extends AbstractSphinxAdapter
{
    use \Entity\Manager\EntityManagerAwareTrait;
    
    public function search($query)
    {
        $query = $this->forge();
        $query->select('value')->from('entityIndex')->match('value', $query);
        $results = $query->execute();
        $collection = new ArrayCollection($results);
        return new EntityCollection($collection, $this->getEntityManager());
    }
}