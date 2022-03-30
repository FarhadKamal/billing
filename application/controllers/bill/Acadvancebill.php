<?php
Class Acadvancebill extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function __construct(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));		
		// load model
		$this->load->model('/billaccountmodel','',TRUE);
		
		if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') != 7 ) {
	
		show_401();
	}
	
	}
	 
	function Index(){
		

		
	}
	
	function _set_fields(){			


		$fields['divide_amount'] = 'Amount';
		$fields['account_head'] = 'Account Head';
		
		$fields['remark'] = 'Remarks';
		$fields['Vendor'] = 'Vendor';
	
		
		$this->validation->set_fields($fields);
	}
	
	function _set_rules(){

		$rules['account_head'] = 'trim|required';
		$fields['Vendor'] = 'trim|required';

		$rules['divide_amount'] = 'trim|required';
		$this->validation->set_rules($rules);	
		$this->validation->set_message('required', '* required'); 
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}
	
	
	function viewDoc($doc){
		$data['doc']=$doc;
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('billView/viewDoc', $data);
	}
	
	
	function viewDocs($id){
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['docs']=$this->billaccountmodel->get_documents($id);
		$this->load->view('billView/viewDocs', $data);
	}
	
	
	
	
	
	function makeQuery($id){
		return $member = array(

							
							'advance_id' => $id,
					
							'vendor' => $this->input->post('Vendor'),
							'account_head' => $this->input->post('account_head'),
							'amount' => $this->input->post('divide_amount'),
							'remarks' => $this->input->post('remark')
			
							
							);
	}
	


	
	function mkDate($userDate){
		if($userDate!=''){
		$date_arr = explode('-', $userDate);
		$data = date("Y-m-d", mktime(0,0,0,$date_arr[1], $date_arr[0], $date_arr[2] ) );
		return $data;}
		else return '';
	}
	
	function getListData(){
		
		$data['company']=$this->billaccountmodel->list_company();
		$data['vendor']=$this->billaccountmodel->list_vendor();
		return $data;
		
	}
	
	
	
	
	
	function deleteAdvance($id1,$id2){
		$this->billaccountmodel->deleteAdvance($id1);
		redirect('bill/acadvancebill/bill_Accept_account/'.$id2, 'location');
	}
	
	
	
	function bill_Accept_account($id){
		
		$netaccount=$this->billaccountmodel->netadvanceamount($id);
		$newamount=$this->billaccountmodel->newadvanceamount($id);
		
		
		
		
		if($this->input->post('divide_amount')!="")
		$newamount=$newamount+$this->input->post('divide_amount');
		
		$this->_set_fields();
		$this->_set_rules();
		
		//$this->validation->EmpId =	$this->session->userdata('username');
		
		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			
		}
		else if($newamount>$netaccount){
			$data['message'] = '<div class="cancel" align=left>Balance Not Available..</div>';
		}
		else{
			// save data
			$data=$this->makeQuery($id);
					
			//print_r($request);
			$this->billaccountmodel->saveadvance($data);

			// set user message
	
			$data['message'] = '<div class="success" align=left>Save Successful..</div>';
	
			//$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);
			
			
		}
			$data['userlevel']=$this->session->userdata('authlevel');

			$data['vendor']=$this->billaccountmodel->list_vendor();
			$data['accounthead']=$this->billaccountmodel->account_head();
			
			$data['netaccount']=$netaccount;
			$balance=$netaccount-$this->billaccountmodel->newadvanceamount($id);
			$data['balance']=$balance;

	
		
			// set common properties
			$data['title'] = 'Advance Accept Form';
			$data['action'] =  site_url('bill/acadvancebill/bill_Accept_account/'.$id.'/');
			$data['action2'] =  site_url('bill/accountbill/advancePassAdd/'.$id);
			$items=$this->billaccountmodel->cost_center_advance($id)->result();		
			$data['table'] =			$this->cost_table($items);
			
			$data['page']='/billView/accountAdvance'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	
	
	
	function cost_table($items)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No','Account&nbsp;Head&nbsp;','Vendor&nbsp;','remark&nbsp;','Amount&nbsp;BDT','Action');
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){

			$del='&nbsp;'.anchor('bill/acadvancebill/deleteAdvance/'.$row->id.'/'.$row->advance_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));


			$this->table->add_row(				
				$sl,

				$row->account_head,
				$row->vendor,
				$row->remarks,
				$row->amount,
				$del
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	


}
