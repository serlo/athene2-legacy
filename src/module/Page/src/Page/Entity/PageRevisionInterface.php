<?php
namespace Page\Entity;

use Instance\Entity\InstanceProviderInterface;
use Versioning\Entity\RevisionInterface;

interface PageRevisionInterface extends RevisionInterface, InstanceProviderInterface
{
}