<?php

if(!class_exists("Connect"))
    require_once("src/Zurmo/Connect.php");

if(!class_exists("Check"))
    require_once("src/GravityForms/Check.php");

use Zurmo\GravityForms as GravityForms;
use Zurmo\API as API;

/*
Plugin Name: Gravity Forms Zurmo Add-On
Description: Integrates Gravity Forms with Zurmo allowing form submissions to be automatically sent to your Zurmo account
Version: 0.1.0
Author: AXZM
Author URI: http://www.axzm.com

------------------------------------------------------------------------
Copyright 2013 AXZM

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

add_action('init',  array('GFZurmo', 'init'));

class GFZurmo {

	public static $name        = "Gravity Forms Zurmo Add-On";
	public static $path        = "gravity-forms-zurmo/zurmo.php";
	public static $url 	       = "http://www.gravityforms.com";
	public static $slug        = "gravity-forms-zurmo";
	public static $version     = "0.1.0";
	public static $min_gravityforms_version = "1.3.9";

    /**
     * Iniatilize!
     *
	 * load the plugin and perform all checks to make sure the plugin can operate correctly
	 * Checks to make sure GravityForms is installed, loaded and adds sidebar navigation for admin
	 * area.
     */
    public static function init()
    {
	    global $pagenow;

	    if($pagenow === 'plugins.php') 
	    {
			add_action("admin_notices", array('\Zurmo\GravityForms\Check', 'install'), 10);
		}

		if( \Zurmo\GravityForms\Check::install(false, false) !== 1 )
		{
			add_action('after_plugin_row_' . self::$path, array('\Zurmo\GravityForms\Check', 'plugin') );
           	return;
        }

        if(is_admin())
        {
            //creates a new Settings page on Gravity Forms' settings screen
            if( \Zurmo\GravityForms\Check::access("gravityforms_zurmo") )
            {
            	RGForms::add_settings_page("Zurmo", array("GFZurmo", "settings_page"), "");
            }
        }
    }



    /**
     * Create Side Menu Under Forms
     *
     * adds menu to `Forms` drop down so settings can be accessed
     */
    public static function create_menu($menus)
    {

        // Adding submenu if user has access
		$permission = \Zurmo\GravityForms\Check::access("gravityforms_zurmo");

		if(!empty($permission)) {

			$menus[] = array(
				"name" => "gf_zurmo", 
				"label" => __("Zurmo", "gravityformszurmo"), 
				"callback" =>  array("GFZurmo", "zurmo_page"), 
				"permission" => $permission
			);

		}

	    return $menus;
    }


    /**
     * Create Side Menu Under Forms
     *
     * adds menu to `Forms` drop down so settings can be accessed
     */
    public static function zurmo_page()
    {

        if(isset($_GET["view"]) && $_GET["view"] == "edit") 
        {
            self::edit_page($_GET["id"]);
        } 
        else 
        {
			self::settings_page();
		}

    }


    /**
     * Render Settings Page
     *
     * load all meta fields for Zurmo install
     */
    public static function settings_page()
    {   
        include_once('views/settings-page.php');
    }


    /**
     * API
     *
     * load all meta fields for Zurmo install
     *
     * @var ZurmoAPI dependency injection, pass the whole object in
     */
    private static function get_api( API\Connect $zurmo )
    {
        $api = false;

        //global highrise settings
        $settings = get_option("gf_zurmo_settings");

        if( !empty($settings["url"]) && !empty($settings["password"]) && !empty($settings["username"]) )
        {
            $zurmo->setup($settings["url"], $settings["username"], $settings["password"]);
            $api = $zurmo->login();
        }

        return $api;
    }


    //Returns the url of the plugin's root folder
    protected function get_base_url()
    {
        return plugins_url(null, __FILE__);
    }

    //Returns the physical path of the plugin's root folder
    protected function get_base_path()
    {
        $folder = basename(dirname(__FILE__));
        return WP_PLUGIN_DIR . "/" . $folder;
    }
}