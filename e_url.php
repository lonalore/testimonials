<?php
/**
 * Testimonials plugin for e107 v2.
 *
 * @file
 * v2.x Standard  - Simple mod-rewrite module.
 */

if(!defined('e107_INIT'))
{
	exit;
}


/**
 * Class testimonials_url.
 *
 * plugin-folder + '_url'
 */
class testimonials_url
{

	function config()
	{
		$config = array();

		$config['index'] = array(
			// Matched against url, and if true, redirected to 'redirect' below.
			'regex'    => '^testimonials/?$',
			// Used by e107::url(); to create a url from the db table.
			'sef'      => 'testimonials',
			// File-path of what to load when the regex returns true.
			'redirect' => '{e_PLUGIN}testimonials/testimonials.php',
		);

		return $config;
	}


}