<?php
/**
 * @file
 * Class installations to handle configuration forms on Admin UI.
 */

require_once('../../class2.php');
if(!getperms('P'))
{
	header('location:' . e_BASE . 'index.php');
	exit;
}

// [PLUGINS]/testimonials/languages/[LANGUAGE]/[LANGUAGE]_admin.php
e107::lan('testimonials', true, true);


/**
 * Class testimonials_admin
 */
class testimonials_admin extends e_admin_dispatcher
{

	protected $modes = array(
		'main' => array(
			'controller' => 'testimonials_admin_ui',
			'path'       => null,
		),
	);

	protected $adminMenu = array(
		'main/prefs' => array(
			'caption' => LAN_PREFS,
			'perm'    => 'P',
		),
	);

	protected $adminMenuAliases = array(
		'main/edit' => 'main/list',
	);

	protected $menuTitle = LAN_PLUGIN_TESTIMONIALS_NAME;
}


/**
 * Class testimonials_admin_ui.
 */
class testimonials_admin_ui extends e_admin_ui
{

	protected $pluginTitle = LAN_PLUGIN_TESTIMONIALS_NAME;
	protected $pluginName  = "testimonials";
	protected $preftabs    = array(
		LAN_TESTIMONIALS_ADMIN_01,
	);
	protected $prefs       = array(
		'tm_menu_items'  => array(
			'title' => LAN_TESTIMONIALS_ADMIN_02,
			'type'  => 'number',
			'data'  => 'int',
			'tab'   => 0,
		),
		'tm_trim'  => array(
			'title' => LAN_TESTIMONIALS_ADMIN_03,
			'type'  => 'number',
			'data'  => 'int',
			'tab'   => 0,
		),
		'tm_submit_role' => array(
			'title'      => LAN_TESTIMONIALS_ADMIN_04,
			'type'       => 'userclass',
			'data'       => 'int',
			'writeParms' => 'classlist=nobody,main,admin,classes',
			'tab'        => 0,
		),
		'tm_use_captcha' => array(
			'title'      => LAN_TESTIMONIALS_ADMIN_05,
			'type'       => 'boolean',
			'writeParms' => 'label=yesno',
			'data'       => 'int',
			'tab'        => 0,
		),
		'tm_approval'    => array(
			'title'      => LAN_TESTIMONIALS_ADMIN_06,
			'type'       => 'boolean',
			'writeParms' => 'label=yesno',
			'data'       => 'int',
			'tab'        => 0,
		),
	);
}


new testimonials_admin();

require_once(e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();
require_once(e_ADMIN . "footer.php");
exit;
