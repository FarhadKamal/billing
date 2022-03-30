<?php
class Mainindex  extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('/fp_mainModel/Mainmodel');
		$this->load->library('session');

		$this->loggedin = False;
		$this->load->database();
		$this->load->model('/fp_usersLoginModel/loginmodel');
	}

	function Index($type = 1)
	{
		//$data['test'] ="";
		//$this->load->view('/fp_mainFormView/main',$data);

		if ($type == 2)
			echo " Password Changed Successful please login again.";

		// set validation properties
		$this->load->library('validation');
		$this->_set_fields();
		$data['error'] = '';
		$data['action'] = site_url('login/login/verify');
		// load view 
		$data['page'] = '/fp_usersLoginView/login_form'; //add page name as a parameter
		$this->load->view('/fp_usersLoginView/index', $data);
	}

	//Set Fields
	function _set_fields()
	{
		$fields['password'] = 'password';
		$fields['user_id'] = 'user_id';
		$this->validation->set_fields($fields);
	}
}
