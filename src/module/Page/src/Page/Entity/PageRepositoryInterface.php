<?php
namespace Page\Entity;

use Instance\Entity\InstanceAwareInterface;
use License\Entity\LicenseAwareInterface;
use Versioning\Entity\RepositoryInterface;

interface PageRepositoryInterface extends RepositoryInterface, LicenseAwareInterface, InstanceAwareInterface
{
}
