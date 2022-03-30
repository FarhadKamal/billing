<?php
Class Pobill extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function Pobill(){
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
		$fields['Company'] = 'Company';
		$fields['Area'] = 'Area';

		$fields['divide_amount'] = 'Amount';
		$fields['tmpProfitC'] = 'Profit Center';
		
		$fields['sap_id'] = 'Sap id';
		$fields['Vendor'] = 'vendor';
	
		
		$this->validation->set_fields($fields);
	}
	
	function _set_rules(){
		$rules['Company'] = 'trim|required';
		$fields['Area'] = 'trim|required';
		$fields['sap_id'] = 'trim|required';
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

							
							'doc_id' => $id,
							'business_area' => $this->input->post('Area'),
							'company_id' => $this->input->post('Company'),
							'sap_id' => $this->input->post('sap_id'),
							'vendor' => $this->input->post('Vendor'),
							'profit_center' => $this->input->post('tmpProfitC'),
							'divide_amount' => $this->input->post('divide_amount')
			
							
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
	
	
	
	
	
	function deleteCost($id1,$id2){
		$this->billaccountmodel->deleteCost($id1);
		redirect('bill/pobill/bill_Accept_account/'.$id2, 'location');
	}
	
	
	
	function bill_Accept_account($id){
		
		$netaccount=$this->billaccountmodel->netamount($id);
		$newamount=$this->billaccountmodel->newamount($id);
		
		
		$billtag=$this->billaccountmodel->get_by_id($id)->row();
		
		if($this->input->post('divide_amount')!="")
		$newamount=$newamount+$this->input->post('divide_amount');
		
		$this->_set_fields();
		$this->_set_rules();
		
		$this->validation->Company	=	$billtag->company_id;
		if($this->validation->Area=='')
		$this->validation->Area	= 6;
		
		if($this->validation->tmpProfitC=='')
		{
			if($billtag->loc==1)
			$this->validation->tmpProfitC="PC-01";
			else if($billtag->loc==2)
			$this->validation->tmpProfitC="PC-02";
		}
		
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
			$this->billaccountmodel->save($data);

			// set user message
	
			$data['message'] = '<div class="success" align=left>Save Successful..</div>';
	
			//$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);
			
			
		}
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['area']=$this->billaccountmodel->list_area();
			$data['vendor']=$this->billaccountmodel->list_vendor();
			
			$data['netaccount']=$netaccount;
			$balance=$netaccount-$this->billaccountmodel->newamount($id);
			$data['balance']=$balance;
			if($balance==0)
			$this->billaccountmodel->accountmark($id);
			else $this->billaccountmodel->rmv_accountmark($id);

			$data['company']=$this->billaccountmodel->list_company();
		
			// set common properties
			$data['title'] = 'Bill Accept Form';
			$data['action'] =  site_url('bill/pobill/bill_Accept_account/'.$id.'/');
			$data['action2'] =  site_url('bill/accountbill/FinalSubmit/'.$id);
			$items=$this->billaccountmodel->cost_center($id)->result();		
			$data['table'] =			$this->cost_table($items);
			
			$data['page']='/billView/noPo'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	
	
	
	function cost_table($items)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No','Company&nbsp;','Business&nbsp;Area&nbsp;','Profit&nbsp;Center&nbsp;','SAP&nbsp;ID&nbsp;','Vendor&nbsp;','Amount&nbsp;BDT','Action');
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){

			$del='&nbsp;'.anchor('bill/pobill/deleteCost/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));


			$this->table->add_row(				
				$sl,
				$row->vCompany,
				$row->area,
				$row->profit_center,
				$row->sap_id,
				$row->vendor,
				$row->divide_amount,
				$del
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	


}
