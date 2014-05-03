<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Entity\Provider;

use Entity\Entity\EntityInterface;
use Normalizer\NormalizerAwareTrait;
use Normalizer\NormalizerInterface;
use Token\Provider\AbstractProvider;
use Token\Provider\ProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class TokenProvider extends AbstractProvider implements ProviderInterface
{
    use ServiceLocatorAwareTrait, NormalizerAwareTrait;

    public function __construct(NormalizerInterface $normalizer, ServiceLocatorInterface $serviceLocator)
    {
        $this->normalizer     = $normalizer;
        $this->serviceLocator = $serviceLocator;
    }

    public function getData()
    {
        $path = $this->getObject()->getId();

        /* @var $term \Taxonomy\Entity\TaxonomyTermInterface */
        foreach ($this->getObject()->getTaxonomyTerms() as $term) {
            $path = $term->slugify('root');
            break;
        }

        $normalized = $this->getNormalizer()->normalize($this->getObject());

        $title = $normalized->getTitle();
        $type  = $normalized->getType();

        return [
            'path'  => $path,
            'type'  => $this->getTranslator()->translate($type),
            'title' => $title,
            'id'    => $this->getObject()->getId(),
        ];
    }

    protected function validObject($object)
    {
        $this->isValid($object);
    }

    public function getTranslator()
    {
        return $this->getServiceLocator()->get('translator');
    }

    protected function isValid(EntityInterface $object)
    {
    }
}
