<?php
namespace Auth\Service;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class HashService implements HashServiceInterface, FactoryInterface
{
    private $_config = array(
        'salt_pattern' => '1,3,5,9,14,15,20,21,28,30',
	    'hash_method'   => 'sha1',
    );

    /**
	 * @return the $adapter
	 */
	public function getAdapter() {
		return $this->adapter;
	}

	/**
	 * @param field_type $adapter
	 */
	public function setAdapter(\Zend\Db\Adapter\Adapter $adapter) {
		$this->adapter = $adapter;
	}

	
	public function createService (ServiceLocatorInterface $serviceLocator)
    {
        return new self();
    }
    
    
    public function __construct(){
        $this->_config['salt_pattern'] = explode(',',$this->_config['salt_pattern']);
    }
    
    
    /**
     * Creates a hashed password from a plaintext password, inserting salt
     * based on the configured salt pattern.
     *
     * @param   string  plaintext password
     * @return  string  hashed password string
     */
    public function hash_password($password, $salt = FALSE)
    {
    	if ($salt === FALSE)
    	{
    		// Create a salt seed, same length as the number of offsets in the pattern
    		$salt = substr($this->hash(uniqid(NULL, TRUE)), 0, count($this->_config['salt_pattern']));
    	}
    
    	// Password hash that the salt will be inserted into
    	$hash = $this->hash($salt.$password);
    
    	// Change salt to an array
    	$salt = str_split($salt, 1);
    
    	// Returned password
    	$password = '';
    
    	// Used to calculate the length of splits
    	$last_offset = 0;
    
    	foreach ($this->_config['salt_pattern'] as $offset)
    	{
    		// Split a new part of the hash off
    		$part = substr($hash, 0, $offset - $last_offset);
    
    		// Cut the current part out of the hash
    		$hash = substr($hash, $offset - $last_offset);
    
    		// Add the part to the password, appending the salt character
    		$password .= $part.array_shift($salt);
    
    		// Set the last offset to the current offset
    		$last_offset = $offset;
    	}
    
    	// Return the password, with the remaining hash appended
    	return $password.$hash;
    }
    
    /**
     * Perform a hash, using the configured method.
     *
     * @param   string  string to hash
     * @return  string
     */
    private function hash($str)
    {
    	return hash($this->_config['hash_method'], $str);
    }
    
    /**
     * Finds the salt from a password, based on the configured salt pattern.
     *
     * @param   string  hashed password
     * @return  string
     */
    public function find_salt($password)
    {
    	$salt = '';
    
    	foreach ($this->_config['salt_pattern'] as $i => $offset)
    	{
    		// Find salt characters, take a good long look...
    		$salt .= substr($password, $offset + $i, 1);
    	}
    
    	return $salt;
    }
}

?>