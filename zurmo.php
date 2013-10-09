<?php
require 'vendor/autoload.php';

/**
 * Plugin Name: Gravity Forms Zurmo Add-On
 * Description: Integrates Gravity Forms with Zurmo allowing form submissions to be automatically sent to your Zurmo account
 * Version: 0.1.0
 * Author: AXZM
 * Author URI: http://www.axzm.com
 *
 * @package   Gravityforms / Zurmo Addon
 * @author    Ross Edman / Tyler Ferguson <info@axzm.com>
 * @license   GPL-2.0+
 * @link      http://axzm.com
 * @copyright 2013 AXZM
 *
 * ------------------------------------------------------------------------
 * Copyright 2013 AXZM
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

use Zurmo\API as API;
use Helpers\ErrorHandler as Error;

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
			add_action("admin_notices", array('GravityForms\Check', 'install'), 10);
		}

		if( GravityForms\Check::install(false, false) !== 1 )
		{
			add_action('after_plugin_row_' . self::$path, array('GravityForms\Check', 'plugin') );
           	return;
        }

        if(is_admin())
        {
            //creates a new Settings page on Gravity Forms' settings screen
            if( GravityForms\Check::access("gravityforms_zurmo") )
            {
            	RGForms::add_settings_page("Zurmo", array("GravityForms\Views", "settings"), "");
            }
        }

        /**
         * Action Form Option
         *
         * add checkbox into form settings
         */
        add_action("gform_properties_settings", array('GravityForms\Views', 'checkbox'), 800);
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
     * API
     *
     * load all meta fields for Zurmo install
     *
     * @var ZurmoAPI dependency injection, pass the whole object in
     */
    public static function api( API\Connect $zurmo )
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

}

/**
 * Link It Up
 *
 * hook into the Wordpress init
 */
add_action('init',  array('GFZurmo', 'init'));