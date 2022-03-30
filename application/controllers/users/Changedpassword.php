<?php
Class Changedpassword extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	
	function __construct(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));
		
		// load helper
		$this->load->helper('url');
		
		// load model
		$this->load->model('/fp_usersModel/usersmodel','',TRUE);
		
	 }

	function updateChangedPasswordQuery($id){
	
		return $query = array(
							'user_id' => $id,
							'password' =>  sha1($this->input->post('newpassword')));
	}
	function changePassword($type=1){
		


		// set validation properties
		$this->_set_fields();
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['ext']= $this->session->userdata('ext');
		// set common properties
		$data['title'] = 'Changed Password ...';
		$data['message'] = '';
		if($type==2)
		$data['message'] = "<div class='cancel'>Your Password is Weak! Please Change it.</div>";
		$data['action'] = site_url('users/changedpassword/updateChangePassword');
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));
		
		//Get Users Id & Name
		$this->validation->Id=$this->session->userdata('user_id');
		$this->validation->username=$this->session->userdata('username');
		$this->validation->oldpassword='';
		// load view 
		$data['page']='/fp_usersLoginView/changedPasswordEdit'; //add page name as a parameter
		$this->load->view('index', $data);
	}
	
	function updateChangePassword(){
	$data['userlevel']=$this->session->userdata('authlevel');
		// set common properties
		$data['title'] = 'Changed Password';
		$data['action'] = site_url('users/changedpassword/updateChangePassword');
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));
		
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();
		
		

		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			
			//Get Users Id & Name
			$this->validation->Id=$this->session->userdata('user_id');
			$this->validation->username=$this->session->userdata('username');	
			
			// load view 
			$data['page']='/fp_usersLoginView/changedPasswordEdit'; //add page name as a parameter
			$this->load->view('index', $data);
		}else{
			// save data
			
			if($this->input->post('retypepassword')!= $this->input->post('newpassword')){
			
				// set user message
				$data['message'] = '<div class="cancel">New Password & Retype&nbsp;New&nbsp;Password does not match ..</div>';																																
			}else{
				if ($this->session->userdata('password')== sha1($this->input->post('oldpassword'))){
					
					
					
					   if(!preg_match("#[0-9]+#",$this->input->post('newpassword'))) {
							$data['message'] = "<div class='cancel'>Your Password Must Contain At Least 1 Number!</div>";
						}
						elseif(!preg_match("#[A-Z]+#",$this->input->post('newpassword'))) {
							$data['message']  = "<div class='cancel'>Your Password Must Contain At Least 1 Capital Letter!</div>";
						}
						elseif(!preg_match("#[a-z]+#",$this->input->post('newpassword'))) {
							$data['message'] = "<div class='cancel'>Your Password Must Contain At Least 1 Lowercase Letter!</div>";
						}				
						else{ 
						$id = $this->input->post('Id');
						$result=$this->updateChangedPasswordQuery($id);
						$this->usersmodel->update($id,$result);
						
						
						$this->session->set_userdata(array('password'=>sha1($this->input->post('newpassword'))));
						
						$newdata = array(
							   'tempPass'  => $this->input->post('newpassword')
							);
							$this->session->set_userdata($newdata);
						
						// set user message
						redirect('login/login/logout_pass_change', 'location');
						$data['message'] = '<div class="success">update successful ..</div>'; } 
				}else{
				
					// set user message
					$data['message'] = '<div class="cancel">password does not match ..</div>';																																
				}
			}
			$data['page']='/fp_usersLoginView/changedPasswordEdit'; //add page name as a parameter
			$this->load->view('index', $data);
		}
		
	}
	
	// validation rules
	function _set_rules(){
	
		$rules['oldpassword'] = 'trim|required';
		$rules['newpassword'] = 'trim|required|min_length[5]';
		$rules['retypepassword'] = 'trim|required';
		
		
		//$rules['Date'] = 'trim|required|callback_valid_date';
		
		$this->validation->set_rules($rules);
		
		$this->validation->set_message('required', '* required'); //for other Fields..
		$this->validation->set_message('isset', '* required'); //
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}
	
	// validation fields
	function _set_fields(){
		
		$fields['Id'] = 'Id';
		$fields['MembersId'] = 'MembersId';
		$fields['username'] = 'username';
		$fields['password'] = 'password';
		$fields['newpassword'] = 'New Password';
		$fields['oldpassword'] = 'Old Password';
		$fields['retypepassword'] = 'Retype New Password';
		//$fields['Date'] = 'Date';
		$this->validation->set_fields($fields);
	}
	
	// date_validation callback
	function valid_date($str)
	{
		if(!ereg("^(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-([0-9]{4})$", $str))
		{
			$this->validation->set_message('valid_date', 'date format is not valid. dd-mm-yyyy');
			return false;
		}
		else
		{
			return true;
		}
	}

}
