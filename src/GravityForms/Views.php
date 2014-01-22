<?php namespace GravityForms;

use Zurmo\API as API;
use GFZurmo;
/**
 * Performs all functions related to rendering the view like registering the
 * registering the settings page, and custom options to individual forms
 *
 * @package   Gravityforms / Zurmo Addon
 * @author    Ross Edman / Tyler Ferguson <info@axzm.com>
 * @license   GPL-2.0+
 * @link      http://axzm.com
 * @copyright 2013 AXZM
 */

class Views {

    /**
     * Render Settings Page
     *
     * load all meta fields for Zurmo install
     */
    public static function settings()
    {   
        include_once(self::views_path() . 'settings.php');
    }   


    /**
     * Render Form Option
     *
     * render checkbox on each form so Zurmo can be activated
     */
    public static function checkbox() 
    {
        // clean contents
        ob_start();
            gform_tooltip("form_zurmo");
            $tooltip = ob_get_contents();
        ob_end_clean();

        // trim tooltip
        $tooltip = trim(rtrim($tooltip)).' ';

        // include the markup
        include_once(self::views_path() . 'checkbox.php');
    } 
	
	/**
     * Render Form Option
     *
     * render users selector on each form so Zurmo can be submitted to a specific user
     */
    public static function users() 
    {
    	
    	$zurmo = new API\Connect;
    $api = GFZurmo::api($zurmo);

    if($api)
    {
		if ( $api['token'] ) 
		{
			/*
            	|-------------------------------------
            	| Set headers
            	|-------------------------------------
            	*/
                $headers = array(
                    'Accept: application/json',
                    'ZURMO_SESSION_ID: ' . $api['sessionId'],
                    'ZURMO_TOKEN: ' . $api['token'],
                    'ZURMO_API_REQUEST_TYPE: REST',
                );
                
                
                 //global highrise settings
        		$settings = get_option("gf_zurmo_settings");
        		$url = $settings['url'];
                /*
            	|-------------------------------------
            	| Make API call
            	|-------------------------------------
            	*/
                	$response = API\RestHelper::call($url.'/app/index.php/users/user/api/list/', 'GET', $headers);
                	//echo var_dump($response);
                 $response = json_decode($response, true);

            	/*
            	|-------------------------
            	| Handle Response
            	|-------------------------
            	*/    
                	if ($response['status'] == 'SUCCESS')
                	{
                    	$users = $response['data']['items'];
                    	//return $contact;
                    	//Do something with contact data
                	}
                	else
                	{
                    	// Error
                    	$users = $response['errors'];
                    	//return $errors;
                    	// Do something with errors, show them to user
                	}
        
     

		
        // clean contents
        ob_start();
            gform_tooltip("form_users");
            $tooltip = ob_get_contents();
        ob_end_clean();

        // trim tooltip
        $tooltip = trim(rtrim($tooltip)).' ';

        // include the markup
        include_once(self::views_path() . 'users.php');
    	}
    }
    } 

    /**
     * Views Directory Path
     *
     * @return the directory path to the views folder
     */
    protected static function views_path()
    {
        $folder = dirname(dirname(dirname(__FILE__) . '/'));
        return $folder . '/views/';
    }

}