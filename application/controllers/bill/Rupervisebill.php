<?php
Class Supervisebill extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function Supervisebill(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));		
		// load model
		$this->load->model('/billsupervisemodel','',TRUE);
		$this->load->model('/billauditmodel','',TRUE);
		$this->load->model('/generalmodel','',TRUE);
		$this->load->model('/billmodel','',TRUE);
		
		if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') != 5 AND $this->session->userdata('authlevel') != 6) {
	
		show_401();
	}
	
	}
	 
	function Index(){
		

		
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
		$data['docs']=$this->billsupervisemodel->get_documents($id);
		$this->load->view('billView/viewDocs', $data);
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
							'suggested_cheque' => $this->input->post('suggested_cheque'),
							
							'payment_type' => $this->input->post('payment_type'),
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
		
		
		$data['company']=$this->billsupervisemodel->list_company();
		$data['vendor']=$this->billsupervisemodel->list_vendor();
		$data['costcentre']=$this->billsupervisemodel->list_costcentre();		
		return $data;
		
	}
	
	
	
	function billList($offset = 0,$message=''){
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		
		//Search 
		$data['action']=site_url('bill/supervisebill/searchbill');
		$data['action2']=site_url('bill/supervisebill/searchSpecialbill');
		
		
		$data['title']="bill List";
		// set user message
		$data['message']=$message;
		
		if($message=='submitAuthority')
		$data['message']="<div class='success' align=left>Submitted Successful..!!</div>";
		else if($message=='resubmit')
		$data['message']="<div class='success' align=left>Resubmit Successful..!!</div>";
		else if($message=='cancel')
		$data['message']="<div class='cancel' align=left>Cancel Successful..!!</div>";
		else if($message=='dupAction')
		$data['message']="<div class='cancel' align=left>You have already made an action for this bill!</div>";
		
		// load data
		$bill = $this->billsupervisemodel->get_paged_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/supervisebill/billList/');
		$number_of_rows=$this->billsupervisemodel->count_all();
 		$config['total_rows'] = $number_of_rows;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/superviseList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	
	
	function bill_table($bill)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id','billing&nbsp;date','location','description','sap&nbsp;id','Net&nbsp;Pay','Submitted&nbsp;by','status','last&nbsp;comment','action','documents');

		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){

		$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";	
	
		if($row->step_status==2)
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
		
		
			if($row->step_status==2 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0

			and ($row->hold_by==$this->session->userdata('username')  or (  ( $row->hold_by==2430 or $row->hold_by==3  ) and $row->company_id==21 )   )
			
			)
			{
			
				if($row->bill_type=="general" and $row->requisition_status==1){
					$view=anchor('bill/billforreq/updatebill/'.$row->id,'process&nbsp;bill',array('class'=>'update'));	
				
				}
				else if($row->bill_type=="general" and $row->requisition_status==0){
					$view=anchor('bill/general/updatebill/'.$row->id,'process&nbsp;bill',array('class'=>'update'));	
				
				}
				else if($row->bill_type=="vendor" and $row->requisition_status==1){
					$view=anchor('bill/vendorforreq/updatebill/'.$row->id,'process&nbsp;bill',array('class'=>'update'));	
				
				}
				else if($row->bill_type=="distribution")
				{
				$view=anchor('bill/distribution/updatebill/'.$row->id,'process&nbsp;bill',array('class'=>'update'));	
				}				
				else
				{
					$view=anchor('bill/supervisebill/updatebill/'.$row->id,'process&nbsp;bill',array('class'=>'update'));
				}
			

				$view.="<br/>".anchor('bill/supervisebill/resubmitEmp/'.$row->id,'Resubmit&nbsp;to&nbsp;Employee',array('class'=>'add'));
				
			$cancel=anchor('bill/supervisebill/cancel/'.$row->id,'cancel',array('class'=>'cancel',
			'onclick'=>"return confirm('Are you sure want to cancel?')"));	
			}
			else {$view ="";
			$cancel="";
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
		

			
	
			$viewbilldoc="";  /*
			$docsl=1;
			$pchk="start";
		$pcount=1;
		
		if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
		$billdoc=$this->billmodel->get_special_documents($row->id);		
		foreach($billdoc->result() as $rows)
					{ 

					
					if(strlen($rows->doc_file)<5)
							$viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));	
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/bill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
						$docsl=$docsl+1;
					}
		
		}else if($row->bill_type=="general"){
		$billdoc=$this->generalmodel->get_special_documents($row->id);
		foreach($billdoc->result() as $rows)
					{ 

						if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/bill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
					
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->detail_id;
					}  
		
		} */
			$doctable="<div id='".$row->id."'  class='divcost' ><font color='#FF000'><b>Double click here to view documents.</b></font></div>";

			$viewaction=anchor('reports/reports/view_action_bill/'.$row->id,'view&nbsp;step',array('class'=>'view','target'=>'about_blank'));	
			
			
			$empName=$this->billmodel->get_holdby($row->created_by);
			
			//$netpay=($row->amount-$row->advance)."&nbsp;BDT";
			//if($row->bill_type=='general')
			$netpay=($row->amount-$row->advance-$row->tds-$row->vds-$row->tot_auth_deduction)."&nbsp;BDT";
			
			$rowcmt = $this->billsupervisemodel->last_comment($row->id);
			
			if($rowcmt->num_rows()==1)
			$last_comment=$rowcmt->row()->last_comment;
			else $last_comment='';
		
			$investigation=anchor('reports/reports/investigation/'.$row->id,'investigation',array('class'=>'view','target'=>'about_blank'));

		
			$this->table->add_row(
				$row->id,
				$row->bill_date,
				$loc,
				$row->bill_description,
				$row->sap_id,
				$netpay,
				$empName,
				$status,
				$last_comment,
				$viewbill."</br>".$viewhistory."</br>".$viewaction."</br>".$view."</br>".$cancel."</br>".$investigation,
				//$viewbilldoc,
				$doctable
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	
	function get_doc_details()
	{
		$bill_id=$this->input->post('data');
		
		$row=$this->billsupervisemodel->get_by_id($bill_id)->row();

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
	
	function resubmitEmp($id){
		
			$data['message'] = '';
			$data['comment'] = '';
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'Resubmit Reason';
			$data['action'] =  site_url('bill/supervisebill/resubmitToEmp/'.$id.'/');
			$data['page']='/billView/comment'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	
	
	function resubmitToEmp($id){	
	
		$this->billsupervisemodel->ReSubmitEmp($id);
		$IPTrack=$_SERVER['REMOTE_ADDR'];	
			
			$this->billsupervisemodel->action_doc(
					array(							
							'doc_id' =>$id,'action_ip' =>$IPTrack,'action' =>'Resubmit','user_id' => $this->session->userdata('username'),'comment' =>$this->input->post('comment')						
						 )
					);
			
			redirect('bill/supervisebill/billList/0/resubmit');
		
		
	}
	
	function documten_table($items)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");

		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){

			$del='&nbsp;'.anchor('bill/supervisebill/deleteDoc/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"))."&nbsp;&nbsp;<b>Category:</b>&nbsp;".$row->doc_category;
			//$view='&nbsp;'.anchor('bill/supervisebill/viewDoc/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			//$view='<a href="ftp://billmaster:stargold@192.168.1.117/Common_share/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$view='<a href="'.base_url().'index.php/bill/bill/url/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$doc_file=$row->doc_file;
			if($row->doc_file=="0")
			{
				$doc_file="Requisition";
				$view=anchor('reports/reports/view_material_list/'.$row->id,'view',array('class'=>'view','target'=>'about_blank'));	
				$del='&nbsp;'.anchor('bill/supervisebill/deleteRequisition/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')")).'&nbsp;&nbsp;<b>Category:</b>&nbsp;Requisition';
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
		$data['action']=site_url('bill/supervisebill/searchbill');
		$data['action2']=site_url('bill/supervisebill/searchSpecialbill');
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->billsupervisemodel->get_search_by_id($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->billsupervisemodel->count_search($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')));
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/superviseList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
		
	function searchSpecialbill(){

		//Search 
		$data['action']=site_url('bill/supervisebill/searchbill');
		$data['action2']=site_url('bill/supervisebill/searchSpecialbill');
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->billsupervisemodel->get_special_search_by_id($this->input->post('stype'),$this->input->post('svalue'))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->billsupervisemodel->count_search_special($this->input->post('stype'),$this->input->post('svalue'));
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/superviseList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	

	
	function FinalSubmit($id){	
			
			$usercode=$this->billsupervisemodel->user_code();
			
			$compchk=$this->billsupervisemodel->get_by_id($id)->row()->company_id;
			
		if ( in_array($compchk, array(7) ))
		{	
		
			if($this->session->userdata('username')!=2405 and $this->session->userdata('username')!="003")
			{
		
				$usercode=4;
							
			}
			
		}
		
		
		if ( in_array($compchk, array(1,2,3,8,9) ))
		{	
		
			if($this->session->userdata('username')!=2346 and $this->session->userdata('username')!="003" 
			and $this->input->post('ReportingOfficer')!="audit" and   $this->session->userdata('username')!=1970 
			)
			{
		
				$usercode=4;
							
			}
			
		}
			
			
			
			
			
			
			
			if($this->billsupervisemodel->checkDuplicatePass($id)==2)
			redirect('bill/supervisebill/billList/0/dupAction');
			
		
			
			if($this->input->post('ReportingOfficer')!="")
			{
				$chk_supervise_comment= $this->billsupervisemodel->chk_supervise_comment($id);
				$chk_authority_comment= $this->billsupervisemodel->chk_authority_comment($id);
				$chk_high_authority_comment= $this->billsupervisemodel->chk_high_authority_comment($id);
				$chk_super_authority_comment= $this->billsupervisemodel->chk_super_authority_comment($id);
				$chk_ceo_comment= $this->billsupervisemodel->chk_ceo_comment($id);
				$chk_finance_head_comment= $this->billsupervisemodel->chk_finance_head_comment($id);
				
				
				
				if($chk_supervise_comment==1)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'supervise_comment' =>$this->input->post('comment')					
						 )
					);
				}
				else if($chk_authority_comment==1)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'auth_comment' =>$this->input->post('comment')					
						 )
					);
				}
				else if($chk_high_authority_comment==1)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'high_auth_comment' =>$this->input->post('comment')					
						 )
					);
				}
				else if($chk_finance_head_comment==1)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'finance_head_comment' =>$this->input->post('comment')					
						 )
					);
				}
				else if($chk_ceo_comment==1)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'ceo_comment' =>$this->input->post('comment')					
						 )
					);
				}
				else if($chk_super_authority_comment==1)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'super_auth_comment' =>$this->input->post('comment')					
						 )
					);
				}	
				
				
				
				
				
				if($usercode==4)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'hold_by' =>$this->input->post('ReportingOfficer'),'authority_by' =>$this->input->post('ReportingOfficer')					
						 )
					);
					
				
				}
				else if($usercode==3 and $this->input->post('ReportingOfficer')==1495)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'hold_by' =>$this->input->post('ReportingOfficer'),'finance_head_by' =>$this->input->post('ReportingOfficer')					
						 )
					);
					
				
				}
				
				else if($usercode==3 and $this->input->post('ReportingOfficer')!="audit" and $this->input->post('ReportingOfficer')!="fin"  and $this->input->post('ReportingOfficer')!="account")
				{
						
					$authcode=$this->billsupervisemodel->user_code_by_id($this->input->post('ReportingOfficer'));
					
					if($authcode==3)
					{
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>$this->input->post('ReportingOfficer'),'authority_by' =>$this->input->post('ReportingOfficer')					
							 )
						);
					}
					else{
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>$this->input->post('ReportingOfficer'),'high_authority_by' =>$this->input->post('ReportingOfficer')					
							 )
						);
					}
					
				
				}
				else if($usercode==2 and $this->input->post('ReportingOfficer')==1495)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'hold_by' =>$this->input->post('ReportingOfficer'),'finance_head_by' =>$this->input->post('ReportingOfficer')					
						 )
					);
					
					
				}
			
				
				else if($usercode==2 and $this->input->post('ReportingOfficer')==3)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'hold_by' =>$this->input->post('ReportingOfficer'),'super_authority_by' =>$this->input->post('ReportingOfficer')					
						 )
					);
					
				
				}	
				
				
				else if($usercode==2 and $this->input->post('ReportingOfficer')==1970)
				{
					$this->billsupervisemodel->FinalSubmit($id,
					array(							
							'hold_by' =>$this->input->post('ReportingOfficer'),'ceo_by' =>$this->input->post('ReportingOfficer')					
						 )
					);
					
				
				}	


				
				//echo $this->input->post('ReportingOfficer');
				//return 0;

				
				else if($this->input->post('ReportingOfficer')=="audit")
				{
					
					/*
					$flow_type=$this->billsupervisemodel->get_by_id($id)->row()->flow_type;
					
					if($flow_type=="PASC")
					{
						
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>'audit','step_status' =>4					
							 )
						);
					
					}
					else
					{
						
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>'audit','step_status' =>3					
							 )
						);
					
					}
					*/
					
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>'audit','step_status' =>3					
							 )
						);
					
				
					
				}
				else if($this->input->post('ReportingOfficer')=="account")
				{
						$blrow=$this->billsupervisemodel->get_by_id($id)->row();
						
						if(($blrow->company_id==4 or $blrow->company_id==12 or $blrow->company_id==22 or $blrow->company_id==23) and $blrow->hill_status==1 )
						{
						
							$this->billsupervisemodel->FinalSubmit($id,
							array(							
									'hold_by' =>'account','step_status' =>5					
								 )
							);
							//$this->billsupervisemodel->chkVendor($id);
							
						
						
						}
						
						else if($blrow->company_id==1 or $blrow->company_id==3 )
						{
						
							$this->billsupervisemodel->FinalSubmit($id,
							array(							
									'hold_by' =>'account','step_status' =>4				
								 )
							);
							$this->billsupervisemodel->chkVendor($id);
							
						
						
						}
						
						else{
						
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>'account','step_status' =>4,'account_head_pass' => 1					
							 )
						);
						$this->billsupervisemodel->chkVendor($id);
						
						
					}
				
					
				}
				else if($this->input->post('ReportingOfficer')=="CGO")
					{
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>2023,'high_authority_by' =>2023				
							 )
						);
						
						/*
						$this->billsupervisemodel->action_doc(
						array(							
								'doc_id' =>$id,'action' =>'accepted','user_id' => $this->session->userdata('username')	,'comment' =>$this->input->post('comment')					
							 )
						);
						*/
				
					}
					
					else if($this->input->post('ReportingOfficer')==1085)
					{
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>1085,'high_authority_by' =>1085				
							 )
						);
						$this->billsupervisemodel->action_doc(
						array(							
								'doc_id' =>$id,'action' =>'accepted','user_id' => $this->session->userdata('username')	,'comment' =>$this->input->post('comment')					
							 )
						);
				
					}
					
				else if($this->input->post('ReportingOfficer')=="director")
					{
	
						$this->billsupervisemodel->FinalSubmit($id,
						array(							
								'hold_by' =>003,'super_authority_by' =>003					
							 )
						);
						
						
						/*
						$this->billsupervisemodel->action_doc(
						array(							
								'doc_id' =>$id,'action' =>'accepted','user_id' => $this->session->userdata('username')	,'comment' =>$this->input->post('comment')					
							 )
						);
						*/
						
			
					}	
					
			

				
				else if($this->input->post('ReportingOfficer')=="fin")
					{
						if($compchk==6 or $compchk==21)
						$this->billauditmodel->SubmitFinNew($id);
						else 	
						$this->billauditmodel->SubmitFin($id);
						
					
			
			
					}
			
					
				
				else if($this->input->post('ReportingOfficer')=="claimer")
					{
						$this->billauditmodel->ReSubmitEmp($id);
					
						$this->billauditmodel->action_doc(
						array(							
								'doc_id' =>$id,'action' =>'Returned','user_id' => $this->session->userdata('username')	,'comment' =>$this->input->post('comment')					
							 )
						);
						
					}	
				
				
				
				$comment= $this->input->post('comment');
				
				if($this->input->post('auth_deduct')>0)	{
				 $comment= "Amount Deducted: ".$this->input->post('auth_deduct')."<br/>".$comment;
				}
				$IPTrack=$_SERVER['REMOTE_ADDR'];
				$this->billsupervisemodel->action_doc(
					array(							
							'doc_id' =>$id,'action' =>'accepted','action_ip' =>$IPTrack,'user_id' => $this->session->userdata('username'),'comment' =>$comment							
						 )
					);
					
				if($this->input->post('auth_deduct')>0)	{
				$this->billsupervisemodel->save_mis_document_auth_deduction($id,
					array(							
							'doc_id' =>$id,'auth_deduct_amt' =>$this->input->post('auth_deduct'),'user_id' => $this->session->userdata('username'),'comment' =>$this->input->post('comment')							
						 )
					);
				}
				
				
				
				
				redirect('bill/supervisebill/billList/0/submitAuthority');
			}
			else redirect('bill/supervisebill/updatebill/'.$id.'/3', 'location');
			
			/*
			
			$this->billsupervisemodel->FinalSubmit($id,
			array(							
					'supervise_comment' =>$this->input->post('comment'),'hold_by'=>	$this->input->post('ReportingOfficer')						
				 )
			);		
		
			*/
		
	}
	
	
	
	
	
	function deleteDoc($id1,$id2){
		$this->billsupervisemodel->deleteDoc($id1);

		redirect('bill/supervisebill/updatebill/'.$id2, 'location');
	}
	
	
	
	
	function updatebill($id,$filechk=1){
		
		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();
		
		$rows = $this->billsupervisemodel->get_by_id($id)->row();
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

		$this->validation->vds = 							$rows->vds;
		$this->validation->tds = 							$rows->tds;
		$this->validation->suggested_cheque = 				$rows->suggested_cheque;
		$this->validation->loc = 							$rows->loc;
		$this->validation->flow_type = 						$rows->flow_type;
		$this->validation->general_deduction_note = 		$rows->general_deduction_note;
		$this->validation->general_deduction = 				$rows->general_deduction;
		$this->validation->step_status = 					$rows->step_status;
		$this->validation->az_status = 					   	$rows->az_status;

		$data=$this->getListData();
		
		$data['openmodel'] = 		'yes';
		$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->billsupervisemodel->tot_document_by_bill_id($id);
		//$items=$this->billsupervisemodel->get_documents($id)->result();
		$items=$this->billmodel->get_documents_requisition($id)->result();			
		$data['table'] =			$this->documten_table($items);
		
		// run validation
		if ($this->validation->run() == FALSE){
		
			
			if($filechk==2)
			$data['message'] = '<div class="cancel" align="left">File Not Found..</div>';
			else if($filechk==3)
			$data['message'] = '<div class="cancel" align="left">Please Select Reporting To..</div>';
			else if($filechk==4)
			$data['message'] = '<div class="cancel" align="left">FTP Login Fail..</div>';
			else if($filechk==5)
			$data['message'] = '<div class="cancel" align="left">FTP Connection Fail..</div>';
			else if($filechk==11)
			$data['message'] = '<div class="cancel" align="left">This file is already saved in the system..</div>';
			else $data['message'] = '';
			
			
			
		}
		else if($this->input->post('amount')!=$this->input->post('retypeamount')){
			$data['message'] = '<div class="cancel" align=left>Please Check Amount</div>';
		}
		else{
			// save data
		
			$this->billsupervisemodel->update($id,$this->makeQuery());
	
			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}
		
		
		$data['title'] = 'Process Vendor Bill';
		$data['action'] = site_url('bill/supervisebill/updatebill/'.$id);
		$data['action2'] =  site_url('bill/supervisebill/addDoc/'.$id);
		$data['action3'] =  site_url('bill/supervisebill/FinalSubmit/'.$id);
		$data['reportingOfficerList']=$this->billsupervisemodel->list_head($this->validation->amount, $this->validation->Company );
		

		$data['usercode']=$this->billsupervisemodel->user_code();
		
		$dep_code=$this->billsupervisemodel->dep_code_by_id( $this->session->userdata('username'));
	
	
		if ( in_array($this->validation->Company, array(7) ))
		{	
		
			if($this->session->userdata('username')!=2405 and $this->session->userdata('username')!="003")
			{
				
				$data['usercode']=4;
							
			}
		
			
			if($dep_code==0)
			$data['reportingOfficerList']=$this->billsupervisemodel->list_dep_head();
			else if($dep_code==1 and $this->session->userdata('username')!=2405 and $this->session->userdata('username')!="003")
			{
				$data['reportingOfficerList']=$this->billsupervisemodel->list_fin_head();
				$data['usercode']=4;
							
			}
			
		}
		
		
		
		
		if ( in_array($this->validation->Company, array(2,8,9) ))
		{	
		
			if($this->session->userdata('username')!=2346 and $this->session->userdata('username')!="003")
			{
				
				$data['usercode']=4;
							
			}
		
			
			if($dep_code==0)
			$data['reportingOfficerList']=$this->billsupervisemodel->list_dep_head();
			else if($dep_code==1 and $this->session->userdata('username')!=2346 and $this->session->userdata('username')!="003")
			{
				
					//echo "hello2";
					
				if($this->validation->amount>10000)			
				$data['reportingOfficerList']=$this->billsupervisemodel->list_fin_head_second();
				else  $data['reportingOfficerList']=$this->billsupervisemodel->list_sel_audit();
				$data['usercode']=4;
							
			}
			
		}
		
		if ( in_array($this->validation->Company, array(1,3) ))
		{	
		
			if( $this->session->userdata('username')!="003")
			{
				
				$data['usercode']=4;
							
			}
		
			
			if($dep_code==0)
			$data['reportingOfficerList2']=$this->billsupervisemodel->list_dep_head();
			else if($dep_code==1 and $this->session->userdata('username')!=2346 
			
			and $this->session->userdata('username')!=1970 
			and $this->session->userdata('username')!="003")
			{
				
			//echo "hello2";
					
			/* 	if($this->validation->amount>10000)			
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_fin_head_second();
				else  $data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();
				$data['usercode']=4; */
					
				$data['reportingOfficerList']=$this->billsupervisemodel->list_sel_audit();	
				
							
			}
			
			else if($rows->finance_head_by!=2346 and $this->session->userdata('username')==2346 )
			{
				$data['reportingOfficerList']=$this->billsupervisemodel->list_sel_audit();					
			}
			
			
			else if($rows->finance_head_by==2346 and $this->session->userdata('username')==2346 )
			{
				
				
				if($this->validation->amount>50000  and  $rows->supervise_by!=1970  )				
				$data['reportingOfficerList']=$this->billsupervisemodel->list_coo_head();
				else $data['reportingOfficerList']=$this->billsupervisemodel->list_sel_account();					
			}
			
			else if($rows->ceo_by!=1970 and $this->session->userdata('username')==1970 )
			{
				$data['reportingOfficerList']=$this->billsupervisemodel->list_sel_audit();					
			}
			
			else if($rows->ceo_by==1970 and $this->session->userdata('username')==1970 )
			{
				$data['reportingOfficerList']=$this->billsupervisemodel->list_sel_account();
				if($rows->audit_approved_date==null or $rows->audit_approved_date=="")
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();		
			}
	
			
			
			
			
			
			
			/* else if($this->validation->amount>50000 and $this->session->userdata('username')==2346 
			and $rows->supervise_by!=1970 and $rows->authority_by!=1970 and $rows->high_authority_by!=1970 )
			{
				
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_coo_head();
									
			}
			
			else if($this->validation->amount>50000 and $this->session->userdata('username')==1970 
			and( $rows->supervise_by==2346 or $rows->authority_by==2346 or $rows->high_authority_by==2346 
			or $rows->finance_head_by==2346 ))
			{
				
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();
									
			}
			
			else if($this->session->userdata('username')==1970 )
			{
				
				if($this->validation->amount>10000)			
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_fin_head_second();
				else  $data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();
				$data['usercode']=4;
									
			} */
			
		}
		
		
		
		
		
		$data['page']='/billView/billEdit'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		
		
		$data['entry']=0;
		$data['recommend']=1;
		$this->load->view('index', $data);
		
	}
	

	
	function addDoc($id)
	{
		
		
		$ftp_server = "192.168.1.117";
		$conn_id = ftp_connect ($ftp_server)
			or redirect('bill/supervisebill/updatebill/'.$id.'/5', 'location');
		   
		$login_result = ftp_login($conn_id, "bill", "bill007");
		if ((!$conn_id) || (!$login_result))
			redirect('bill/supervisebill/updatebill/'.$id.'/4', 'location'); 
		
		
		
		
		$str = str_replace( '\\', '/', $this->input->post('doc_file') ); 
		$doubleFileChk=$this->billsupervisemodel->doubleFileChk($str);
		if($doubleFileChk>0)
		redirect('bill/supervisebill/updatebill/'.$id.'/11', 'location');
		
		
		$pizza  = $str;
		$pieces = explode("/", $pizza);
		$piecescount = count($pieces);
		
		if($piecescount!=2)
		{	ftp_close($conn_id); 
			redirect('bill/supervisebill/updatebill/'.$id.'/2', 'location');
		}
		
		
		$path = "/BILL/".$pieces[0]."/"; 
        ftp_pasv($conn_id, true);
		$contents_on_server = ftp_nlist($conn_id, $path);
		ftp_close($conn_id); 
		
		if(in_array($pieces[1], $contents_on_server) and $this->input->post('doc_file')!="" )
		{
		
		$this->billsupervisemodel->saveDoc(			
		array(
		'doc_file' => $str ,
		'doc_id' => $id)
		);
			redirect('bill/supervisebill/updatebill/'.$id.'/1', 'location');
		}
		else redirect('bill/supervisebill/updatebill/'.$id.'/2', 'location');
		
	}
	
	/*
	function cancel($id){	
		$this->billsupervisemodel->cancel($id);
		redirect('bill/supervisebill/billList');
		
	}
	*/
	
	function cancel($id){
		
			$data['message'] = '';
			$data['comment'] = '';
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'Cancel Reason';
			$data['action'] =  site_url('bill/supervisebill/canceladd/'.$id.'/');
			$data['page']='/billView/comment'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	
	
	function canceladd($id){
			$chk_supervise_comment= $this->billsupervisemodel->chk_supervise_comment($id);
			
			if($chk_supervise_comment==1){
			$this->billsupervisemodel->cancel($id,
			array(
							'supervise_cancel' => 1,
							
							'cancel_reason' =>$this->input->post('comment')
							
							)
			);	
			}else{
			
				$this->billsupervisemodel->cancel($id,
				array(
								'authority_cancel' => 1,
								
								'cancel_reason' =>$this->input->post('comment')
								
								)
				);	
		
			}
			$IPTrack=$_SERVER['REMOTE_ADDR'];
				$this->billsupervisemodel->action_doc(
					array(							
							'doc_id' =>$id,'action_ip' =>$IPTrack,'action' =>'cancel','user_id' => $this->session->userdata('username'),'comment' =>$this->input->post('comment')						
						 )
					);
			
			redirect('bill/supervisebill/billList/0/cancel');
			

	}
	
	
	function deleteRequisition($id1,$id2){
		$this->generalmodel->requisitionDelete($id1);
		

		redirect('bill/supervisebill/updatebill/'.$id2, 'location');
	}


}
