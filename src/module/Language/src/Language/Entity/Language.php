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
namespace Language\Entity;

use Doctrine\ORM\Mapping as ORM;

/**    
 * A language.
 * 
 * @ORM\Entity
 * @ORM\Table(name="language")  
 */
class Language implements LanguageInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $code;

    /**
     * @return field_type $code
     */
    public function getCode()
    {
        return $this->code;
    }

	/**
     * @param field_type $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

	public function getId ()
    {
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
        return $this;
    }
}