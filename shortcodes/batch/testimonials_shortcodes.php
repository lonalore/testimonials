<?php
/**
 * Testimonials plugin for e107 v2.
 *
 * @file
 * Class installation to define shortcodes.
 */

if(!defined('e107_INIT'))
{
	exit;
}


/**
 * Class testimonials_shortcodes.
 */
class testimonials_shortcodes extends e_shortcode
{

	private $plugPrefs = array();


	function __construct()
	{
		$this->plugPrefs = e107::getPlugConfig('testimonials')->getPref();
	}


	function sc_testimonials_indicators()
	{
		$indicators = array();
		if (isset($this->var['count']) && (int) $this->var['count'] > 0) {
			for ($i = 0; $i < $this->var['count']; $i++) {
				$indicators[] = '<li data-target="#quote-carousel" data-slide-to="' . $i . '"' . ($i === 0 ? ' class="active"' : '') . '></li>';
			}
		}
		return '<ol class="carousel-indicators">' . implode('', $indicators) . '</ol>';
	}


	function sc_testimonials_message()
	{
		return $this->var['tm_message'];
	}


	function sc_testimonials_author()
	{
		if (!empty($this->var['tm_url'])) {
			return '<a href="' . $this->var['tm_url'] . '" target="_blank">' . $this->var['user_name'] . '</a>';
		}

		$uid = (int) $this->var['user_id'];
		if($uid === 0)
		{
			return $this->var['user_name'];
		}

		// TODO: SEF URL...
		return '<a href="' . e_HTTP . 'user.php?id.' . $uid . '">' . $this->var['user_name'] . '</a>';
	}
}
