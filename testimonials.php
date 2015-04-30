<?php
/**
 * Testimonials plugin for e107 v2.
 *
 * @file
 * Render a form page to submit messages.
 */

if(!defined('e107_INIT'))
{
	require_once('../../class2.php');
}

if(!e107::isInstalled('testimonials'))
{
	header('Location: ' . e_BASE . 'index.php');
	exit;
}

// [PLUGINS]/testimonials/languages/[LANGUAGE]/[LANGUAGE]_front.php
e107::lan('testimonials', false, true);

require_once(HEADERF);


class testimonials
{

	/**
	 * Store plugin preferences.
	 *
	 * @var mixed|null
	 */
	private $plugPrefs = null;

	/**
	 * Access to submit new testimonial item is granted or not.
	 *
	 * @var bool
	 */
	private $access = false;

	/**
	 * Constructor.
	 */
	function __construct()
	{
		// Get plugin preferences.
		$this->plugPrefs = e107::getPlugConfig('testimonials')->getPref();
		// Check user access.
		$this->access = check_class($this->plugPrefs['tm_submit_role']);

		if($this->access)
		{
			// If the form has been submitted.
			if(isset($_POST['tm_submit']) && (int) $_POST['tm_submit'] === 1)
			{
				// Process submitted form details.
				if($this->formValidate())
				{
					$this->formSubmit();
				}
			}

			$this->renderPage();
		}
		else
		{
			$this->renderErrorPage();
		}
	}


	/**
	 * Validate form details.
	 */
	function formValidate()
	{
		$db = e107::getDb();
		$tp = e107::getParser();
		$mes = e107::getMessage();

		$error = true;
		$messages = array();

		if(!isset($_POST['tm_name']) || empty($_POST['tm_name']))
		{
			$messages[] = LAN_TESTIMONIALS_08;
			$error = false;
		}

		if(!isset($_POST['tm_message']) || empty($_POST['tm_message']))
		{
			$messages[] = LAN_TESTIMONIALS_09;
			$error = false;
		}

		// If user is Anonymous, and nickname is exits in user table.
		if(!USER && (isset($_POST['tm_name'])))
		{
			// Check nickname's ownership.
			$nick = trim(preg_replace("#\[.*\]#si", "", $tp->toDB($_POST['tm_name'])));
			if($db->select("user", "*", "user_name='$nick' "))
			{
				$messages[] = LAN_TESTIMONIALS_10;
				$error = false;
			}
		}

		if(!$error)
		{
			foreach($messages as $message)
			{
				$mes->addError($message);
			}
		}

		return $error;
	}


	/**
	 * Insert form details into database.
	 */
	function formSubmit()
	{
		$db = e107::getDb();
		$tp = e107::getParser();
		$ip = e107::getIPHandler()->getIP(false);
		$mes = e107::getMessage();

		if(!USER && isset($_POST['nickname']))
		{
			$tm_name = 0 . '.' . trim(preg_replace("#\[.*\]#si", "", $tp->toDB($_POST['tm_name'])));
		}
		else
		{
			$tm_name = USERID . '.' . trim(preg_replace("#\[.*\]#si", "", $tp->toDB($_POST['tm_name'])));
		}

		$tm_url = trim(preg_replace("#\[.*\]#si", "", $tp->toDB($_POST['tm_url'])));

		$tm_message = $_POST['tm_message'];
		$tm_message = preg_replace("#\[.*?\](.*?)\[/.*?\]#s", "\\1", $tm_message);

		$insert = array(
			'tm_id'        => 0,
			'tm_name'      => $tm_name,
			'tm_url'       => $tm_url,
			'tm_message'   => $tm_message,
			'tm_datestamp' => time(),
			'tm_blocked'   => (int) $this->plugPrefs['tm_approval'],
			'tm_ip'        => $ip,
			'tm_order'     => 0,
		);

		$result = $db->insert("testimonials", $insert);
		if($result)
		{
			// Get last inserted id.
			$insert['tm_id'] = $result;
			$event = e107::getEvent();
			// Trigger event.
			$event->trigger('testimonials_message_insert', $insert);

			$mes->addSuccess(LAN_TESTIMONIALS_11);

			unset($_POST);
		}
	}


	/**
	 * Render testimonial submit form.
	 */
	function renderPage()
	{
		$mes = e107::getMessage();
		$frm = e107::getForm();
		$action = e107::url('testimonials', 'index');

		$form = $frm->open('testimonials', 'post', $action, array(
			'class' => 'formclass',
			'id'    => 'testimonials',
		));

		$tm_name = (isset($_POST['tm_name']) ? $_POST['tm_name'] : (USER ? USERNAME : ''));

		$form .= '<div class="form-group">';
		$form .= $frm->text('tm_name', $tm_name, 100, array(
			'id'          => 'tm_name',
			'class'       => 'form-control tbox span12',
			'placeholder' => LAN_TESTIMONIALS_03,
		));
		$form .= '</div>';

		$tm_url = (isset($_POST['tm_url']) ? $_POST['tm_url'] : '');

		$form .= '<div class="form-group">';
		$form .= $frm->text('tm_url', $tm_url, 100, array(
			'id'          => 'tm_url',
			'class'       => 'form-control tbox span12',
			'placeholder' => LAN_TESTIMONIALS_07,
		));
		$form .= '</div>';

		$tm_message = (isset($_POST['tm_message']) ? $_POST['tm_message'] : '');

		$form .= '<div class="form-group">';
		$form .= $frm->textarea('tm_message', $tm_message, 3, 80, array(
			'id'          => 'tm_message',
			'class'       => 'form-control tbox span12',
			'placeholder' => LAN_TESTIMONIALS_04,
		));
		$form .= '</div>';


		$form .= '<div class="form-group">';
		$form .= $frm->button('tm_submit', 1, 'submit', LAN_TESTIMONIALS_05, array(
			'id' => 'tm_submit',
		));
		$form .= '</div>';


		$form .= $frm->close();

		$messages = $mes->render();
		e107::getRender()->tablerender(LAN_TESTIMONIALS_06, $messages . $form);
		unset($text);
	}


	/**
	 * Render Access Denied page.
	 */
	function renderErrorPage()
	{
		$mes = e107::getMessage();
		$mes->addError(LAN_TESTIMONIALS_02);
		e107::getRender()->tablerender(LAN_ERROR, $mes->render());
		unset($text);
	}

}


new testimonials();

require_once(FOOTERF);
exit;
