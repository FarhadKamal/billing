<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	var $currency_decimal;

	function __construct()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");



		// The cents separater is a hidden config variable.  If it isn't available default to '.'
		if ($this->config->item('currency_decimal') == '') {
			$this->config->set_item('currency_decimal', '.');
		}

		// a list of unlocked (ie: not password protected) controllers.  We assume
		// controllers are locked if they aren't explicitly on this list
		$unlocked = array('mainindex', 'login', 'index');



		if (!$this->userauth->is_logged_in() and !in_array(strtolower(get_class($this)), $unlocked)) {

			redirect('mainindex');
		}




		//$this->output->enable_profiler($this->config->item('show_profiler'));
	}
}
