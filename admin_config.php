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
		'items'  => array(
			'controller' => 'testimonials_admin_items_ui',
			'path'       => null,
			'ui'         => 'testimonials_admin_items_form_ui',
			'uipath'     => null
		),
		'main' => array(
			'controller' => 'testimonials_admin_ui',
			'path'       => null,
		),
	);

	protected $adminMenu = array(
		'items/list'   => array(
			'caption' => LAN_TESTIMONIALS_ADMIN_07,
			'perm'    => 'P',
		),
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


/**
 * Class testimonials_admin_items_ui.
 */
class testimonials_admin_items_ui extends e_admin_ui
{

	protected $pluginTitle = LAN_PLUGIN_TESTIMONIALS_NAME;
	protected $pluginName  = 'testimonials';
	protected $eventName   = 'testimonials_message';
	protected $table       = "testimonials";
	protected $pid         = "tm_id";
	protected $perPage     = 0; //no limit
	protected $batchDelete = false;
	protected $sortField   = 'tm_order';
	protected $listOrder   = "tm_order ASC";

	protected $fields = array(
		'checkboxes' => array(
			'title'   => '',
			'type'    => null,
			'width'   => '5%',
			'forced'  => true,
			'thclass' => 'center',
			'class'   => 'center',
		),
		'tm_id'     => array(
			'title'    => LAN_TESTIMONIALS_ADMIN_09,
			'type'     => 'number',
			'width'    => '5%',
			'forced'   => true,
			'readonly' => true,
			'thclass'  => 'center',
			'class'    => 'center',
		),
		'tm_name'  => array(
			'title'    => LAN_TESTIMONIALS_ADMIN_10,
			'type'     => 'text',
			'inline'   => false,
			'width'    => 'auto',
			'thclass'  => 'left',
			'readonly' => true,
			'validate' => true,
		),
		'tm_url'   => array(
			'title'     => LAN_TESTIMONIALS_ADMIN_11,
			'type'     => 'text',
			'inline'   => true,
			'width'    => 'auto',
			'thclass'  => 'left',
			'readonly' => false,
		),
		'tm_blocked' => array(
			'title'      => LAN_TESTIMONIALS_ADMIN_12,
			'type'       => 'dropdown',
			'width'      => 'auto',
			'readonly'   => false,
			'inline'     => true,
			'batch'      => true,
			'filter'     => true,
			'writeParms' => array(
				0 => LAN_TESTIMONIALS_ADMIN_13,
				1 => LAN_TESTIMONIALS_ADMIN_14,
			),
			'readParms'  => array(
				0 => LAN_TESTIMONIALS_ADMIN_13,
				1 => LAN_TESTIMONIALS_ADMIN_14,
			),
			'thclass'    => 'center',
			'class'      => 'center',
		),
		'tm_message'   => array(
			'title'     => LAN_TESTIMONIALS_ADMIN_15,
			'type'      => 'textarea',
			'inline'    => true,
			'width'     => 'auto',
			'thclass'   => 'left',
			'readParms' => 'expand=...&truncate=150&bb=1',
			'readonly'  => false,
			'validate' => true,
		),
		'tm_order'  => array(
			'title'   => LAN_TESTIMONIALS_ADMIN_16,
			'type'    => 'text',
			'width'   => 'auto',
			'thclass' => 'center',
			'class'   => 'center',
		),
		'options'    => array(
			'title'   => LAN_TESTIMONIALS_ADMIN_17,
			'type'    => null,
			'width'   => '10%',
			'forced'  => true,
			'thclass' => 'center last',
			'class'   => 'center',
			'sort'    => true,
		),
	);

	protected $fieldpref = array(
		'checkboxes',
		'tm_id',
		'tm_name',
		'tm_url',
		'tm_blocked',
		'tm_message',
		'tm_order',
		'options',
	);

	function init()
	{
	}

	public function beforeCreate($data)
	{
		if(empty($data['tm_order']))
		{
			$c = e107::getDb()->count('testimonials');
			$data['tm_order'] = $c ? $c : 0;
		}

		return $data;
	}


	public function beforeUpdate($data, $old_data, $id)
	{
	}

}


/**
 * Class testimonials_admin_items_form_ui.
 */
class testimonials_admin_items_form_ui extends e_admin_form_ui
{

}



new testimonials_admin();

require_once(e_ADMIN . "auth.php");
e107::getAdminUI()->runPage();
require_once(e_ADMIN . "footer.php");
exit;
