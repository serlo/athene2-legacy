<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Normalizer;

use Normalizer\Strategy\StrategyInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Normalizer implements NormalizerInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var Strategy\StrategyInterface[]
     */
    protected $strategies = [
        'Normalizer\Strategy\AttachmentStrategy',
        'Normalizer\Strategy\PageRepositoryStrategy',
        'Normalizer\Strategy\PageRevisionStrategy',
        'Normalizer\Strategy\EntityStrategy',
        'Normalizer\Strategy\TaxonomyTermStrategy',
        'Normalizer\Strategy\CommentStrategy',
        'Normalizer\Strategy\UserStrategy',
        'Normalizer\Strategy\EntityRevisionStrategy',
        'Normalizer\Strategy\PostStrategy'
    ];

    protected $cache = [];

    public function normalize($object)
    {
        $objectHash = spl_object_hash($object);

        if (isset($this->cache[$objectHash])) {
            return $this->cache[$objectHash];
        }

        /* @var $strategy Strategy\StrategyInterface */
        foreach ($this->strategies as & $strategy) {
            if (!$strategy instanceof StrategyInterface) {
                $strategy = $this->getServiceLocator()->get($strategy);
            }

            if ($strategy->isValid($object)) {
                $normalized               = $strategy->normalize($object);
                $this->cache[$objectHash] = $normalized;

                return $normalized;
            }
        }

        throw new Exception\RuntimeException(sprintf('No strategy found for "%s"', get_class($object)));
    }
}
