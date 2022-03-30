<?php
Class Bill extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function __construct(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));		
		// load model
		$this->load->model('/billmodel','',TRUE);
		$this->load->model('/generalmodel','',TRUE);
		
		if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') != 5 AND $this->session->userdata('authlevel') != 6 AND $this->session->userdata('authlevel') != 9) {
	
			show_401();
		}
	
	}
	 
	function Index(){
		
		$data=$this->getListData();
		$this->_set_fields();
		$this->validation->bill_date = 	date('d-m-Y');
		$data['title'] = 'Claim Vendor Bill';
		$data['message'] = '';
		$data['openmodel'] = 'no';
		$data['action'] = site_url('bill/bill/add');
		$data['page']='/billView/billEdit'; 
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->validation->advance = 0;
		$data['entry']=1;
		$data['recommend']=0;
		$this->load->view('index', $data);
		
	}
	
	function _set_fields(){
		
		$fields['general_deduction'] = 'General Deduction';
		$fields['general_deduction_note'] = 'General Deduction Note';
		$fields['tot_auth_deduction'] = '';
		$fields['bill_date'] = 'bill date';
		$fields['flow_type'] = 'flow type';
		$fields['Company'] = 'Company';	
		$fields['loc'] = 'Location';	
		$fields['Vendor'] = 'Vendor';	
		$fields['po_no'] = 'PO NO';
		$fields['po_date'] = 'PO DATE';
		$fields['gr_no'] = 'GR NO';
		$fields['gr_date'] = 'GR DATE';
		$fields['iv_no'] = 'IV NO';
		$fields['iv_date'] = 'IV DATE';
		$fields['bill_description'] = 'bill description';
		$fields['asset_no'] = 'Asset No';
		$fields['tds'] = 'TDS';
		$fields['vds'] = 'VDS';
		$fields['advance'] = 'Advance';
		$fields['amount'] = 'Amount';
		$fields['retypeamount'] = 'Retype Amount';
		$fields['payment_type'] = 'payment_type';
		$fields['doc_file'] = 'doc_file';
		$fields['ReportingOfficer'] = 'Reporting Officer';
		
		$fields['suggested_cheque'] = 'Suggested Cheque';
		
		$this->validation->set_fields($fields);
	}
	
	function _set_rules(){
		$rules['loc'] = 'trim|required';
		$rules['po_no'] = 'trim|required';
		$rules['po_date'] = 'trim|required';
		$rules['iv_no'] = 'trim|required';
		$rules['iv_date'] = 'trim|required';
		
		$rules['gr_no'] = 'trim|required';
		$rules['gr_date'] = 'trim|required';
		
		$rules['bill_date'] = 'trim|required';
		$rules['bill_description'] = 'trim|required';
		$rules['Company'] = 'trim|required';
		$rules['Vendor'] = 'trim|required';	
		$rules['advance'] = 'trim|required';
		$rules['retypeamount'] = 'trim|required';
		$rules['amount'] = 'trim|required';
		$rules['ReportingOfficer'] = 'trim|required';
		$rules['payment_type'] = 'trim|required';
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
		$data['docs']=$this->billmodel->get_documents($id);
		$this->load->view('billView/viewDocs', $data);
	}
	
	
	function add(){
	
		$data=$this->getListData();
		// set common properties
		$data['title'] = 'Claim Vendor Bill';
		
		
		$this->_set_fields();
		$this->_set_rules();
		
		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			$data['action'] =  site_url('bill/bill/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		}
		else if($this->input->post('amount')!=$this->input->post('retypeamount')){
			$data['message'] = '<div class="cancel" align=left>Please Check Amount</div>';
			$data['action'] =  site_url('bill/bill/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		}
		else if($this->input->post('payment_type')=='Cheque' and $this->input->post('suggested_cheque')==''  ){
			$data['message'] = 			'<div class="cancel" align=left>please input Cheque Name..</div>';
			$data['action'] =  site_url('bill/general/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		
		}
		else{
			// save data	
			
			$this->billmodel->save($this->makeQueryinsert());
			
			$data['openmodel'] = 		'yes';
			$billId = 				$this->billmodel->get_last_entry_id($this->mkDate($this->input->post('bill_date')));			
	
			$data['action'] =  			site_url('bill/bill/updatebill/'.$billId);
			$data['itemcount'] = 		$this->billmodel->tot_document_by_bill_id($billId);
			$data['action2'] =  site_url('bill/bill/addDoc/'.$billId);
			$data['action3'] =  site_url('bill/bill/submitSupervisor/'.$billId);
			
			
			$items=$this->billmodel->get_documents($billId)->result();
			
			$data['table'] =			$this->documten_table($items);

			$data['message'] = 			'<div class="success" align=left>save successful..</div>';
		
			
		}
		
			$data['page']='/billView/billEdit'; //add page name as a parameter
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['entry']=1;
			$data['recommend']=0;
			$this->load->view('index', $data);
	}
	
	function makeQuery(){
		return $member = array(
							'bill_description' => $this->input->post('bill_description'),
							'bill_date' => $this->mkDate($this->input->post('bill_date')),
							'company_id' => $this->input->post('Company'),
							'vendor_code' => $this->input->post('Vendor'),
							'po_no' => $this->input->post('po_no'),
							'po_date' => $this->mkDate($this->input->post('po_date')),
							
							'gr_no' => $this->input->post('gr_no'),
							'gr_date' => $this->mkDate($this->input->post('gr_date')),
							
							'iv_no' => $this->input->post('iv_no'),
							'iv_date' => $this->mkDate($this->input->post('iv_date')),
							
							'asset_no' => $this->input->post('asset_no'),
							
							'general_deduction' => $this->input->post('general_deduction'),
							'general_deduction_note' => $this->input->post('general_deduction_note'),
							
							'tds' => $this->input->post('tds'),
							'vds' => $this->input->post('vds'),
							
							'amount' => $this->input->post('amount'),
							'advance' => $this->input->post('advance'),
							'flow_type' => $this->input->post('flow_type'),	
							'payment_type' => $this->input->post('payment_type'),
							'supervise_by' => $this->input->post('ReportingOfficer'),
							'suggested_cheque' => $this->input->post('suggested_cheque'),			
							'created_by' => $this->session->userdata('username'),
							'loc' => $this->input->post('loc')
							
							);
	}
	
	
	
	function makeQueryinsert(){
		//$id=$this->billmodel->id();
		return $member = array(
							
							'bill_description' => $this->input->post('bill_description'),
							'bill_date' => $this->mkDate($this->input->post('bill_date')),
							'company_id' => $this->input->post('Company'),
							'vendor_code' => $this->input->post('Vendor'),
							'po_no' => $this->input->post('po_no'),
							'po_date' => $this->mkDate($this->input->post('po_date')),
							
							'gr_no' => $this->input->post('gr_no'),
							'gr_date' => $this->mkDate($this->input->post('gr_date')),
							
							'iv_no' => $this->input->post('iv_no'),
							'iv_date' => $this->mkDate($this->input->post('iv_date')),
							
							'asset_no' => $this->input->post('asset_no'),
							
							'general_deduction' => $this->input->post('general_deduction'),
							'general_deduction_note' => $this->input->post('general_deduction_note'),
							'tds' => $this->input->post('tds'),
							'vds' => $this->input->post('vds'),
							
							'amount' => $this->input->post('amount'),
							'advance' => $this->input->post('advance'),
							'flow_type' => $this->input->post('flow_type'),	
							'payment_type' => $this->input->post('payment_type'),
							'supervise_by' => $this->input->post('ReportingOfficer'),
							'suggested_cheque' => $this->input->post('suggested_cheque'),		
							'created_by' => $this->session->userdata('username'),
							'loc' => $this->input->post('loc')
							
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
		
		
		$data['company']=$this->billmodel->list_company();
		$data['vendor']=$this->billmodel->list_vendor();
		$data['costcentre']=$this->billmodel->list_costcentre();
		$data['reportingOfficerList']=$this->billmodel->list_head();
		return $data;
		
	}
	
	
	
	function billList($offset = 0,$message=''){
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		
		//Search 
		$data['action']=site_url('bill/bill/searchbill');
		$data['action2']=site_url('bill/bill/searchSpecialbill');
		$data['title']="Bill List";
		// set user message
		$data['message']=$message;
		
		if($message=='submitSupervisor')
		$data['message']="<div class='success' align=left>Submission to Supervisor Successful..!!</div>";

		
		// load data
		$bill = $this->billmodel->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/bill/billList/');
		$number_of_rows=$this->billmodel->count_all();
 		$config['total_rows'] = $number_of_rows;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/selfbillList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	
	
	function bill_table($bill)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
	
		$this->table->set_heading('id','billing&nbsp;date','location','description','sap&nbsp;id','Net&nbsp;Pay','status','action','Documents');

		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){			
		$status="";
		$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
		if($row->step_status==1)
		$status="Still Not Final Submitted";
		else if($row->step_status==2)
		{
		$hold_by=$this->billmodel->get_holdby($row->hold_by);
		$status="Processing</br>hold&nbsp;by:&nbsp;".$hold_by;	
		}
		else if($row->step_status==3)
		$status="Submitted to Audit";  
		else if($row->step_status==4 and $row->account_head_pass==0 and $row->account_pass==0 and $row->payment_type!="Adjustment" )
		$status="Submitted to Accounts";
	
		else if($row->step_status==4 and $row->account_head_pass==0 and $row->account_pass==0 and $row->payment_type=="Adjustment" )
		$status="Submitted to Account Head";
		
		else if($row->step_status==4 and $row->account_head_pass==1 and $row->account_pass==0)
		$status="Submitted to Accounts";
		
		else if($row->step_status==4 and $row->account_pass==1)
		$status="Submitted to Account Head ".$loc;
		else if($row->step_status==5)
		{
			$status="Accounts Clear";
			if($row->payment_type=="Cheque" and $row->cheque_date=="" and $row->bill_date>"2013-06-15" )
			$status="Cheque Date Still not Assigned";
		}
		else if($row->step_status==6)
		$status="Payment Made";
		
		if($row->supervise_cancel==1)
		$status="Cancel&nbsp;by&nbsp;Supervisor<br/>Reason:&nbsp;".$row->cancel_reason;		
		else if($row->authority_cancel==1)
		$status="Cancel&nbsp;by&nbsp;Authority<br/>Reason:&nbsp;".$row->cancel_reason;		
		else if($row->audit_cancel==1)
		$status="Cancel&nbsp;by&nbsp;Audit<br/>Reason:&nbsp;".$row->cancel_reason;
		
		
		
		
		if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="vendor" and $row->requisition_status==0)
			$view=anchor('bill/bill/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));
		else if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="general" and $row->requisition_status==0)
			$view=anchor('bill/general/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));
		else if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="general" and $row->requisition_status==1)
			$view=anchor('bill/billforreq/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));
		else if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="vendor" and $row->requisition_status==1)
			$view=anchor('bill/vendorforreq/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));	
		else if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="distribution")
			$view=anchor('bill/distribution/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));			
		else $view ="";
		
		
		if($row->step_status<4 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 )
		{
			if($row->bill_type=="vendor" or $row->bill_type=="distribution")
			$view.="<br/>".anchor('bill/bill/add_doc_vendor/'.$row->id,'add&nbsp;document',array('class'=>'update'));
			else if($row->bill_type=="general")  $view.="<br/>".anchor('bill/general/add_doc_general/'.$row->id,'add&nbsp;document',array('class'=>'update'));
		}
		
		
		if($row->bill_type=="vendor"){
			$viewbill=anchor('reports/reports/view_bill/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));
			$viewhistory=anchor('reports/reports/view_history/'.$row->id,'view&nbsp;history',array('class'=>'view','target'=>'about_blank'));
		}
		else if($row->bill_type=="distribution"){
			$viewbill=anchor('reports/reports/view_distribution/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));
			$viewhistory='';
		}
		
		else{
			$viewbill=anchor('reports/reports/view_general/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));	
			$viewhistory=anchor('reports/reports/view_details_history/'.$row->id,'view&nbsp;history',array('class'=>'view','target'=>'about_blank'));			
		}
		
		
		
		
		
		$viewbilldoc="";
		
		
		$doctable="<div id='".$row->id."'  class='divcost' ><font color='#FF000'><b>Double click here to view documents.</b></font></div>";
			if($row->step_status==1)
			$submitSupervisor=anchor('bill/bill/submitSupervisor/'.$row->id,'Submit&nbsp;to&nbsp;Supervisor',array('class'=>'add'));
			else $submitSupervisor="";
			
			if($row->check_ready==1 && $row->step_status==5)
			$status="&nbsp;Cheque&nbsp;is&nbsp;Ready&nbsp;";
			
			
			
			
			$viewaction=anchor('reports/reports/view_action_bill/'.$row->id,'view&nbsp;step',array('class'=>'view','target'=>'about_blank'));	
			
			$submitSupervisor="";
			
			
			//$netpay=($row->amount-$row->advance)."&nbsp;BDT";
			//if($row->bill_type=='general') 
			$netpay=($row->amount-$row->advance-$row->tds-$row->vds-$row->tot_auth_deduction-$row->general_deduction)."&nbsp;BDT";
			$this->table->add_row(
				$row->id,
				$row->bill_date,
				$loc,
				$row->bill_description,
				$row->sap_id,
				$netpay,
				//$row->superviseName,
				$status,
				$viewbill."</br>".$viewhistory."</br>".$viewaction."</br>".$view."</br>".$submitSupervisor,
				//$viewbilldoc
				$doctable
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	function add_doc_vendor($id,$filechk=1){
		
		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();
		
		$rows = $this->billmodel->get_by_id($id)->row();
		$this->validation->bill_date = 						date('d-m-Y',strtotime($rows->bill_date));
		$this->validation->Company = 						$rows->company_id;
		$this->validation->Vendor = 						$rows->vendor_code;
		$this->validation->po_no = 							$rows->po_no;
		$this->validation->po_date = 						date('d-m-Y',strtotime($rows->po_date));
		$this->validation->gr_no = 							$rows->gr_no;
		$this->validation->gr_date = 						date('d-m-Y',strtotime($rows->gr_date));
		$this->validation->iv_no = 							$rows->iv_no;
		$this->validation->iv_date = 						date('d-m-Y',strtotime($rows->iv_date));
		$this->validation->bill_description = 				$rows->bill_description;
		$this->validation->asset_no = 						$rows->asset_no;
		$this->validation->advance = 						$rows->advance;
		$this->validation->amount = 						$rows->amount;
		$this->validation->retypeamount = 					$rows->amount;
		$this->validation->payment_type = 					$rows->payment_type;
		$this->validation->ReportingOfficer = 				$rows->supervise_by;
		$this->validation->vds = 							$rows->vds;
		$this->validation->tds = 							$rows->tds;
		$this->validation->suggested_cheque = 				$rows->suggested_cheque;
		$this->validation->loc = 							$rows->loc;
		$this->validation->flow_type = 						$rows->flow_type;
		$this->validation->tot_auth_deduction = 			$rows->tot_auth_deduction;
		$this->validation->general_deduction_note = 		$rows->general_deduction_note;
		$this->validation->general_deduction = 				$rows->general_deduction;

		$items=$this->billmodel->get_documents_requisition($id)->result();		
		$data['table'] =			$this->documten_table($items,'add_doc_vendor');
		

		
			if($filechk==2)
			$data['message'] = '<div class="cancel" align="left">File Not Found..</div>';
			else if($filechk==3)
			$data['message'] = '<div class="cancel" align="left">FTP Connection Fail..</div>';
			else if($filechk==4)
			$data['message'] = '<div class="cancel" align="left">FTP Login Fail..</div>';
			else if($filechk==5)
			$data['message'] = '<div class="cancel" align="left">Please Select Document Category..</div>';
			else if($filechk==11)
			$data['message'] = '<div class="cancel" align="left">This file is already saved in the system..</div>';
			else if($filechk==21)
			$data['message'] = '<div class="cancel" align="left">Requisition not found or Already exist in another bill..</div>';
			else $data['message'] = '';
			// load view 
			
			
			
		
		
		
		$data['title'] = 'Add Extra Document';
		$data['billid'] =  $id;
		$data['action2'] =  site_url('bill/bill/addDoc/'.$id);
		$data['page']='/billView/vendor_doc_add'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		
		$this->load->view('index', $data);
		
	}
	
	function get_doc_details()
	{
		$bill_id=$this->input->post('data');
		
		$row=$this->billmodel->get_by_id($bill_id)->row();

		$viewbilldoc=""; 
		$docsl=1;
		$pchk="start";
		$pcount=1;
		$doccount=0;
		
		if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
	
		$billdoc=$this->billmodel->get_special_documents($row->id);	
		foreach($billdoc->result() as $rows)
					{ 

					$doccount=$doccount+1;
					$doc_category="(".$rows->doc_category.")";
					if(strlen($doc_category)<4)
					$doc_category="";
					
							if(strlen($rows->doc_file)<5)
							$viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));	
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/bill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.' '.$doc_category.'</a><br/>';
						$docsl=$docsl+1;
				
					}
		
		}else if($row->bill_type=="general"){
		
		$billdoc=$this->generalmodel->get_special_documents($row->id);	
		foreach($billdoc->result() as $rows)
					{ 
					$doccount=$doccount+1;
					if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/bill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					$doc_category="(".$rows->doc_category.")";
					if(strlen($doc_category)<4)
					$doc_category="";
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.$doc_category.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.$doc_category.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.$doc_category.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->detail_id;
	
					}
		
		} 
		if($doccount==0) echo "<font color='#FF000'><b>No Document Found..</font>";
		echo $viewbilldoc;
	}
	
	
	
	
	function documten_table($items,$link='a')
	{
	
	
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
	
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){
			$del='';
			if($link!='add_doc_vendor')
			$del='&nbsp;'.anchor('bill/bill/deleteDoc/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"))."&nbsp;&nbsp;<b>Category:</b>&nbsp;".$row->doc_category;
			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			//$view='<a href="ftp://billmaster:stargold@192.168.1.117/Common_share/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$view='<a href="'.base_url().'index.php/bill/bill/url/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$doc_file=$row->doc_file;
			if($row->doc_file=="0")
			{
				$doc_file="Requisition";
				$view=anchor('reports/reports/view_material_list/'.$row->id,'view',array('class'=>'view','target'=>'about_blank'));	
				$del='&nbsp;'.anchor('bill/bill/deleteRequisition/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')")).'&nbsp;&nbsp;<b>Category:</b>&nbsp;Requisition';
			}
			
			
			
			$this->table->add_row(				
				$sl,
				$doc_file,
				$view.'&nbsp;'.$del
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	
	
	function searchbill(){

		//Search 
		$data['action']=site_url('bill/bill/searchbill');
		$data['action2']=site_url('bill/bill/searchSpecialbill');
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->billmodel->get_search_by_id($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->billmodel->count_search($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')));
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/selfbillList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function searchSpecialbill(){

		//Search 
		$data['action']=site_url('bill/bill/searchbill');
		$data['action2']=site_url('bill/bill/searchSpecialbill');
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->billmodel->get_special_search_by_id($this->input->post('stype'),$this->input->post('svalue'))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->billmodel->count_search_special($this->input->post('stype'),$this->input->post('svalue'));
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/selfbillList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
		
	
	}
	
	
	
	
	
	
	function delete($id){	
		$this->billmodel->delete($id);
		redirect('bill/bill/billList/0/del');
		
	}
	
	function submitSupervisor($id){	
		$this->billmodel->submitSupervisor($id);
		redirect('bill/bill/billList/0/submitSupervisor');
		
	}
	
	
	
	function deleteDoc($id1,$id2){
		$this->billmodel->deleteDoc($id1);

		redirect('bill/bill/updatebill/'.$id2, 'location');
	}
	
	
	
	
	function updatebill($id,$filechk=1){
		
		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();
		
		$rows = $this->billmodel->get_by_id($id)->row();
		$this->validation->bill_date = 						date('d-m-Y',strtotime($rows->bill_date));
		$this->validation->Company = 						$rows->company_id;
		$this->validation->Vendor = 						$rows->vendor_code;
		$this->validation->po_no = 							$rows->po_no;
		$this->validation->po_date = 						date('d-m-Y',strtotime($rows->po_date));
		$this->validation->gr_no = 							$rows->gr_no;
		$this->validation->gr_date = 						date('d-m-Y',strtotime($rows->gr_date));
		$this->validation->iv_no = 							$rows->iv_no;
		$this->validation->iv_date = 						date('d-m-Y',strtotime($rows->iv_date));
		$this->validation->bill_description = 				$rows->bill_description;
		$this->validation->asset_no = 						$rows->asset_no;
		$this->validation->advance = 						$rows->advance;
		$this->validation->amount = 						$rows->amount;
		$this->validation->retypeamount = 					$rows->amount;
		$this->validation->payment_type = 					$rows->payment_type;
		$this->validation->ReportingOfficer = 				$rows->supervise_by;
		$this->validation->vds = 							$rows->vds;
		$this->validation->tds = 							$rows->tds;
		$this->validation->suggested_cheque = 				$rows->suggested_cheque;
		$this->validation->loc = 							$rows->loc;
		$this->validation->flow_type = 						$rows->flow_type;
		$this->validation->tot_auth_deduction = 			$rows->tot_auth_deduction;
		$this->validation->general_deduction_note = 		$rows->general_deduction_note;
		$this->validation->general_deduction = 				$rows->general_deduction;
		$this->validation->step_status = 					$rows->step_status;
		$this->validation->az_status = 					   	$rows->az_status;
		
		$data=$this->getListData();
		
		$data['openmodel'] = 		'yes';
		$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->billmodel->tot_document_by_bill_id($id);
		$items=$this->billmodel->get_documents_requisition($id)->result();		
		$data['table'] =			$this->documten_table($items);
		
		// run validation
		if ($this->validation->run() == FALSE){
		
			if($filechk==2)
			$data['message'] = '<div class="cancel" align="left">File Not Found..</div>';
			else if($filechk==3)
			$data['message'] = '<div class="cancel" align="left">FTP Connection Fail..</div>';
			else if($filechk==4)
			$data['message'] = '<div class="cancel" align="left">FTP Login Fail..</div>';
			else if($filechk==5)
			$data['message'] = '<div class="cancel" align="left">Please Select Document Category..</div>';
			else if($filechk==11)
			$data['message'] = '<div class="cancel" align="left">This file is already saved in the system..</div>';
			else if($filechk==21)
			$data['message'] = '<div class="cancel" align="left">Requisition not found or Already exist in another bill..</div>';
			else $data['message'] = '';
			// load view 
			
			
			
		}
		else if($this->input->post('amount')!=$this->input->post('retypeamount')){
			$data['message'] = '<div class="cancel" align=left>Please Check Amount</div>';
		}
		else if($this->input->post('payment_type')=='Cheque' and $this->input->post('suggested_cheque')==''  ){
			$data['message'] = 			'<div class="cancel" align=left>please input Cheque Name..</div>';
		
		}
		else{
			// save data
		
			$this->billmodel->update($id,$this->makeQuery());
	
			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}
		
		
		$data['title'] = 'Claim Vendor Bill';
		$data['action'] = site_url('bill/bill/updatebill/'.$id);
		$data['action2'] =  site_url('bill/bill/addDoc/'.$id);
		$data['action3'] =  site_url('bill/bill/submitSupervisor/'.$id);
		$data['page']='/billView/billEdit'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['entry']=1;
		$data['recommend']=0;
		$this->load->view('index', $data);
		
	}
	
	function addDoc($id)
	{
		
		$linkforward="updatebill";
		
		if($this->input->post('extra_doc')=="add_doc_vendor")
		$linkforward="add_doc_vendor";
		
		
		if(strlen($this->input->post('doc_category'))<2)
		redirect('bill/bill/'.$linkforward.'/'.$id.'/5', 'location');	
		
		
		
		if($this->input->post('requisition')!="")
		{
			
			$requisitionChk= $this->billmodel->requisitionChk($this->input->post('requisition'));
			
			if($requisitionChk==0){						
				redirect('bill/bill/'.$linkforward.'/'.$id.'/21', 'location');			
			}
			else{
				$this->billmodel->requisitionUpdate($id,$this->input->post('requisition'));
				redirect('bill/bill/'.$linkforward.'/'.$id.'/1', 'location');
			}
		}	
		
		
		
		$ftp_server = "192.168.1.117";
		$conn_id = ftp_connect ($ftp_server)
			or redirect('bill/bill/'.$linkforward.'/'.$id.'/3', 'location');
		   
		$login_result = ftp_login($conn_id, "bill", "bill007");
		if ((!$conn_id) || (!$login_result))
			redirect('bill/bill/'.$linkforward.'/'.$id.'/4', 'location'); 
		
		
		
		
		$str = str_replace( '\\', '/', $this->input->post('doc_file') ); 
		$str = trim($str," ");
		
		$doubleFileChk=$this->billmodel->doubleFileChk($str);
		if($doubleFileChk>0)
		redirect('bill/bill/'.$linkforward.'/'.$id.'/11', 'location');
		
	
		
		$pizza  = $str;
		$pieces = explode("/", $pizza);
		$piecescount = count($pieces);
		
		
		if($piecescount!=2)
		{	ftp_close($conn_id); 
			redirect('bill/bill/'.$linkforward.'/'.$id.'/2', 'location');
		}
		
		
		$path = "/BILL/".$pieces[0]."/"; 
		ftp_pasv($conn_id, true);
    	$contents_on_server = ftp_nlist($conn_id, $path);
		ftp_close($conn_id); 
		
		if(in_array($pieces[1], $contents_on_server) and $this->input->post('doc_file')!="" )
		{
		
		$this->billmodel->saveDoc(			
		array(
		'doc_file' => $str ,
		'doc_category' => $this->input->post('doc_category'),
		'doc_id' => $id)
		);
			redirect('bill/bill/'.$linkforward.'/'.$id.'/1', 'location');
		}
		else redirect('bill/bill/'.$linkforward.'/'.$id.'/2', 'location');
		
		
		
	}
	
	
	function url3($url1,$url2)
	{	
		//redirect('ftp://billmaster:stargold@192.168.1.117/Common_share/'.$url, 'refresh');
		$location='location: ftp://bill:bill007@192.168.1.117/BILL/'.$url1.'/'.$url2;
	
		header($location);
	}
	
	
	function url($url1,$url2)
	{	
		//redirect('ftp://billmaster:stargold@192.168.1.117/Common_share/'.$url, 'refresh');
		$location='location: ftp://bill:bill007@192.168.1.117/BILL/'.$url1.'/'.$url2;
		$chkjpg=strtoupper($url2);
		if(strpos( $chkjpg , 'JPG' ) ) 
		{

			//header('Content-Type: image/jpeg');
		
			echo  '<img style="width:900px;" src="data:image/jpeg;Base64,'.base64_encode(file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/".$url1.'/'.$url2)).'"/>';
		}/*
		else if(strpos( $chkjpg , 'PDF' ) ) 
		{

			//header('Content-Type: image/jpeg');
		
		
			echo  '<embed type="application/pdf" src="'.base64_encode(file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/".$url1.'/'.$url2)).'"/>';
		}*/
		else{
			// header($location);	
			$this->load->helper('download');

			$data = file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/".$url1.'/'.$url2); 
			$name = $url2;

			force_download($name, $data);			
		}
	}
	
	
	function deleteRequisition($id1,$id2){
		$this->generalmodel->requisitionDelete($id1);
		

		redirect('bill/bill/updatebill/'.$id2, 'location');
	}
	

}
