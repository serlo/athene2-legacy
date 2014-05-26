<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Notification\Filter;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Notification\Entity\NotificationInterface;
use Zend\Filter\Exception;
use Zend\Filter\FilterInterface;

class PersistentEmptyFilter implements FilterInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if ($value instanceof Collection) {
            return $value->filter([$this, 'passes']);
        } elseif (is_array($value)) {
            return array_filter($value, [$this, 'passes']);
        } else {
            throw new Exception\RuntimeException(sprintf(
                'Expected Collection or array but got %s',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }
    }

    /**
     * @param NotificationInterface $notification
     * @return bool
     */
    protected function passes(NotificationInterface $notification)
    {
        if ($notification->getEvents()->count() == 0) {
            $this->objectManager->remove($notification);
            $this->objectManager->persist($notification);
            return false;
        }

        return true;
    }
}
