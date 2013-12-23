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
namespace Normalizer;

use Normalizer\Strategy\StrategyInterface;

class Normalizer implements NormalizerInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    /**
     *
     * @var Strategy\StrategyInterface[]
     */
    protected $strategies = [
        'Normalizer\Strategy\PageRepositoryStrategy',
        'Normalizer\Strategy\EntityStrategy',
        'Normalizer\Strategy\TaxonomyTermStrategy',
        'Normalizer\Strategy\CommentStrategy'
    ];

    public function normalize($object)
    {
        /* @var $strategy Strategy\StrategyInterface */
        foreach ($this->strategies as & $strategy) {
            if (! $strategy instanceof StrategyInterface) {
                $strategy = $this->getServiceLocator()->get($strategy);
            }
            
            if ($strategy->isValid($object)) {
                return $strategy->normalize($object);
            }
        }
        throw new Exception\RuntimeException(sprintf('No strategy found for "%s"', get_class($object)));
    }
}