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
namespace Subject\View;

use Zend\View\Helper\Url as ZfUrl;
use Subject\Service\SubjectServiceInterface;

class Url extends ZfUrl
{
    protected $subjectService;
    
    public function setSubjectService(SubjectServiceInterface $subjectService){
        $this->subjectService = $subjectService;
        return $this;
    }
    
    public function __invoke($name = null, $params = array(), $options = array(), $reuseMatchedParams = false){
        if(!$name && !count($params) && !count($options) && !$reuseMatchedParams){
            return $this;
        } else {
            return parent::__invoke($name, $params, $options, $reuseMatchedParams);
        }
    }
    
    public function buildWithSubject($name = null, $params = array(), $options = array(), $reuseMatchedParams = false){
        return parent::__invoke('subject/'.$this->subjectService->getName().'/' . $name, $params, $options, $reuseMatchedParams);
    }
}