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
namespace Subject\Taxonomy;

use Core\Decorator\GraphDecorator;
use Taxonomy\Service\TermServiceInterface;
use Application\Taxonomy\Form\TaxonomyForm;
use Taxonomy\Factory\FactoryInterface;

class SubjectTaxonomy implements FactoryInterface
{

    public function build(TermServiceInterface $termService)
    {
        $instance = new Term($termService);
        // $instance->setTemplate('taxonomy/default/partial');
        return $this->inject($instance, $termService);
    }

    protected function inject(GraphDecorator $decorator, TermServiceInterface $termService)
    {
        if ($termService instanceof GraphDecorator)
            throw new \Exception('Ouch, this could get really really messy. Stop whatever you are doing and go to bed.');
        
        $decorator->addComponent($termService);
        $decorator->setForm(new TaxonomyForm());
        return $decorator;
    }
}