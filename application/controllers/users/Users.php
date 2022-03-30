<?php
Class Users extends MY_Controller {
	
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
		if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') != 1) {
		
			show_404();
		}
	 }

	function Index($offset = 0,$message=''){
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		
		// set user message
		$data['message']=$message;
		
		// load data
		$results = $this->usersmodel->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('users/users/index/');
		$number_of_rows=$this->usersmodel->count_all();
 		$config['total_rows'] = $number_of_rows;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;
		
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('Id','Name','Display Name','Date','Actions');
		//$i = 0 + $offset;
		foreach ($results as $result){
			$link='';
			if ($this->session->userdata('authlevel') ==1){
				$link= anchor('users/users/update/'.$result->user_id,'update',array('class'=>'update')).' '.
				anchor('users/users/delete/'.$result->user_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));
			}
			$this->table->add_row($result->user_id,$result->username,
			$result->displayname,date('d-m-Y',strtotime($result->created)),$link);
		}
		$data['table'] = $this->table->generate();
		$data['page']='/fp_usersFormView/usersList'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	function view($id){
		// set common properties
		$data['title'] = 'Money Receipt Information ...';
		//$data['link_back'] = anchor('members/Members/index/','Back to list of persons',array('class'=>'back'));
		
		// get members details
		$data['result'] = $this->usersmodel->get_by_id($id)->row();
		
		// load view 
		$data['page']='/fp_usersFormView/usersView'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	function add(){
		// set validation properties
		$this->_set_fields();
		
		// set common properties
		$data['title'] = 'New User ...';
		$data['message'] = '';
		$data['passwordEdit'] = '';
		$data['action'] = site_url('users/users/addUsers');
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));
		
		//load members id from database
		$data['userrole']=$this->usersmodel->list_usersRole();
		
		//set MR NO
		$this->validation->Id=$this->usersmodel->maxId();
			
		// load view 
		$data['page']='/fp_usersFormView/usersEdit'; //add page name as a parameter
		$this->load->view('index', $data);
	}
	
	
	function addUsers(){
		// set common properties
		$data['title'] = 'New User ...';
		$data['action'] =  site_url('users/users/addUsers');
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));
		
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();
		
		$rules['username'] = 'trim|required';
		$rules['password'] = 'trim|required';
		$this->validation->set_rules($rules);
		// run validation
		if ($this->validation->run() == FALSE){
		
			$data['message'] = '';
			$data['passwordEdit'] = '';
			//load members id from database
			$data['userrole']=$this->usersmodel->list_usersRole();
			
			//set MR NO
			$this->validation->Id=$this->usersmodel->maxId();
		
		// load view 
			$data['page']='/fp_usersFormView/usersEdit'; //add page name as a parameter
			$this->load->view('index', $data);
		}else{
		
			//set get NO
			$Id=$this->usersmodel->maxId();
			// save data
			$userEntry=$this->makeQuery($Id);
			$this->usersmodel->save($userEntry);
			
			// set user message
			$message = '<div class="success">save success..</div>';
			$this->Index(0,$message);
			//redirect('users/users/index/');
		}
	}
	
	function update($id){
		// set validation properties
		$this->_set_fields();

		// prefill form values
		$results = $this->usersmodel->get_by_id($id)->row();
		$this->validation->Id = $id;
		$this->validation->UserRole = $results->authlevel;
		$this->validation->username = $results->username;
		$this->validation->password = $results->password;
		$this->validation->fullname = $results->name;
		$this->validation->DisplayName = $results->displayname;

		//load members id from database
		$data['membersid']=$this->usersmodel->list_membersId();
			
		//load members id from database
		$data['userrole']=$this->usersmodel->list_usersRole();
			
		
		// set common properties
		$data['title'] = 'Update Users ..';
		$data['message'] = '';
		$data['passwordEdit'] = 'no';
		$data['action'] = site_url('users/users/updateUsers');
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));
	
		// load view
		$data['page']='/fp_usersFormView/usersEdit'; //add page name as a parameter
		$this->load->view('index', $data);
	}

	function updateUsers(){
		// set common properties
		$data['title'] = 'Update Users';
		$data['action'] = site_url('users/users/updateUsers');
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));
		
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();
			
		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			$data['passwordEdit'] = 'no';
			
			//load members id from database
			$data['userrole']=$this->usersmodel->list_usersRole();
			
			// load view 
			$data['page']='/fp_usersFormView/usersEdit'; //add page name as a parameter
			$this->load->view('index', $data);
		}else{
			// save data
			$id = $this->input->post('Id');
			$result=$this->updateQuery($id);
			$this->usersmodel->update($id,$result);
			
			// set user message
			$message = '<div class="success">update successful ..</div>';
			$this->Index(0,$message);
			//redirect('users/users/index/');
		}
		
	}
	
	function delete($id){
		// delete member
		$this->usersmodel->delete($id);
		
		// set user message
		$message = '<div class="success">delete receipt success</div>';
		
		// redirect to member list page
		//$this->Index(0,$message);
		redirect('users/users/index/',$message);
		
	}
	
	function makeQuery($id){
	
		return $query = array(
							'user_id' => $id,
							'username' => $this->input->post('username'),
							'firstname' => $this->input->post('fullname'),
							'password' => sha1($this->input->post('password')),
							'authlevel' => $this->input->post('UserRole'),
							'enabled' => 1,
							'displayname' => $this->input->post('DisplayName'));
							
	}
	
	function updateQuery($id){
	
		return $query = array(
							'user_id' => $id,
							'firstname' => $this->input->post('fullname'),
							'authlevel' => $this->input->post('UserRole'),
							'enabled' => 1,
							'displayname' => $this->input->post('DisplayName'),
							'created'=>date('d-m-Y',strtotime(date('Y-m-d h:i:s'))));
	}	
	
	function updateChangedPasswordQuery($id){
	
		return $query = array(
							'user_id' => $id,
							'password' =>  sha1($this->input->post('newpassword')));
	}
	function changePassword(){
		// set validation properties
		$this->_set_fields();
		
		// set common properties
		$data['title'] = 'Changed Password ...';
		$data['message'] = '';
		$data['action'] = site_url('users/users/updateChangePassword');
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));
		
		//Get Users Id & Name
		$this->validation->Id=$this->session->userdata('user_id');
		$this->validation->username=$this->session->userdata('username');
		$this->validation->oldpassword='';
		// load view 
		$data['page']='/fp_usersFormView/changedPasswordEdit'; //add page name as a parameter
		$this->load->view('index', $data);
	}
	
	function updateChangePassword(){
		// set common properties
		$data['title'] = 'Changed Password';
		$data['action'] = site_url('users/users/updateChangePassword');
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));
		
		// set validation properties
		$this->_set_fields();
		
		$rules['oldpassword'] = 'trim|required';
		$rules['newpassword'] = 'trim|required';
		$this->validation->set_rules($rules);
		$this->validation->set_message('required', '* required'); //for other Fields..
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			
			//Get Users Id & Name
			$this->validation->Id=$this->session->userdata('user_id');
			$this->validation->username=$this->session->userdata('username');	
			
			// load view 
			$data['page']='/fp_usersFormView/changedPasswordEdit'; //add page name as a parameter
			$this->load->view('index', $data);
		}else{
			// save data
			if ($this->session->userdata('password')== sha1($this->input->post('oldpassword'))){
				$id = $this->input->post('Id');
				$result=$this->updateChangedPasswordQuery($id);
				$this->usersmodel->update($id,$result);
				
				// set user message
				$data['message'] = '<div class="success">update successful ..</div>';
			}else{
			
				// set user message
				$data['message'] = '<div class="success">password does not match ..</div>';																																
			}
			$data['page']='/fp_usersFormView/changedPasswordEdit'; //add page name as a parameter
			$this->load->view('index', $data);
		}
		
	}
	
	// validation rules
	function _set_rules(){
	
		
		$rules['UserRole'] = 'trim|required';
		$rules['fullname'] = 'trim|required';
		$rules['DisplayName'] = 'trim|required';
		
		
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
		$fields['UserRole'] = 'UserRole';
		$fields['password'] = 'password';
		$fields['fullname'] = 'fullname';
		$fields['DisplayName'] = 'DisplayName';
		$fields['newpassword'] = 'newpassword';
		$fields['oldpassword'] = 'oldpassword';
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
