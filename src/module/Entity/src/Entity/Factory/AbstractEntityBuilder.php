<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\Factory;

abstract class AbstractEntityBuilder extends EntityServiceProxy implements EntityBuilderInterface
{

    /**
     *
     * @param EntityFactoryInterface $adaptee            
     * @return $this
     */
    public function build (EntityFactoryInterface $adaptee)
    {
        $this->setSource($adaptee);
        
        $this->uniqueName = 'Entity(' . $adaptee->getId() . ')';
        $this->_loadComponents();
        return $this;
    }

    abstract protected function _loadComponents ();

    public function __construct ()
    {}
}