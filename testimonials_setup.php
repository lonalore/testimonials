<?php
/**
 * Testimonials plugin for e107 v2.
 *
 * @file
 * Custom install/uninstall/update routines.
 */

if(!defined('e107_INIT'))
{
	exit;
}


/**
 * Class testimonials_setup.
 */
class testimonials_setup
{

	/**
	 * This function is called before plugin table has been created
	 * by the testimonials_sql.php file.
	 *
	 * @param array $var
	 */
	function install_pre($var)
	{
		$db = e107::getDb('testimonials');

		// Check old database tables exist.
		if($db->gen("SELECT * FROM #testimonials_conf WHERE 1"))
		{
			// Retrieve testimonial items from old table.
			$db->gen("SELECT * FROM #testimonials WHERE 1");

			$old_records = array();
			while($row = $db->fetch())
			{
				$old_records[] = $row;
			}

			if(!empty($old_records))
			{
				$_SESSION['OLD_TESTIMONIALS'] = $old_records;
			}

			$db->gen("DROP TABLE #testimonials");
			$db->gen("DROP TABLE #testimonials_conf");
		}
	}


	/**
	 * This function is called after plugin table has been created
	 * by the testimonials_sql.php file.
	 *
	 * @param array $var
	 */
	function install_post($var)
	{
		if(isset($_SESSION['OLD_TESTIMONIALS']))
		{
			$db = e107::getDb('testimonials');

			$old_records = $_SESSION['OLD_TESTIMONIALS'];

			$order = 0;
			foreach($old_records as $old_record)
			{
				$insert = array(
					'tm_id'        => 0,
					'tm_name'      => '0.' . $old_record['name'],
					'tm_url'       => $old_record['homepage'],
					'tm_message'   => $old_record['text'],
					'tm_datestamp' => time(),
					'tm_blocked'   => ($old_record['allowed'] == 'yes' ? 0 : 1),
					'tm_ip'        => '',
					'tm_order'     => $order,
				);

				$db->insert("testimonials", $insert);
				$order++;
			}

			unset($_SESSION['OLD_TESTIMONIALS']);
		}
	}


	function uninstall_options()
	{
	}


	function uninstall_post($var)
	{
	}


	/**
	 * Trigger an upgrade alert or not.
	 *
	 * @param array $var
	 *
	 * @return bool
	 *  True to trigger an upgrade alert, and false to not.
	 */
	function upgrade_required($var)
	{
		$db = e107::getDb('testimonials');

		// Check old database tables exist.
		if($db->gen("SELECT * FROM #testimonials_conf WHERE 1"))
		{
			return true;
		}
	}


	function upgrade_pre($var)
	{
		$db = e107::getDb('testimonials');

		// Check old database tables exist.
		if($db->gen("SELECT * FROM #testimonials_conf WHERE 1"))
		{
			// Retrieve testimonial items from old table.
			$db->gen("SELECT * FROM #testimonials WHERE 1");
			$old_records = array();
			while($row = $db->fetch())
			{
				$old_records[] = $row;
			}

			// Drop old tables.
			$db->gen("DROP TABLE #testimonials");
			$db->gen("DROP TABLE #testimonials_conf");

			// Create new table.
			$db->gen("CREATE TABLE #testimonials (
				tm_id int(11) unsigned NOT NULL auto_increment,
				tm_name varchar(50) NOT NULL DEFAULT '',
				tm_url varchar(255) NOT NULL DEFAULT '',
				tm_message text NOT NULL,
				tm_datestamp int(10) unsigned NOT NULL DEFAULT '0',
				tm_blocked tinyint(3) unsigned NOT NULL DEFAULT '0',
				tm_ip varchar(45) NOT NULL DEFAULT '',
				tm_order tinyint(3) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY  (tm_id)
				) ENGINE=MyISAM;");

			// Insert testimonial items into new table.
			$order = 0;
			foreach($old_records as $old_record)
			{
				$insert = array(
					'tm_id'        => 0,
					'tm_name'      => '0.' . $old_record['name'],
					'tm_url'       => $old_record['homepage'],
					'tm_message'   => $old_record['text'],
					'tm_datestamp' => time(),
					'tm_blocked'   => ($old_record['allowed'] == 'yes' ? 0 : 1),
					'tm_ip'        => '',
					'tm_order'     => $order,
				);

				$db->insert("testimonials", $insert);
				$order++;
			}
		}
	}


	function upgrade_post($var)
	{
	}
}
