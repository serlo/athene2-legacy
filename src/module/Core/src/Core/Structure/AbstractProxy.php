<?php
namespace Core\Structure;

abstract class AbstractProxy
{

    protected $source;
    
    /**
	 * @return the $source
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param field_type $source
	 */
	public function setSource($source) {
		$this->source = $source;
	}

	public function __construct ($source)
    {
        $this->source = $source;
    }
}