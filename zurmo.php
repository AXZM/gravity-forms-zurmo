<?php
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

	private static $name = "Gravity Forms Zurmo Add-On";
	private static $path = "gravity-forms-zurmo/zurmo.php";
	private static $url = "http://www.gravityforms.com";
	private static $slug = "gravity-forms-zurmo";
	private static $version = "0.1.0";
	private static $min_gravityforms_version = "1.3.9";


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
			add_action("admin_notices", array('GFZurmo', 'is_gravity_forms_installed'), 10);
		}

		if(self::is_gravity_forms_installed(false, false) !== 1)
		{
			add_action('after_plugin_row_' . self::$path, array('GFZurmo', 'plugin_row') );
           	return;
        }

        if(is_admin())
        {
            //creates a new Settings page on Gravity Forms' settings screen
            if(self::has_access("gravityforms_zurmo"))
            {
            	RGForms::add_settings_page("Zurmo", array("GFZurmo", "settings_page"), "");
            }
        }

        //creates the subnav left menu
        add_filter("gform_addon_navigation", array('GFZurmo', 'create_menu'), 20);

        if(self::is_zurmo_page())
        {
            //enqueueing sack for AJAX requests
            wp_enqueue_script(array("sack", "jquery-ui-tooltip"));
            wp_enqueue_style('gravityforms-admin', GFCommon::get_base_url().'/css/admin.css');
        }
        else if(in_array(RG_CURRENT_PAGE, array("admin-ajax.php")))
        {
            add_action('wp_ajax_rg_update_feed_active', array('GFZurmo', 'update_feed_active'));
            add_action('wp_ajax_gf_select_highrise_form', array('GFZurmo', 'select_highrise_form'));

        } 
        elseif(in_array(RG_CURRENT_PAGE, array('admin.php'))) 
        {
        	add_action('admin_head', array('GFZurmo', 'show_highrise_status'));
        }
        else
        {
            //handling post submission.
            add_action("gform_entry_created", array('GFZurmo', 'push'), 10, 2);
        }

        add_action("gform_properties_settings", array('GFZurmo', 'add_form_option_js'), 800);

		add_filter('gform_tooltips', array('GFZurmo', 'add_form_option_tooltip'));

		add_filter("gform_confirmation", array('GFZurmo', 'confirmation_error'));

		add_action('gform_entry_info', array('GFZurmo', 'entry_info_link_to_highrise'), 10, 2);
    }


    /**
     * Check If Gravity Forms Is Installed
     *
     * @var $installed - an integer that describes if Gravity Forms is installed and to what degree
     * @var $message - return a message that can inform the end user
     */
    public static function is_gravity_forms_installed($asd = '', $echo = true) 
    {
		global $pagenow, $page; $message = '';

		$installed = 0;
		$name = self::$name;

		if(!class_exists('RGForms')) 
		{

			if(file_exists(WP_PLUGIN_DIR.'/gravityforms/gravityforms.php')) 
			{
				/**
				 * Gravity Forms Is Inactive
				 *
				 * @var $installed = 2
				 */
				$installed = 2;
				$message .= __(sprintf('%sGravity Forms is installed but not active. %sActivate Gravity Forms%s to use the %s plugin.%s', '<p>', '<strong><a href="'.wp_nonce_url(admin_url('plugins.php?action=activate&plugin=gravityforms/gravityforms.php'), 'activate-plugin_gravityforms/gravityforms.php').'">', '</a></strong>', $name,'</p>'), 'gravity-forms-highrise');
			} 
			else 
			{
				/**
				 * Gravity Forms Is Not Installed
				 *
				 * @var $installed = 0
				 */
				$installed = 0;
				$message .= 'You do not have the Gravity Forms plugin installed';
			}

			/**
			 * Deliver Message
			 *
			 * @var $message depending on if $install = 0 or 2
			 */
			if(!empty($message) && $echo) 
			{
				echo '<div id="message" class="updated">'.$message.'</div>';
			}

		} 
		else 
		{
			/**
			 * Gravity Forms Is Installed
			 *
			 * @var $installed = 1
			 */
			$installed = 1;
		}

		return $installed;
	}

    /**
     * Is Gravity Forms Supported?
     */
	public static function plugin_row(){

        if(!self::is_gravityforms_supported())
        {
            $message = sprintf( __("%sGravity Forms%s is required. %sPurchase it today!%s") );
            self::display_plugin_message($message, true);
        }

    }

    //Returns true if the current page is an Feed pages. Returns false if not
    private static function is_zurmo_page()
    {
    	if(empty($_GET["page"])) 
    	{
    		return false; 
    	}

        $current_page = trim(strtolower($_GET["page"]));
        $zurmo_pages = array("gf_zurmo");

        return in_array($current_page, $zurmo_pages);
    }

    /**
     * Has Access
     */
	protected static function has_access($required_permission)
	{
        $has_members_plugin = function_exists('members_get_capabilities');
        $has_access = $has_members_plugin ? current_user_can($required_permission) : current_user_can("level_7");
        if($has_access)
            return $has_members_plugin ? $required_permission : "level_7";
        else
            return false;
    }

}