<?php
/**
 * Testimonials plugin for e107 v2.
 *
 * @file
 * Class to render Testimonials menu.
 */

if(!defined('e107_INIT'))
{
	exit;
}

// [PLUGINS]/testimonials/languages/[LANGUAGE]/[LANGUAGE]_front.php
e107::lan('testimonials', false, true);


class testimonials_menu
{

	/**
	 * Store plugin preferences.
	 *
	 * @var mixed|null
	 */
	private $plugPrefs = null;


	/**
	 * Store testimonial items.
	 *
	 * @var array
	 */
	private $testimonials = array();


	/**
	 * Constructor.
	 */
	function __constructor()
	{
		// Get plugin preferences.
		$this->plugPrefs = e107::getPlugConfig('testimonials')->getPref();
	}


	/**
	 * Render testimonials menu.
	 */
	function renderMenu()
	{
		$cache = e107::getConfig();

		if(!$text = $cache->get("testimonials"))
		{
			$this->getItems();
			$template = e107::getTemplate('testimonials');
			$sc = e107::getScBatch('testimonials', true);
			$tp = e107::getParser();

			$sc->setVars(array('count' => count($this->testimonials)));
			$text = $tp->parseTemplate($template['menu_header'], true, $sc);
			foreach($this->testimonials as $val)
			{
				$sc->setVars($val);
				$text .= $tp->parseTemplate($template['menu_body'], true, $sc);
			}
			$text .= $tp->parseTemplate($template['menu_footer'], true, $sc);

			$cache->set("testimonials", $text);
		}

		e107::getRender()->tablerender(LAN_NCB_FRONT_01, $text);
		unset($text);
	}


	/**
	 * Select messages from database.
	 */
	function getItems()
	{
		$db = e107::getDb('testimonials');

		$query = 'SELECT t.*, u.user_id, u.user_name, u.user_image FROM #testimonials AS t ';
		$query .= 'LEFT JOIN #user AS u ON SUBSTRING_INDEX(t.tm_name,".",1) = u.user_id ';
		$query .= 'ORDER BY t.tm_datestamp DESC ';
		$query .= 'LIMIT 0, ' . (int) $this->plugPrefs['tm_menu_items'];

		$db->gen($query);

		while($row = $db->fetch())
		{
			$item = $row;
			// If the author is not a registered user, we have to get the nickname.
			if((int) $row['user_id'] === 0)
			{
				list($tm_uid, $tm_nick) = explode(".", $row['tm_name'], 2);
				$item['user_name'] = $tm_nick;
				$item['user_image'] = '';
			}

			$this->testimonials[] = $item;
		}
	}
}