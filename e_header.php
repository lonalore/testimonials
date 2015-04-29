<?php
/**
 * Testimonials plugin for e107 v2.
 *
 * @file
 * e_header handler
 */

if(!defined('e107_INIT'))
{
	exit;
}


/**
 * Class testimonials_e_header.
 */
class testimonials_e_header
{

	private $plugPrefs = null;

	function __construct()
	{
		$this->plugPrefs = e107::getPlugConfig('testimonials')->getPref();

		self::include_components();
	}

	/**
	 * Include necessary CSS and JS files.
	 *
	 * TODO: check that the testimonials_menu is active on the site or not.
	 */
	function include_components()
	{
		e107::css('testimonials', 'css/testimonials.css');
	}

}


// Class instantiation.
new testimonials_e_header;
