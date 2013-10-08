<?php
/**
 * Zurmo / Gravity Forms Integration
 *
 * An integration for connecting Zurmo CRM with Gravity Forms.
 *
 * @package   ZurmoForms
 * @author    Ross Edman / Tyler Ferguson <info@axzm.com>
 * @license   GPL-2.0+
 * @link      http://axzm.com
 * @copyright 2013 AXZM
 *
 * @wordpress-plugin
 * Plugin Name: Gravityforms / Zurmo Addon
 * Plugin URI:  http://axzm.com
 * Description: An integration for connecting Zurmo CRM with Gravity Forms.
 * Version:     1.0.0
 * Author:      AXZM
 * Author URI:  http://axzm.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	require_once( plugin_dir_path( __FILE__ ) . 'zurmo-forms.php' );

	register_activation_hook( __FILE__, array( 'ZurmoForms', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'ZurmoForms', 'deactivate' ) );
	
	add_action( 'plugins_loaded', array( 'ZurmoForms', 'get_instance' ) );