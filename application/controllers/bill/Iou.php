<?php
Class Iou extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function __construct(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));		
		// load model
		$this->load->model('/ioumodel','',TRUE);
		
		if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') != 5 AND $this->session->userdata('authlevel') != 6) {
	
		show_401();
	}
	
	}
	 
	function Index(){
		
		$data=$this->getListData();
		$this->_set_fields();
		$data['title'] = 'Claim Iou';
		$data['message'] = '';
		$data['openmodel'] = 'no';
		$data['action'] = site_url('bill/iou/add');
		$data['page']='/billView/iouEdit'; 
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['entry']=1;
		$data['recommend']=0;
		$this->validation->amount =0;
		$this->load->view('index', $data);
		
	}
	
	function _set_fields(){
		
		$fields['Company'] = 'Company';	
		$fields['ReportingOfficer'] = 'Reporting Officer';
		$fields['amount'] = 'Amount';
		$fields['purpose'] = 'Purpose';
		
		
		$this->validation->set_fields($fields);
	}
	
	function _set_rules(){
		$rules['Company'] = 'trim|required';
		$rules['ReportingOfficer'] = 'trim|required';
	
		$rules['purpose'] = 'trim|required';

		$this->validation->set_rules($rules);	
		$this->validation->set_message('required', '* required'); 
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}
	
		
	function add(){
	
		$data=$this->getListData();
		// set common properties
		$data['title'] = 'Claim Iou';
		
		
		$this->_set_fields();
		$this->_set_rules();
		
		$this->validation->amount = 				0;
		
		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			$data['action'] =  site_url('bill/iou/add');
			$data['openmodel'] = 'no';
		
		}
	
		else{
			// save data	
			
			$this->ioumodel->save($this->makeQueryinsert());
			
			$data['openmodel'] = 		'yes';
			$reqId = 				$this->ioumodel->get_last_entry_id();			
	
			$data['action'] =  			site_url('bill/iou/updateIou/'.$reqId);

			$data['action2'] =  site_url('bill/iou/FinalSubmit/'.$reqId);
			
			
			$data['action3'] =  site_url('bill/iou/AddItem/'.$reqId);
			$data['table'] =""; 	
			$data['message'] = 			'<div class="success" align=left>save successful..</div>';
		
			
		}
		
			$data['page']='/billView/iouEdit'; //add page name as a parameter
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['entry']=1;
			$data['recommend']=0;
			$this->load->view('index', $data);
	}
	
	function makeQuery(){
		$rp=$this->input->post('ReportingOfficer');
		if($rp=='3')
		$rp='003';
	
		return $member = array(
							'company' => $this->input->post('Company'),
							'dep_accept_by' => $rp,	
							'hold_by' => $rp,		
							'req_by' => $this->session->userdata('username'),
						
							'purpose' => $this->input->post('purpose')
							
							);
	}
	
	
	
	function makeQueryinsert(){
		//$id=$this->ioumodel->id();
		
		$rp=$this->input->post('ReportingOfficer');
		
		if($rp=='3')
		$rp='003';
		
		return $member = array(
							'company' => $this->input->post('Company'),
							'dep_accept_by' => $rp,	
							'hold_by' => $rp,		
							'req_by' => $this->session->userdata('username'),
						
							'purpose' => $this->input->post('purpose')
							
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
		$data['company']=$this->ioumodel->list_company();
		$data['reportingOfficerList']=$this->ioumodel->list_head();
		return $data;
		
	}
	
	
	function searchIou(){

		//Search 
		$data['action']=site_url('bill/iou/searchIou');;
		$data['title']="IOU List";
		// set user message
		$data['message']='';
		
		// load data
		$requisition = $this->ioumodel->get_search_by_id($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->ioumodel->count_search($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')));
		
		$data['table'] =$this->iou_table($requisition);
		$data['page']='/billView/iouList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function iouList($message='',$offset = 0){
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		$data['action']=site_url('bill/iou/searchIou');
		$data['title']="IOU List";
		// set user message
		$data['message']=$message;
		
		if($message=='submit')
		$data['message']="<div class='success' align=left>Submitted Successful..!!</div>";


		
		// load data
		$iou = $this->ioumodel->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/iou/iouList/');
		$number_of_rows=$this->ioumodel->count_all();
 		$config['total_rows'] = $number_of_rows;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;

		
		$data['table'] =$this->iou_table($iou);
		$data['page']='/billView/iouList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	
	
	function iou_table($requisition)
	{
	
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('ID','request&nbsp;by','request&nbsp;date','Requested&nbsp;to','department&nbsp;approved&nbsp;date','Finance Approved Date','amount','purpose','hold by','status','action');

		$status="";
		

		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($requisition as $row){			

		if($row->step_status==1)	$status="Still Not Final Submitted";
		else if($row->step_status==2)	$status="Submitted to Department Head";
		else if($row->step_status==3 and ($row->hold_by==2405 or $row->hold_by==2346))	$status="Submitted to Finance";
		else if($row->step_status==4)	$status="Submitted to Cash Counter";
		else if($row->step_status==5)	$status="Payment made";

		if($row->cancel_status==1 and $row->step_status==2)
		$status="Canceled";		
		else if($row->cancel_status==1 and $row->step_status==3)
		$status="Canceled";
//$status="Canceled by Department Head";		
		if($row->step_status==1 and $row->req_by==$this->session->userdata('username'))$view=anchor('bill/iou/updateIou/'.$row->id,'update&nbsp;',array('class'=>'update'));	
		else $view="";	

		$report=anchor('reports/reports/view_iou/'.$row->id,'view',array('class'=>'view','target'=>'about_blank'));		
		$viewhistory=anchor('reports/reports/view_history_iou/'.$row->id,'view&nbsp;history',array('class'=>'view','target'=>'about_blank'));
		
			$this->table->add_row(
			
				$row->id,
				$row->req_by." ".$row->createdName,
				$row->req_date,
				$row->deptName,
				$row->dep_accept_date,				

				$row->dgm_accept_date,
				$row->amount, 
				$row->purpose,
				$row->holdName,
				$status,
				$view."<br/><br/>".$report."<br/><br/>".$viewhistory

			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	

	
	function updateIou($id,$filechk=1){
		
		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();
		
		

		$data=$this->getListData();
		
		$data['openmodel'] = 		'yes';
		//$data['billId'] = 		$id;

		
		// run validation
		if ($this->validation->run() == FALSE){
		

			 $data['message'] = '';
			// load view 
			if($filechk==7)
			$data['message'] = '<div class="cancel" align="left">Please input reason  first, then you can click add.</div>';
			else if($filechk==8)
			$data['message'] = '<div class="cancel" align="left">Please input reason  first, then you can click update.</div>';
			
		}
		else{
			// save data
		
			$this->ioumodel->update($id,$this->makeQuery());
			
	
			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}
		
		
		$data['action3'] =  site_url('bill/iou/AddItem/'.$id);
		$items=$this->ioumodel->get_items($id)->result();	
		$data['table'] =			$this->item_table($items);
		
		
		
		$this->ioumodel->calculate_total($id);
		$rows = $this->ioumodel->get_by_id($id)->row();

		$this->validation->Company = 						$rows->company;

		
		$this->validation->ReportingOfficer = 		$rows->dep_accept_by;
		$this->validation->purpose = 				$rows->purpose;
		$this->validation->amount = 				$rows->amount;
		
		
		$data['title'] = 'Claim IOU';
		$data['action'] = site_url('bill/iou/updateIou/'.$id);

		$data['action2'] =  site_url('bill/iou/FinalSubmit/'.$id);
		$data['page']='/billView/iouEdit'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['entry']=1;
		$data['recommend']=0;
		$this->load->view('index', $data);
		
	}
	
	
	
	function FinalSubmit($id)
	{
			
			
			$this->ioumodel->FinalSubmit($id);
			redirect('bill/iou/iouList/submit', 'location');
			
	}
	
	
	function item_table($items)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
	
		$this->table->set_heading('Ref ID','Reasons and Amounts&nbsp;');
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){

			$del='&nbsp;'.anchor('bill/iou/deleteItem/'.$row->id.'/'.$row->iou_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));

			$ac=site_url('bill/iou/updateItem/'.$row->iou_id.'/'.$row->id);
			$this->table->add_row(	
				
				$row->id,
				'<form method="post" action="'.$ac.'"><table style="font: 0.90em arial;width:690;"><tr>'.	
	
				'<td>Reason&nbsp;For&nbsp;Request&nbsp;<br/><textarea rows=4  name="reason_des"  cols=30>'.$row->purpose.'</textarea></td>
				<td>Amount&nbsp;<br/><input type="text" name="reason_amount" class="text" value="'.$row->amount.'"/></td>
				<td><input type="submit" value="update"/><br/><br/>'.$del.'</td></tr></table></form>'
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	function addItem($id)
	{
		
		if($this->input->post('reason_des')!="" )
		{
		
			$this->ioumodel->saveItem(			
			array(
			'purpose' => $this->input->post('reason_des'),
			'amount' => $this->input->post('reason_amount'),
			'iou_id' => $id
			)
			);
			redirect('bill/iou/updateIou/'.$id.'/1', 'location');
		}
		else redirect('bill/iou/updateIou/'.$id.'/7', 'location');
		
	}
	
	function updateItem($id1,$id2)
	{
	
		if($this->input->post('reason_des')!="" )
		{
			$this->ioumodel->updateItem($id2,			
			array(
			'purpose' => $this->input->post('reason_des'),
			'amount' => $this->input->post('reason_amount'),
			'iou_id' => $id1
			));
	
			redirect('bill/iou/updateIou/'.$id1.'/1', 'location');
		}
		else redirect('bill/iou/updateIou/'.$id1.'/8', 'location');
		
	}
	
	
	function deleteItem($id1,$id2){
		
		$this->ioumodel->deleteItem($id1);
		$this->ioumodel->calculate_total($id1);
		redirect('bill/iou/updateIou/'.$id2, 'location');
	}

}
