<?php
Class External extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function __construct(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));		
		// load model
		$this->load->model('/externalmodel','',TRUE);
		$this->load->model('/generalmodel','',TRUE);
		$this->load->model('/billmodel','',TRUE);
		$this->load->model('/billaccountmodel','',TRUE);
		$this->load->model('/advancesupmodel','',TRUE);
		$this->load->model('/reqsupmodel','',TRUE);
		if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') != 666) {
	
		show_401();
	}
	
	}
	 
	function Index(){
		
		//$data=$this->getListData();

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
	
	
	
	function viewDoc($doc){
		$data['doc']=$doc;
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('billView/viewDoc', $data);
	}
	
	function viewDocs($id){
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['docs']=$this->externalmodel->get_documents($id);
		$this->load->view('billView/viewDocs', $data);
	}
	

	
	function get_doc_details()
	{
		$bill_id=$this->input->post('data');
		
		$row=$this->externalmodel->get_by_id($bill_id)->row();

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
					
							if(strlen($rows->doc_file)<5)
							$viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));	
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/external/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
						$docsl=$docsl+1;
					}
		
		}else if($row->bill_type=="general"){
		
		$billdoc=$this->generalmodel->get_special_documents($row->id);	
		foreach($billdoc->result() as $rows)
					{ 
					$doccount=$doccount+1;
					if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/external/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->detail_id;
					}
		
		} 
		if($doccount==0) echo "<font color='#FF000'><b>No Document Found..</font>";
		echo $viewbilldoc;
	}
	
	
	
	function billList($offset = 0,$message=''){
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		$data['employee']=$this->externalmodel->list_employeeid();
		$data['company']=$this->externalmodel->list_company();
		//Search 
		$data['action']=site_url('bill/external/billList');

		$data['title']="Bill List";
		// set user message
		$data['message']=$message;
		
		if($message=='submitSupervisor')
		$data['message']="<div class='success' align=left>Submitted to Supervisor Successful..!!</div>";

		
		// load data
		$list = $this->externalmodel->get_paged_list($this->limit, $offset);
		$bill = $list->result();
		
		
		
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/external/billList/');
		if($this->input->post('con')!=2 and $this->input->post('con')!=3)
		$number_of_rows=$this->externalmodel->count_all();
		else $number_of_rows=$list->num_rows();
 		$config['total_rows'] = $number_of_rows;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/externalList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	
	
	function bill_table($bill)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id','company','billing&nbsp;date','location','description','sap&nbsp;id','Cheque&nbsp;Name','Net&nbsp;Pay','Submitted&nbsp;by','Supervised&nbsp;by','Payment&nbsp;Date','Payment&nbsp;Type','Bill&nbsp;Type','status','action','Documents');
		$tmpl = array ( 'table_open'  => '<table   id="myTable" class="tablesorter">',
						'heading_row_start'   => '<thead>',
						'heading_row_end'     => '</thead>'
				);
		$this->table->set_template($tmpl); 
		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){			
	
		if($row->step_status==1)
		$status="Still Not Final Submitted";
		else if($row->step_status==2)
		{
		$hold_by=$this->billmodel->get_holdby($row->hold_by);
		$status="Processing</br>hold&nbsp;by:&nbsp;".$hold_by;	
		}
		else if($row->step_status==3)
		$status="Submitted to Audit";
		else if($row->step_status==4 and $row->account_head_pass==0 and $row->account_pass==0)
		$status="Submitted to Accounts";
		else if($row->step_status==4 and $row->account_pass==1)
		$status="Submitted to Account Head";
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
		
		
		
		
		if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="vendor")
			$view=anchor('bill/bill/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));
		else if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="general")
			$view=anchor('bill/general/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));	
		else $view ="";
		
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
			
			$viewaction=anchor('reports/reports/view_action_bill/'.$row->id,'view&nbsp;step',array('class'=>'view','target'=>'about_blank'));	
			
			$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
			
			
			$empName=$this->billmodel->get_holdby($row->created_by);
			$superviseName=$this->billmodel->get_holdby($row->supervise_by);
			
			//$vCompany=$this->externalmodel->get_company_by_id($row->company_id)->row()->vCompany;
			
			
			//$netpay=($row->amount-$row->advance)."&nbsp;BDT";
			//if($row->bill_type=='general')
			$netpay=($row->amount-$row->advance-$row->tds-$row->vds-$row->tot_auth_deduction-$row->general_deduction);
			$netpay=number_format($netpay, 0, '.', ',');
			
			$this->table->add_row(
				$row->id,
				$row->vCompany,
				$row->bill_date,
				$loc,
				$row->bill_description,
				$row->sap_id,
				$row->suggested_cheque,
				$netpay,
				$empName,
				$superviseName,
				$row->payment_made_date,
				$row->payment_type,
				$row->bill_type,
				$status,
				$viewbill."</br>".$viewhistory."</br>".$viewaction,
				$doctable
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	function documten_table($items)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
	
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){

			$del='&nbsp;'.anchor('bill/bill/deleteDoc/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));
			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			$view='<a href="ftp://bill:bill007@192.168.1.117/BILL/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$this->table->add_row(				
				$sl,
				$row->doc_file,
				$view.'&nbsp;'.$del
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	
	
	function searchbill(){
	$data['employee']=$this->externalmodel->list_employeeid();
	$data['company']=$this->externalmodel->list_company();
		//Search 
		$data['action']=site_url('bill/external/searchbill');
		$data['action2']=site_url('bill/external/searchbillEmp');
		$data['action3']=site_url('bill/external/searchSpecialbill');
		$data['action4']=site_url('bill/external/searchAmount');
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->externalmodel->get_search_by_id($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')),$this->input->post('status'),$this->input->post('loc'),$this->input->post('company'));

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';

		$data['totalrecord'] = $bill->num_rows();
		$data['table'] =$this->bill_table($bill->result());	
		

		$data['page']='/billView/adminList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function searchbillEmp(){
	$data['employee']=$this->externalmodel->list_employeeid();
	$data['company']=$this->externalmodel->list_company();
		//Search 
		$data['action']=site_url('bill/external/searchbill');
		$data['action2']=site_url('bill/external/searchbillEmp');
		$data['action3']=site_url('bill/external/searchSpecialbill');
		$data['action4']=site_url('bill/external/searchAmount');
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->externalmodel->get_search_by_id_by_emp($this->input->post('EmpId'));

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		
		$data['totalrecord'] = $bill->num_rows();
		$data['table'] =$this->bill_table($bill->result());
		$data['page']='/billView/adminList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	function searchSpecialbill(){
	$data['employee']=$this->externalmodel->list_employeeid();
	$data['company']=$this->externalmodel->list_company();
		//Search 
		$data['action']=site_url('bill/external/searchbill');
		$data['action2']=site_url('bill/external/searchbillEmp');
		$data['action3']=site_url('bill/external/searchSpecialbill');
		$data['action4']=site_url('bill/external/searchAmount');
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data

		$bill = $this->externalmodel->get_special_search_by_id($this->input->post('stype'),$this->input->post('svalue'));
		
		
		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';

		$data['totalrecord'] = $bill->num_rows();
		$data['table'] =$this->bill_table($bill->result());
		
		
		$data['page']='/billView/adminList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function searchAmount(){
	$data['employee']=$this->externalmodel->list_employeeid();
	$data['company']=$this->externalmodel->list_company();
		//Search 
		$data['action']=site_url('bill/external/searchbill');
		$data['action2']=site_url('bill/external/searchbillEmp');
		$data['action3']=site_url('bill/external/searchSpecialbill');
		$data['action4']=site_url('bill/external/searchAmount');
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
	
		$bill = $this->externalmodel->get_search_by_amount($this->input->post('amtfrom'),$this->input->post('amtto'));
		
		
		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';

		$data['totalrecord'] = $bill->num_rows();
		$data['table'] =$this->bill_table($bill->result());
		
		
		$data['page']='/billView/adminList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	
	
	
	function mkDate($userDate){
		if($userDate!=''){
		$date_arr = explode('-', $userDate);
		$data = date("Y-m-d", mktime(0,0,0,$date_arr[1], $date_arr[0], $date_arr[2] ) );
		return $data;}
		else return '';
	}
	

	function requisitionList($offset = 0,$message=''){

		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		
		$data['title']="Material Requisition List";
		// set user message
		$data['message']=$message;
		
		if($message=='submit')
		$data['message']="<div class='success' align=left>Approved Successful..!!</div>";
		$data['action']=site_url('bill/external/searchrequisition');
		
		// load data
		$requisition = $this->externalmodel->all_material_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/external/requisitionList/');
		$number_of_rows=$this->externalmodel->count_material();
 		$config['total_rows'] = $number_of_rows;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;
		
		$data['table'] =$this->requisition_table($requisition);
		$data['page']='/billView/reqAllList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function searchrequisition(){
	$data['employee']=$this->externalmodel->list_employeeid();
		//Search 
		$data['action']=site_url('bill/external/searchrequisition');

		$data['title']="Material Requisition List";
		// set user message
		$data['message']='';
		
		// load data
		$requisition = $this->externalmodel->get_requisition_search($this->input->post('stype'),$this->input->post('svalue'))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = '';
		
		
		$data['table'] =$this->requisition_table($requisition);
		$data['page']='/billView/reqAllList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	function requisition_table($requisition)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('ID','requested&nbsp;by','request&nbsp;date','Requested&nbsp;to','approved&nbsp;date','assigned&nbsp;person','Procurement&nbsp;Approved','Complete&nbsp;Status','Purchase&nbsp;Status','Cancel&nbsp;Status','action');

		$status="";
		$approve="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($requisition as $row){			

	
		$report=anchor('reports/reports/view_material_list/'.$row->id,'view',array('class'=>'view','target'=>'about_blank'));	
		//if($this->session->userdata('username')==1091 and $row->procurement_pass==0)
		//$approve=anchor('bill/reqsup/approve/'.$row->id,'Approve',array('class'=>'add','onclick'=>"return confirm('Are you sure want to Approve?')"));
		//else $approve='';
		if($row->cancel_status==0)
		$cancel_status="No";
		else $cancel_status="Canceled";
		
		if($row->bill_status==0)$complete_status="No";else $complete_status="Yes";
		if($row->procurement_pass==0)$procurement_pass="No";else $procurement_pass="Yes";
			
		$itemdoc=$this->reqsupmodel->getDocId($row->id)->result();	
		$doctable="<table>";
		$docsl=0;
		foreach($itemdoc as $rowdoc)
		{
		$docsl=$docsl+1;
		$viewbillReport="";
		if($rowdoc->bill_type=="vendor"){	
			$viewbillReport=anchor('reports/reports/view_bill/'.$rowdoc->doc_id,'bill-'.$docsl,array('class'=>'view','target'=>'about_blank'));
		}
		else
		$viewbillReport=anchor('reports/reports/view_general/'.$rowdoc->doc_id,'bill-'.$docsl,array('class'=>'view','target'=>'about_blank'));
		
			
			$doctable.="<tr>";
			$doctable.="<td>";
			$doctable.=$viewbillReport;
			$doctable.="</td>";
			$doctable.="</tr>";
		}	
		$doctable.="</table>";	


		if($row->purchase_staus==0 and $row->bill_status==0)
		$purchase_staus="Not Yet";
		else $purchase_staus="Purchased&nbsp;done<br/>Purchased&nbsp;date:".$row->purchase_date."<br/>Comments:".$row->purchase_comment;
			
			$this->table->add_row(
			
				$row->id,
				$row->request_by.'# '.$row->createdName,
				$row->request_date,
				$row->approveName,
				$row->approved_date,
				$row->assignName,
				$procurement_pass,
				$complete_status,
				$purchase_staus,
				$cancel_status,
				$report.'<br/>'.$doctable.'<br/>'
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	function url($url1,$url2)
	{	
		//redirect('ftp://billmaster:stargold@192.168.1.117/Common_share/'.$url, 'refresh');
		$location='location: ftp://bill:bill007@192.168.1.117/BILL/'.$url1.'/'.$url2;
	
		header($location);
	}
	
	
	
	
	function billPaymentList($offset = 0,$message=''){
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		//Search 
		$data['action']=site_url('bill/external/searchpaymentbill');

		$data['title']="Bill List";
		// set user message
		$data['message']=$message;
		
		if($message=='submitSupervisor')
		$data['message']="<div class='success' align=left>Submitted to Supervisor Successful..!!</div>";

		
		// load data
		$bill = $this->externalmodel->get_paged_payment_list($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/external/billPaymentList/');
		$number_of_rows=$this->externalmodel->count_payment_all();
 		$config['total_rows'] = $number_of_rows;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;
		
		$data['table'] =$this->payment_bill_table($bill);
		$data['page']='/billView/billPaymentList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function searchpaymentbill(){

		//Search 
		$data['action']=site_url('bill/external/searchpaymentbill');

		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->externalmodel->count_payment_search($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')),$this->input->post('loc'),$this->input->post('payment_type'))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->externalmodel->count_search_payment_all($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')),$this->input->post('loc'),$this->input->post('payment_type'));

		
		$data['table'] =$this->payment_bill_table($bill);
		$data['page']='/billView/billPaymentList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function payment_bill_table($bill)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id','billing&nbsp;date','payment&nbsp;date','location','description','sap&nbsp;id','Net&nbsp;Pay','Submitted&nbsp;by','status','action','Documents');

		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){			
	
		if($row->step_status==1)
		$status="Still Not Final Submitted";
		else if($row->step_status==2)
		{
		$hold_by=$this->billmodel->get_holdby($row->hold_by);
		$status="Processing</br>hold&nbsp;by:&nbsp;".$hold_by;	
		}
		else if($row->step_status==3)
		$status="Submitted to Audit";
		else if($row->step_status==4 and $row->account_head_pass==0 and $row->account_pass==0)
		$status="Submitted to Accounts";
		else if($row->step_status==4 and $row->account_pass==1)
		$status="Submitted to Account Head";
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
		
		
		
		
		if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="vendor")
			$view=anchor('bill/bill/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));
		else if($row->step_status==1 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0 and $row->bill_type=="general")
			$view=anchor('bill/general/updatebill/'.$row->id,'update&nbsp;bill',array('class'=>'update'));	
		else $view ="";
		
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
		$docsl=1;
		$pchk="start";
		$pcount=1;
		
		if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
		//$billdoc=$this->externalmodel->get_documents($row->id);
		$billdoc=$this->billmodel->get_special_documents($row->id);			
		foreach($billdoc->result() as $rows)
					{ 

					
					if(strlen($rows->doc_file)<5)
							$viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));	
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/external/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
						$docsl=$docsl+1;
					}
		
		}else if($row->bill_type=="general"){
		$billdoc=$this->generalmodel->get_special_documents($row->id);
		foreach($billdoc->result() as $rows)
					{ 

						if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/external/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
					
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->detail_id;
					}
		
		}
			
			
			//if($row->check_ready==1)
			//$status="&nbsp;Check&nbsp;is&nbsp;Ready&nbsp;";
			
			
			
			
			$viewaction=anchor('reports/reports/view_action_bill/'.$row->id,'view&nbsp;step',array('class'=>'view','target'=>'about_blank'));


			$assignSAPid=anchor('bill/external/assignSAPid/'.$row->id,'Assign&nbsp;SAP&nbsp;ID',array('class'=>'add'));		
			
			$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
			$this->table->add_row(
				$row->id,
				$row->bill_date,
				$row->paydate,
				$loc,
				$row->bill_description,
				$row->sap_id,
				($row->amount-$row->advance)."&nbsp;BDT",
				$row->empName,
				$status,
				$viewbill."</br>".$viewhistory."</br>".$viewaction."</br>".$assignSAPid,
				$viewbilldoc
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	function assignSAPid($id,$msg=1){
		
		
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['message'] = '';
			if($msg==2)
			$data['message'] = '<div class="success" align=left>Update Successful..</div>';
			// set common properties
			$data['title'] = 'Bill Accept Form';
			$data['action'] =  site_url('bill/pobill/bill_Accept_account/'.$id.'/');
			$data['action2'] =  site_url('bill/accountbill/FinalSubmit/'.$id);
			$items=$this->billaccountmodel->cost_center($id)->result();		
			$data['table'] =			$this->cost_table($items);
			
			$data['page']='/billView/assignSAP'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	
	
	
	function cost_table($items)
	{

		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		//$this->table->set_heading('No','Company&nbsp;','Business&nbsp;Area&nbsp;','Profit&nbsp;Center&nbsp;','SAP&nbsp;ID&nbsp;','Vendor&nbsp;','Amount&nbsp;BDT','Action');
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){

		$ac=site_url('bill/external/assignSAPidENTRY/'.$row->doc_id.'/'.$row->id);

			$this->table->add_row(

				$sl,
				'<form method="post" action="'.$ac.'"><table><tr><td>'.$row->vCompany.'</td><td>'.$row->area.'</td><td>'.$row->profit_center.'</td><td>'.$row->vendor.'</td><td>'.$row->divide_amount.'</td>'.			
				'<td><input type="text" name="sap_id" class="text" value="'.$row->sap_id.'"/><input type="submit" value="update"/></td></tr></table></form>'
			
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	function assignSAPidENTRY($id1,$id2)
	{
	
		
			$this->billaccountmodel->assignSAPidENTRY($id2,			
			array(
	
			'sap_id' => $this->input->post('sap_id')
		
			));
	
			redirect('bill/external/assignSAPid/'.$id1.'/2', 'location');
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	function advancePaymentList($offset = 0,$message=''){
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);
		
		//Search 
		$data['action']=site_url('bill/external/searchAdvancePaymentList');
		$data['title']="Advance List";
		// set user message
		$data['message']=$message;
		
		if($message=='pass')
		$data['message']="<div class='success' align=left>Submitted Successful..!!</div>";
	
		
		// load data
		$bill = $this->externalmodel->get_paged_list_advance_payment($this->limit, $offset)->result();
		
		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/external/advancePaymentList/');
		$number_of_rows=$this->externalmodel->count_all_advance_payment();
 		$config['total_rows'] = $number_of_rows;
 		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;
		
		$data['table'] =$this->advancePaymentList_table($bill);
		$data['page']='/billView/advanceList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function searchAdvancePaymentList(){

		//Search 
		$data['action']=site_url('bill/external/searchAdvancePaymentList');;
		$data['title']="Advance List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->externalmodel->get_search_by_id_advance_payment($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->externalmodel->count_search_advance_payment($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')));
		
		$data['table'] =$this->advancePaymentList_table($bill);
		$data['page']='/billView/advanceList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	function advancePaymentList_table($bill)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id','advance&nbsp;date','payment&nbsp;date','location','description','Net&nbsp;Pay','Created&nbsp;by','Supervised&nbsp;by','status','action','Documents');

		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){			
	

		 if($row->step_status==3)
		$status="Submitted to Finance";

		else if($row->step_status==4)
		{
		$status="Accounts Clear";
		}
		else if($row->step_status==5)
		$status="Payment Made";
		
	
		
		
		
		

		

			$viewbill=anchor('reports/reports/view_advance/'.$row->id,'view&nbsp;advance',array('class'=>'view','target'=>'about_blank'));	
			//$viewhistory=anchor('reports/reports/view_details_history/'.$row->id,'view&nbsp;history',array('class'=>'view','target'=>'about_blank'));			
		
		
		
		
		
		
		$viewbilldoc="";
		$docsl=1;
		$pchk="start";
		$pcount=1;
		

		$billdoc=$this->advancesupmodel->get_special_documents($row->id);	
		foreach($billdoc->result() as $rows)
					{ 
					
					if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->advance_id.'/'.$rows->advance_details_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/accountbill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->advance_details_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->advance_details_id;
					}
		
		
			
			
			if($row->check_ready==1)
			$status="&nbsp;Check&nbsp;is&nbsp;Ready&nbsp;";
			
		
			
			
			
			if($row->cancel_staus==1)
			$status="&nbsp;Canceled&nbsp;";
			
			$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
			$this->table->add_row(
				$row->id,
				$row->advance_date,
				$row->payment_date,		
				$loc,
				$row->advance_description,			
				$row->amount,
				$row->createdName,
				$row->superviseName,
				$status,
				$viewbill."<br/>",
				$viewbilldoc
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	
	
	
	
	

}
