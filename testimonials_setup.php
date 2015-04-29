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
				);

				$db->insert("testimonials", $insert);
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
	}


	function upgrade_pre($var)
	{
	}


	function upgrade_post($var)
	{
	}
}
