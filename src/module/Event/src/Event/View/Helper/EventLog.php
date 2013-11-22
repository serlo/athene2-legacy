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
namespace Event\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Event\Entity\EventLogInterface;
use Event\Exception;

class EventLog extends AbstractHelper
{
    use \Event\EventManagerAwareTrait, \Common\Traits\ConfigAwareTrait;
    
    protected function getDefaultConfig(){
        return array(
            'templates' => array(
                'events' => 'event/helper/events',
                'event' => 'event/helper/event'
            )
        );
    }
    
    public function renderObjectLog($id){
        $events = $this->getEventManager()->findEventsByObject($id);
        return $this->getView()->partial($this->getOption('templates')['events'], array(
            'events' => $events
        ));
    }
    
    public function renderLog($id){
        if(is_numeric($id)){
            $event = $this->getEventManager()->get
        } elseif ($id instanceof EventLogInterface){
            
        } else {
            throw new Exception\InvalidArgumentException(sprintf('Expected numeric or EventLogInterface but got `%s`', gettype($id)));
        }
    }
}