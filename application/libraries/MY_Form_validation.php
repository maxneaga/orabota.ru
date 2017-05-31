<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    function __construct()
    {
        parent::__construct();
    }


    /**
	 * Alpha - Allow alpha characters and spaces.
	 *
	 * @param	string
	 * @return	bool
	 */
	public function alpha($str)
	{
		return ( ! preg_match("/^([-a-z\s])+$/i", $str)) ? FALSE : TRUE;
	}
}