<?php namespace GravityForms;

/**
 * Push function, this takes the Gravity Forms fields and maps them
 * to Zurmo inputs and pushed the form to the CRM
 *
 * @package   Gravityforms / Zurmo Addon
 * @author    Ross Edman / Tyler Ferguson <info@axzm.com>
 * @license   GPL-2.0+
 * @link      http://axzm.com
 * @copyright 2013 AXZM
 */

class Push {

	private $credentials;
	public  $zurmo;
	public  $data;


	public function __construct(Zurmo\API\Connect $zurmo)
	{
		// get credentials to connect to Zurmo
		$this->credentials = get_option("gf_zurmo_settings");
		$this->zurmo = $zurmo;

		// check credentials are assigned
        if( !empty($this->credentials["url"]) && 
        	!empty($this->credentials["password"]) && 
        	!empty($this->credentials["username"]) )
        {
            $zurmo->setup(
            	$settings["url"], 
            	$settings["username"], 
            	$settings["password"]
            );
        }
        else
        {
        	// throw exception
        }
	}


	/**
	 * Form Conditional Checkbox
	 *
	 * @return boolean value based on checkbox on per form basis
	 */
	public static function form_enabled()
	{
		if( !empty( $form_meta['enableZurmo'] ) )
		{
			$this->form_status = true;
		}
	}
	
}