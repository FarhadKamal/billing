<?php
class Index  extends MY_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->model('/fp_controlPanelModel/controlmodel');

		if (!$this->userauth->is_logged_in()) {

			show_401();
		}
	}


	function AuthChange($uType, $user)
	{

		if ($uType == 'AH') {

			if ($user == 1508) {

				$this->session->set_userdata('authlevel', 7);
				$this->session->set_userdata('username', 'accounthead');
				$this->session->set_userdata('displayname', 'Account Head');
			} else if ($user == 1016) {

				$this->session->set_userdata('authlevel', 7);
				$this->session->set_userdata('username', 'dhkhead');
				$this->session->set_userdata('displayname', 'Dhaka Account Head');
			} else if ($user == 'dhkhead') {
				$this->session->set_userdata('authlevel', 6);
				$this->session->set_userdata('username', 1016);
				$this->session->set_userdata('displayname', 'Abdul  Malek');
			} else {
				$this->session->set_userdata('authlevel', 6);
				$this->session->set_userdata('username', 1508);
				$this->session->set_userdata('displayname', 'Titon Barua');
			}
		}
		redirect('controlpanel', 'location');
	}

	function View()
	{

		$id = $this->session->userdata('userid');
		$this->controlmodel->getSavings($id);
		//$this->load->view('/fp_controlPanelView/main_menu'); //load Control Panel after loggin sucessfull

		$data['userlevel'] = $this->session->userdata('authlevel');
		$pieces = explode("/", base_url());


		$data['AuthChangeLink'] = "http://" . $pieces[2] . "/BILL/index.php/login/index/AuthChange/";
		$data['MISLink'] = "http://" . $pieces[2] . "/MIS/index.php/controlpanel";

		if ($this->session->userdata('authlevel') == 5 or $this->session->userdata('authlevel') == 6) {

			$check_password_strength = $this->controlmodel->check_password_strength();

			if ($check_password_strength == 1) {


				redirect('users/changedpassword/changePassword/2', 'location');
			}
		}

		if ($this->session->userdata('username') == '' or $this->session->userdata('username') == null) {
			redirect('mainindex', 'location');
		} else {
			$data['page'] = '/fp_controlPanelView/controlPanelBody';
			$this->load->view('index', $data); //load Control Panel after loggin sucessfull

		}
	}
}
