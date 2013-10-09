<?php namespace Helpers;

/**
 * Handle all messages within Wordpress
 *
 * @package   Gravityforms / Zurmo Addon
 * @author    Ross Edman / Tyler Ferguson <info@axzm.com>
 * @license   GPL-2.0+
 * @link      http://axzm.com
 * @copyright 2013 AXZM
 */

 class ErrorHandler  {

 	public function __construct($message, $type)
 	{
 		// the basics of every message
 		$this->message = $message;
 		$this->type = $type;
 	}

 }