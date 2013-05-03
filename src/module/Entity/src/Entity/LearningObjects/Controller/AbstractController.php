<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Entity\EntityManagerInterface;

class AbstractController extends AbstractActionController
{
    /**
     *
     * @var EntityManagerInterface
     */
    protected $_entityManager;

    /**
     *
     * @return EntityManagerInterface $_entityManager
     */
    public function getEntityManager ()
    {
        return $this->_entityManager;
    }

    /**
     *
     * @param EntityManagerInterface $_entityManager            
     * @return $this
     */
    public function setEntityManager (EntityManagerInterface $_entityManager)
    {
        $this->_entityManager = $_entityManager;
        return $this;
    }
}