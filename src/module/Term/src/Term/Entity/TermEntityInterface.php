<?php
namespace Term\Entity;

interface TermEntityInterface
{
    public function getLanguage();
    public function setLanguage($language);
    public function getName();
    public function setName($name);
    public function getSlug();
    public function setSlug();
}