<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions
{

	function __construct()
	{
		parent::__construct();
	}



	function show_404($page = '')
	{
		$heading = "404 Page Not Found";
		$message = "The page you requested was not found.";

		log_message('error', '404 Page Not Found --> ' . $page);
		echo $this->show_error($heading, $message, 'error_404');
		exit;
	}

	function show_error($heading, $message, $template = 'error_general')
	{
		$message = '<p>' . implode('</p><p>', (!is_array($message)) ? array($message) : $message) . '</p>';

		if (ob_get_level() > $this->ob_level + 1) {
			ob_end_flush();
		}
		ob_start();

		include(base_url() . 'errors/' . $template . EXT);
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	function show_php_error($severity, $message, $filepath, $line)
	{

		//echo "<br>You are not authorized to view this page";
		$severity = (!isset($this->levels[$severity])) ? $severity : $this->levels[$severity];

		$filepath = str_replace("\\", "/", $filepath);

		// For safety reasons we do not show the full file path
		if (FALSE !== strpos($filepath, '/')) {
			$x = explode('/', $filepath);
			$filepath = $x[count($x) - 2] . '/' . end($x);
		}

		if (ob_get_level() > $this->ob_level + 1) {
			ob_end_flush();
		}
		ob_start();
		include(APPPATH . 'errors/error_php' . EXT);
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
}
