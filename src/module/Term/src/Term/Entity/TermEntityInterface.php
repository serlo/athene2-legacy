<?php
namespace Term\Entity;

interface TermEntityInterface
{

    /**
     *
     * @return int
     *         $id
     */
    public function getId ();

    /**
     *
     * @param int $id            
     * @return $this
     */
    public function setId ($id);

    /**
     *
     * @return \Language\Entity\LanguageInterface
     *         $language
     */
    public function getLanguage ();

    /**
     *
     * @return field_type
     *         $name
     */
    public function getName ();

    /**
     *
     * @return field_type
     *         $slug
     */
    public function getSlug ();

    /**
     *
     * @param int $language            
     * @return $this
     */
    public function setLanguage ($language);

    /**
     *
     * @param string $name            
     * @return $this
     */
    public function setName ($name);

    /**
     *
     * @param string $slug            
     * @return $this
     */
    public function setSlug ($slug);

    /**
     * Returns
     * an
     * array
     * copy
     *
     * @return array
     */
    public function getArrayCopy ();
}