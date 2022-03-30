<?php
class Login extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->loggedin = False;
		$this->load->database();
		//$this->load->library('validation');
		$this->load->model('/fp_usersLoginModel/loginmodel');
		//$this->load->library(array('encrypt', 'session'));
		//$this->load->helper(array('form','url'));
	}

	function Index()
	{
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

	// validation rules
	function _set_rules()
	{
		$this->load->library('validation');
		//$rules['user_id'] = 'required|valid_email';
		$rules['user_id'] = 'required|max_length[20]|min_length[2]';
		$rules['password'] = 'required|max_length[20]|min_length[2]';
		$this->validation->set_rules($rules);
		//$this->_set_fields();
		$this->validation->set_message('required', '* required');
		$this->validation->set_message('max_length', '* Max. 20 Digit');
		//$this->validation->set_message('isset', '* required');
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}


	function verify()
	{
		log_message('debug', 'login submit');

		// set validation properties
		$this->_set_rules();
		$this->_set_fields();

		// Set the error delims to a nice styled red box
		$this->validation->set_error_delimiters('<p class="hint error"><span>', '</span></p>');

		// Run validation
		if ($this->validation->run() == FALSE) {


			// Validation failed, load login page again
			return $this->index();
		} else {


			// Form validation for length etc. passed, now see if the credentials are OK in the DB
			// Post values 

			$username = $this->input->post('user_id');
			$password = $this->input->post('password');

			// Now see if we can login
			if ($this->userauth->trylogin($username, $password)) {

				// Success! Redirect to control panel
				$newdata = array(
					'tempPass'  => $password
				);
				$this->session->set_userdata($newdata);

				redirect('controlpanel', 'location');
			} else {
				$this->session->set_flashdata('auth', $this->load->view('msgbox/error', 'Incorrect username and/or password.', True));
				$data['error'] = 'Incorrect username and/or password.';
				$data['action'] = site_url('login/login/verify');
				$data['page'] = '/fp_usersLoginView/login_form'; //add page name as a parameter
				$this->load->view('/fp_usersLoginView/index', $data);
				//redirect('login');
			}
		}
	}

	/*
	
	function verify(){
		$this->_set_rules();//Setting the validation rules inside the validation function
		if($this->validation->run() == FALSE){ //Checks whether the form is properly sent
			// set validation properties
			$this->_set_fields();
			$this->_set_rules();
			// set common properties
			$data['action'] = site_url('login/Login/verify');
			$data['error'] ="";
			$data['page'] ="/fp_usersLoginView/login_form";
			$this->load->view('/fp_usersLoginView/index',$data); //If validation fails load the login form again
		}
		else{
			$result = $this->LoginModel->varify($this->input->post('user_id'),$this->input->post('password')); //If validation success then call the login function inside the common model and pass the arguments
			if($result){ //if login success
				foreach($result as $row){
					$this->session->set_userdata(array('controlpanel'=>true,'userid'=>$row->user_id,'username'=>$row->username)); //set the data into the session
				}
				redirect('/login/index/View/', 'refresh');//Load the success page
			}
			else{ // If validation fails.
				$data = array();
				$this->_set_fields();
				$this->_set_rules();
				// set common properties
				$data['action'] = site_url('login/Login/verify');
				$data['error'] = 'Incorrect Username / Password'; //create the error string
				$data['page'] ="/fp_usersLoginView/login_form";
				$this->load->view('/fp_usersLoginView/index',$data); //Load the login page and pass the error message
			}
		}
	}
	*/

	function logout()
	{
		$this->userauth->logout();
		#$layout['title'] = 'Logout';
		#$layout['body'] = '<h2>Logged out</h2>You have successfully logged out of Classroom Bookings.' . anchor('site/home','Home');
		#$this->load->view('layout', $layout);
		redirect('mainindex', 'location');
	}

	function logout_pass_change()
	{
		$this->userauth->logout();
		#$layout['title'] = 'Logout';
		#$layout['body'] = '<h2>Logged out</h2>You have successfully logged out of Classroom Bookings.' . anchor('site/home','Home');
		#$this->load->view('layout', $layout);
		redirect('mainindex/index/2', 'location');
	}
}
