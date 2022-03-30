<?php
Class Distribution extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function __construct(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));		
		// load model
		$this->load->model('/distributionmodel','',TRUE);
		$this->load->model('/billsupervisemodel','',TRUE);
		$this->load->model('/generalmodel','',TRUE);
		$this->load->model('/billmodel','',TRUE);
		if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') != 5 AND $this->session->userdata('authlevel') != 6 AND $this->session->userdata('authlevel') != 9) {
	
		show_401();
	}
	
	}
	 
	function Index(){
		
		$data=$this->getListData();
		$this->_set_fields();
		$this->validation->bill_date = 	date('d-m-Y');
		$data['title'] = 'Claim Distribution Bill';
		$data['message'] = '';
		$data['openmodel'] = 'no';
		$data['action'] = site_url('bill/distribution/add');
		$data['page']='/billView/distributionEdit'; 
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->validation->advance = 0;
		$data['entry']=1;
		$data['recommend']=0;
		$this->load->view('index', $data);
		
	}
	
	function _set_fields(){
		
		$fields['tot_auth_deduction'] = '';
		$fields['bill_date'] = 'bill date';
		$fields['Company'] = 'Company';	
		$fields['loc'] = 'Location';	
		$fields['bill_description'] = 'bill description';
		$fields['payment_type'] = 'payment_type';
		$fields['doc_file'] = 'doc_file';
		$fields['ReportingOfficer'] = 'Reporting Officer';
		$fields['amount'] = 'Amount';
		$fields['suggested_cheque'] = 'Suggested Cheque';
		$fields['contractual_status'] = 'Contractual Status';
		$fields['advance'] = 'Advance';
		$fields['tds'] = 'TDS';
		$fields['vds'] = 'VDS';
		$this->validation->set_fields($fields);
	}
	
	function _set_rules(){
		$rules['bill_date'] = 'trim|required';
		$rules['loc'] = 'trim|required';
		$rules['bill_description'] = 'trim|required';
		$rules['Company'] = 'trim|required';
	
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
		$data['docs']=$this->distributionmodel->get_referance($id);
		$this->load->view('billView/viewDocs', $data);
	}
	
	
	function add(){
	
		$data=$this->getListData();
		// set common properties
		$data['title'] = 'Claim Distribution Bill';
		
		
		$this->_set_fields();
		$this->_set_rules();
		
		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			$data['action'] =  site_url('bill/distribution/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		}
		else if($this->input->post('ReportingOfficer')==''){
			$data['message'] = 			'<div class="cancel" align=left>please select reporitng to..</div>';
			$data['action'] =  site_url('bill/distribution/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		
		}
		else if($this->input->post('payment_type')=='Cheque' and $this->input->post('suggested_cheque')==''  ){
			$data['message'] = 			'<div class="cancel" align=left>please input Cheque Name..</div>';
			$data['action'] =  site_url('bill/distribution/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		
		}
		else{
			// save data	
			
			$this->distributionmodel->save($this->makeQueryinsert());
			
			$data['openmodel'] = 		'yes';
			$billId = 				$this->distributionmodel->get_last_entry_id($this->mkDate($this->input->post('bill_date')));			
		
			$data['action'] =  			site_url('bill/distribution/updatebill/'.$billId);
			$data['itemcount'] = 		$this->distributionmodel->tot_document_by_bill_id($billId);
			$data['action2'] =  site_url('bill/distribution/addRef/'.$billId);
			$data['action3'] =  site_url('bill/bill/submitSupervisor/'.$billId);
			$data['action4'] =  site_url('bill/distribution/addParticular/'.$billId);
			$data['action5'] =  site_url('bill/distribution/addDoc/'.$billId);
			
			$items=$this->distributionmodel->get_referance($billId)->result();
			
			$particularitems=$this->distributionmodel->get_particular($billId)->result();		
			$data['table2'] =			$this->particular_table($particularitems, $this->input->post('contractual_status'));
			$data['table3'] =			$this->documtent_table($this->distributionmodel->get_documents($billId)->result());
			$data['table'] =			$this->ref_table($items);
			$data['list_particular']=$this->distributionmodel->list_particular($billId);

			$data['message'] = 			'<div class="success" align=left>save successful..</div>';
		
			
		}
		
			$data['page']='/billView/distributionEdit'; //add page name as a parameter
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
							'contractual_status' => $this->input->post('contractual_status'),	
							'amount' => $this->input->post('amount'),	
							'payment_type' => $this->input->post('payment_type'),
							'suggested_cheque' => $this->input->post('suggested_cheque'),	
							'supervise_by' => $this->input->post('ReportingOfficer'),
							'loc' => $this->input->post('loc')
							
							);
	}
	
	
	function makeSuperviseQuery(){
		return $member = array(
							'bill_description' => $this->input->post('bill_description'),
							'bill_date' => $this->mkDate($this->input->post('bill_date')),
							'company_id' => $this->input->post('Company'),
							'amount' => $this->input->post('amount'),
							'contractual_status' => $this->input->post('contractual_status'),	
							'payment_type' => $this->input->post('payment_type'),
							'loc' => $this->input->post('loc')
				
							
							);
	}
	
	
	function makeAuditQuery(){
		return $member = array(
							'bill_description' => $this->input->post('bill_description'),
							'bill_date' => $this->mkDate($this->input->post('bill_date')),
							'company_id' => $this->input->post('Company'),
							'amount' => $this->input->post('amount'),
	
							'payment_type' => $this->input->post('payment_type'),
							'contractual_status' => $this->input->post('contractual_status'),	
							'audit_by' => $this->session->userdata('username'),
							'loc' => $this->input->post('loc')
							);
	}
	
	
	
	
	
	function makeQueryinsert(){
		//$id=$this->distributionmodel->id();
		return $member = array(
						
							'bill_description' => $this->input->post('bill_description'),
							'bill_date' => $this->mkDate($this->input->post('bill_date')),
							'company_id' => $this->input->post('Company'),							
							'amount' => $this->input->post('amount'),							
							'payment_type' => $this->input->post('payment_type'),
							'supervise_by' => $this->input->post('ReportingOfficer'),
							'bill_type' => 'distribution',
							'suggested_cheque' => $this->input->post('suggested_cheque'),
							'contractual_status' => $this->input->post('contractual_status'),	
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
		
		
		$data['company']=$this->distributionmodel->list_company();
		$data['pump']=$this->distributionmodel->list_pump();
		$data['reportingOfficerList']=$this->distributionmodel->list_head();
		return $data;
		
	}
	
	
	
	function ref_table($items)
	{
		$table='<table>';	
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){
		$table= $table.'<tr>';	
			$del='&nbsp;'.anchor('bill/distribution/deleteRef/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));


			
			$table= $table.'<td>'.$sl.'</td>'.'<td>'.$row->model_no.'</td>'.'<td>'.$row->referance_no.'</td>'.'<td>'.$del.'</td>';
		$sl=$sl+1;
		$table= $table.'</tr>';	
		}
		
		$table= $table.'</table>';	
		 return $table;	
	}
	

	
	function particular_table($items,$con)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
	
		$this->table->set_heading('No','Pump&nbsp;Model','Action');
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){

			$del='&nbsp;'.anchor('bill/distribution/deleteParticular/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));

			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			$ac=site_url('bill/distribution/updateParticular/'.$row->doc_id.'/'.$row->id);
			if($con==1)
			{
				$this->table->add_row(	
					
					$sl,
					'<form method="post" action="'.$ac.'"><table><tr><td><input type="text" name="req_no" class="text" readonly  value="'.$row->req_no.'"/></td><td><input type="text" name="pump_model" size="40" class="text" readonly value="'.$row->model_no.'"/></td>'.			
					'<td><input type="text" name="pump_qty" class="text" value="'.$row->quantiy.'"/></td>'.
					'<td><input type="submit" value="update"/></td></tr></table></form>',
					'&nbsp;'.$del
				);
			}else{
				$this->table->add_row(	
					
					$sl,
					'<form method="post" action="'.$ac.'"><table><tr><td><input type="text" name="req_no" class="text" readonly  value="'.$row->req_no.'"/></td><td><input type="text" name="pump_model" size="40" class="text" readonly value="'.$row->model_no.'"/></td>'.			
					'<td><input type="text" name="pump_qty" class="text" value="'.$row->quantiy.'"/></td>'.
					'<td><input type="text" name="pump_amount" class="text" value="'.$row->price.'"/><input type="submit" value="update"/></td></tr></table></form>',
					'&nbsp;'.$del
				);
			
			}
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	

	
	function updatebill($id,$filechk=1){
		
		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();
		
		$rows = $this->distributionmodel->get_by_id($id)->row();
		$this->validation->bill_date = 						date('d-m-Y',strtotime($rows->bill_date));
		$this->validation->Company = 						$rows->company_id;
		$this->validation->bill_description = 				$rows->bill_description;		
		$this->validation->amount = 						$rows->amount;
		$this->validation->payment_type = 					$rows->payment_type;
		$this->validation->ReportingOfficer = 				$rows->supervise_by;
		$this->validation->suggested_cheque = 				$rows->suggested_cheque;
		$this->validation->contractual_status = 			$rows->contractual_status;
		$this->validation->loc = 							$rows->loc;
		$this->validation->tot_auth_deduction = 			$rows->tot_auth_deduction;
		$this->validation->advance = 						$rows->advance;
		$this->validation->tds = 							$rows->tds;
		$this->validation->vds = 							$rows->vds;
		$this->validation->step_status = 					$rows->step_status;
		$this->validation->az_status = 					   	$rows->az_status;
		
		$data=$this->getListData();
		
		$data['openmodel'] = 		'yes';
		$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->distributionmodel->tot_document_by_bill_id($id);
		$items=$this->distributionmodel->get_referance($id)->result();
		$particularitems=$this->distributionmodel->get_particular($id)->result();		
		$data['table'] =			$this->ref_table($items);
		$data['table2'] =			$this->particular_table($particularitems,$rows->contractual_status);
		$data['table3'] =			$this->documtent_table($this->distributionmodel->get_documents($id)->result());
		// run validation
		if ($this->validation->run() == FALSE){
		
			if($filechk==2)
			$data['message'] = '<div class="cancel" align="left">File Not Found..</div>';
			else if($filechk==3)
			$data['message'] = '<div class="cancel" align="left">FTP Connection Fail..</div>';
			else if($filechk==4)
			$data['message'] = '<div class="cancel" align="left">FTP Login Fail..</div>';
			else if($filechk==6)
			$data['message'] = '<div class="cancel" align="left">Please Select Pump model and Input Referance No..</div>';
			else if($filechk==7)
			$data['message'] = '<div class="cancel" align="left">Please Complete All Pump model fields..</div>';
			else if($filechk==11)
			$data['message'] = '<div class="cancel" align="left">This Referance for the Selected Pump Model is already saved in the system..</div>';
			else if($filechk==21)
			$data['message'] = '<div class="cancel" align="left">Requisition not found or Already exist in another bill..</div>';
			else if($filechk==23)
			$data['message'] = '<div class="cancel" align="left">Referance No Should be Number..</div>';
			else $data['message'] = '';
			// load view 
			
			
			
		}
		else if($this->input->post('ReportingOfficer')=='' and $rows->step_status==1){
			$data['message'] = 			'<div class="cancel" align=left>please select reporitng to..</div>';
		
		}
		else if($this->input->post('payment_type')=='Cheque' and $this->input->post('suggested_cheque')==''  ){
			$data['message'] = 			'<div class="cancel" align=left>please input Cheque Name..</div>';
		
		}
		
		else{
			// save data
		
			if($rows->step_status==1)$this->distributionmodel->update($id,$this->makeQuery());
			else if($rows->step_status==2) $this->distributionmodel->update($id,$this->makeSuperviseQuery());
			else if($rows->step_status==3) $this->distributionmodel->update($id,$this->makeAuditQuery());
			$this->table->clear();
			$data['table2'] =			$this->particular_table($particularitems, $this->input->post('contractual_status'));
		
			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}
		
		$data['list_particular']=$this->distributionmodel->list_particular($id);
		$data['title'] = 'Claim Distribution Bill';
		$data['action'] = site_url('bill/distribution/updatebill/'.$id);
		$data['action2'] =  site_url('bill/distribution/addRef/'.$id);
		$data['action3'] =  site_url('bill/bill/submitSupervisor/'.$id);
		
		
		
		
		
		
		$data['action4'] =  site_url('bill/distribution/addParticular/'.$id);
		$data['action5'] =  site_url('bill/distribution/addDoc/'.$id);
		$data['page']='/billView/distributionEdit'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['entry']=1;
		$data['recommend']=0;
		
		
		
		if($rows->step_status==2)
		{
		$data['reportingOfficerList2']=$this->billsupervisemodel->list_head($this->validation->amount,$this->validation->Company);
		$data['usercode']=$this->billsupervisemodel->user_code();
		$data['action3'] =  site_url('bill/supervisebill/FinalSubmit/'.$id);
		$data['entry']=0;
		$data['recommend']=1;
		
		$dep_code=$this->billsupervisemodel->dep_code_by_id( $this->session->userdata('username'));
	
	
		if ( in_array($this->validation->Company, array(7) ))
		{	
		
			if($this->session->userdata('username')!=2405 and $this->session->userdata('username')!="003")
			{
				
				$data['usercode']=4;
							
			}
		
			
			if($dep_code==0)
			$data['reportingOfficerList2']=$this->billsupervisemodel->list_dep_head();
			else if($dep_code==1 and $this->session->userdata('username')!=2405 and $this->session->userdata('username')!="003")
			{
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_fin_head();
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
			$data['reportingOfficerList2']=$this->billsupervisemodel->list_dep_head();
			else if($dep_code==1 and $this->session->userdata('username')!=2346 and $this->session->userdata('username')!="003")
			{
				
					//echo "hello2";
					
				if($this->validation->amount>10000)			
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_fin_head_second();
				else  $data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();
				$data['usercode']=4;
							
			}
			
		}
		
		
		if ( in_array($this->validation->Company, array(1,3) ))
		{	
		
			if($this->session->userdata('username')!="003")
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
					
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();	
				
							
			}
			
			else if($rows->finance_head_by!=2346 and $this->session->userdata('username')==2346 )
			{
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();					
			}
			
			
			else if($rows->finance_head_by==2346 and $this->session->userdata('username')==2346 )
			{
				
				
				if($this->validation->amount>50000  and  $rows->supervise_by!=1970  )				
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_coo_head();
				else $data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_account();					
			}
			
			else if($rows->ceo_by!=1970 and $this->session->userdata('username')==1970 )
			{
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();					
			}
			
			else if($rows->ceo_by==1970 and $this->session->userdata('username')==1970 )
			{
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_account();
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
		
		}
		
		
		if($rows->step_status==3)
		{
		
		$data['usercode']='audit';
		if ( in_array($this->validation->Company, array(1,3) ))
		{	
			$data['usercode']=4;
			$data['reportingOfficerList2']=$this->billsupervisemodel->list_audit_options($this->validation->amount);
		
		}
		$data['action3'] =  site_url('bill/auditbill/FinalSubmit/'.$id);
		$data['entry']=0;
		$data['recommend']=1;
		
		}
		
		
		$this->load->view('index', $data);
		
	}
	
	function addRef($id)
	{
	
		if($this->input->post('pid')=="" or $this->input->post('referance_no')=="" )
		redirect('bill/distribution/updatebill/'.$id.'/6', 'location');
		

		$doubleFileChk=$this->distributionmodel->doubleRefChk($this->input->post('pid'),$this->input->post('referance_no'));
		if($doubleFileChk>0)
		redirect('bill/distribution/updatebill/'.$id.'/11', 'location');
		
		$this->distributionmodel->saveRef(			
		array(
		'detail_id' => $this->input->post('pid'),
		'referance_no' =>  $this->input->post('referance_no'))
		);
		
		redirect('bill/distribution/updatebill/'.$id.'/1', 'location');
		
	
		
	}
	
	function addDoc($id)
	{

		
		
		$ftp_server = "192.168.1.117";
		$conn_id = ftp_connect ($ftp_server)
			or redirect('bill/distribution/updatebill/'.$id.'/3', 'location');
		   
		$login_result = ftp_login($conn_id, "bill", "bill007");
		if ((!$conn_id) || (!$login_result))
			redirect('bill/distribution/updatebill/'.$id.'/4', 'location'); 
		
		
		
		
		$str = str_replace( '\\', '/', $this->input->post('doc_file') ); 
		$str = trim($str," ");	
		$doubleFileChk=$this->generalmodel->doubleFileChk($str);
		if($doubleFileChk>0)
		redirect('bill/distribution/updatebill/'.$id.'/11', 'location');
		
		$pizza  = $str;
		$pieces = explode("/", $pizza);
		$piecescount = count($pieces);
		
		if($piecescount!=2)
		{	ftp_close($conn_id); 
			redirect('bill/distribution/updatebill/'.$id.'/2', 'location');
		}
		
		
		$path = "/BILL/".$pieces[0]."/"; 
        ftp_pasv($conn_id, true);
		$contents_on_server = ftp_nlist($conn_id, $path);
		ftp_close($conn_id); 
		

		if(in_array($pieces[1], $contents_on_server) and $this->input->post('doc_file')!="" )
		{
		
		$this->generalmodel->saveDoc(			
		array(
		'doc_file' => $str ,
		'doc_id' => $id)
		);
			redirect('bill/distribution/updatebill/'.$id.'/1', 'location');
		}
		else redirect('bill/distribution/updatebill/'.$id.'/2', 'location');
		
		
		
		
		
	}
	
	function addParticular($id)
	{
		
		if(!is_numeric($this->input->post('req_no')))
		redirect('bill/distribution/updatebill/'.$id.'/23', 'location');
		
		$contractual_status=$this->distributionmodel->contractual_status($id);
		
		
		if($this->input->post('pump_model')!=""  and    $this->input->post('pump_qty')!="")
		{
		
		$this->distributionmodel->saveParticular(			
		array(
		'req_no' => $this->input->post('req_no'),
		'model_no' => $this->input->post('pump_model'),
		'quantiy' => $this->input->post('pump_qty'),
		'price' => $this->input->post('pump_amount'),
		'doc_id' => $id)
		);
			if($contractual_status==0)
			$this->distributionmodel->updateSumParticular($id);
			redirect('bill/distribution/updatebill/'.$id.'/1', 'location');
		}
		else redirect('bill/distribution/updatebill/'.$id.'/7', 'location');
		
	}
	
	
	function updateParticular($id1,$id2)
	{
		$contractual_status=$this->distributionmodel->contractual_status($id1);
		if($this->input->post('pump_model')!=""  and    $this->input->post('pump_qty')!="" )
		{
			$this->distributionmodel->updateParticular($id2,			
			array(
				'quantiy' => $this->input->post('pump_qty'),
				'price' => $this->input->post('pump_amount')
			));
			
			if($contractual_status==0)
			$this->distributionmodel->updateSumParticular($id1);
			redirect('bill/distribution/updatebill/'.$id1.'/1', 'location');
		}
		else redirect('bill/distribution/updatebill/'.$id1.'/7', 'location');
		
	}
	
	
		
	function deleteRef($id1,$id2){
		$this->distributionmodel->deleteRef($id1);
		

		redirect('bill/distribution/updatebill/'.$id2, 'location');
	}
	
	
	function deleteRequisition($id1,$id2){
		$this->distributionmodel->requisitionDelete($id1);
		

		redirect('bill/distribution/updatebill/'.$id2, 'location');
	}
	
		
	function deleteParticular($id1,$id2){
		$contractual_status=$this->distributionmodel->contractual_status($id2);
		$this->distributionmodel->deleteParticular($id1);
		if($contractual_status==0)
		$this->distributionmodel->updateSumParticular($id2);
		redirect('bill/distribution/updatebill/'.$id2, 'location');
	}
	
	
	
	
	function documtent_table($items)
	{
		$table='<table>';	
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){
		$del='&nbsp;'.anchor('bill/distribution/deleteDoc/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));
			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			//$view='<a href="ftp://billmaster:stargold@192.168.1.117/Common_share/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$view='<a href="'.base_url().'index.php/bill/distribution/url/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$doc_file=$row->doc_file;
		
		$table= $table.'<tr>';	


			
			$table= $table.'<td>'.$sl.'</td>'.'<td>'.$doc_file.'</td>'.'<td>'.$view.'</td>'.'<td>'.$del.'</td>';
		$sl=$sl+1;
		$table= $table.'</tr>';	
		}
		
		$table= $table.'</table>';	
		 return $table;	
	}
	

	
	function deleteDoc($id1,$id2){
		$this->billmodel->deleteDoc($id1);

		redirect('bill/distribution/updatebill/'.$id2, 'location');
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
	
}
