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
namespace Application\Subject\Provider\Topic;

use Subject\Service\SubjectServiceInterface;
use Core\Component\ComponentInterface;
use Core\Component\AbstractComponent;

class TopicProvider extends AbstractComponent implements ComponentInterface
{
    protected $publicMethods = array('getTopic', 'getTopics');
    
    /**
     *
     * @var SubjectServiceInterface
     */
    protected $subjectService;

    public function __construct (SubjectServiceInterface $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function getTopic ($topic)
    {
        return $this->subjectService->getTaxonomy('topic')->get($topic);
    }

    public function getTopics ()
    {
        $terms = $this->subjectService->getTaxonomy('topic');
        $terms = $terms->getTerms();
        return $terms;
    }
}