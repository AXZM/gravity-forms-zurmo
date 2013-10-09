<?php namespace GravityForms;

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


    //Returns the physical path of the plugin's root folder
    protected static function views_path()
    {
        $folder = dirname(dirname(dirname(__FILE__) . '/'));
        return $folder . '/views/';
    }

}