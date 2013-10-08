<?php namespace Zurmo\GravityForms;

/**
 * Perform basic checks to see if GravityForms is initialized and installed
 * abstracted into its own class for ease of use and separation of concerns
 *
 * @package   Gravityforms / Zurmo Addon
 * @author    Ross Edman / Tyler Ferguson <info@axzm.com>
 * @license   GPL-2.0+
 * @link      http://axzm.com
 * @copyright 2013 AXZM
 */

class Check {

    /**
     * Check If Gravity Forms Is Installed
     *
     * @var $installed - an integer that describes if Gravity Forms is installed and to what degree
     * @var $message - return a message that can inform the end user
     */
    public static function install($asd = '', $echo = true) 
    {
		global $pagenow, $page; $message = '';

		$installed = 0;
		$name = \GFZurmo::$name;

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
     * Has Access
     *
     * limit access to plugin except for administrative users
     * 
     * @var $required_permission
     */
	public static function access($required_permission)
	{
        $has_members_plugin = function_exists('members_get_capabilities');

        $has_access = $has_members_plugin ? current_user_can($required_permission) : current_user_can("level_7");

        if($has_access)
            return $has_members_plugin ? $required_permission : "level_7";
        else
            return false;
    }


    /**
     * Check Version
     *
     * check if version of Gravity Forms in use is supported
     * 
     * @var $required_permission
     */
    public static function version()
    {

        if(class_exists("\GFCommon"))
        {
            $is_correct_version = version_compare(GFCommon::$version, self::$min_gravityforms_version, ">=");
            return $is_correct_version;
        }

        else
        {
            return false;
        }

    }


    /**
     * Is Gravity Forms Supported?
     */
	public static function plugin()
    {

        if( !self::version() )
        {
            $message = sprintf( __("%sGravity Forms%s is required.%s") );
            self::display_plugin_message($message, true);
        }

    }

}