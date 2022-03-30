<?php
Class Costbill extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function __construct(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));		
		// load model
		$this->load->model('/billcostmodel','',TRUE);
		$this->load->model('/billauditmodel','',TRUE);
		$this->load->model('/billaccountmodel','',TRUE);
		$this->load->model('/generalmodel','',TRUE);
		$this->load->model('/billmodel','',TRUE);
		$this->load->model('/advancesupmodel','',TRUE);
		
		
		if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') != 8  AND $this->session->userdata('username') != "arif" AND $this->session->userdata('username') != "1570" AND $this->session->userdata('username') != "1775"   AND $this->session->userdata('username') != "2346"  AND $this->session->userdata('username') != "2405") {
	
		show_401();
	}
	
	}
	 
	function Index(){
		

		
	}
	
	function _set_fields(){			
		$fields['sap'] = 'Sap';

		
		$this->validation->set_fields($fields);
	}
	
	function _set_rules(){
		$rules['sap'] = 'trim|required';

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
		$data['docs']=$this->billcostmodel->get_documents($id);
		$this->load->view('billView/viewDocs', $data);
	}
	
	
	
	
	
	function makeQuery(){
		return $member = array(
							'sap_id' => $this->input->post('sap'),
							'payment_made_status' => 1,
							'step_status' => 6							
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
		
		
		$data['company']=$this->billcostmodel->list_company();
		$data['vendor']=$this->billcostmodel->list_vendor();
		$data['costcentre']=$this->billcostmodel->list_costcentre();
		$data['reportingOfficerList']=$this->billcostmodel->list_head();
		return $data;
		
	}
	
	
	
	function billList($type='Cheeque',$message='',$printid=0){
		// offset
	
		
		//Search 
		$data['action']=site_url('bill/costbill/searchbill');
		$data['title']="bill List";
		// set user message
		$data['message']=$message;
		
		
		
		if($message=='clear')
		{
		
		$print=anchor('reports/reports/bill_print/'.$printid,'print',array('class'=>'view','target'=>'about_blank'));
		$data['message']="<table><tr><td><div class='success' align=left>Clear&nbsp;Successful..!!</div></td><td>".$print."</td></tr></table>";
		
		
		}		
		else if($message=='Cheque'){ 
		$data['message']="<div class='success' align=left>Cheque&nbsp;is&nbspReady..!!</div>";
		}
		
		
		
		// load data
		$bill = $this->billcostmodel->get_paged_list($type)->result();
		
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/billListCost'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	
	
	function bill_table($bill)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id','billing&nbsp;date','location','description','Suggested Cheque','Net&nbsp;Pay','Submitted&nbsp;by','status','cost&nbsp;allocation','action');

		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){			
	
		if($row->step_status==5)
		$status="Still Not Clear";
	
		
		if($row->supervise_cancel==1)
		$status="Cancel by Supervisor";		
		else if($row->authority_cancel==1)
		$status="Cancel by Authority";		
		else if($row->audit_cancel==1)
		$status="Cancel by Audit";
		

		$viewbilldoc="";
		$docsl=1;
		$pchk="start";
		$pcount=1;
		/*
		if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
		//$billdoc=$this->billcostmodel->get_documents($row->id);	
		$billdoc=$this->billmodel->get_special_documents($row->id);	
		foreach($billdoc->result() as $rows)
					{ 

					
					if(strlen($rows->doc_file)<5)
							$viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));	
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/costbill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
						$docsl=$docsl+1;
					}
		
		}else if($row->bill_type=="general"){
		$billdoc=$this->generalmodel->get_special_documents($row->id);
		foreach($billdoc->result() as $rows)
					{ 

						if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/costbill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
					
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->detail_id;
					}
		
		}
			
			*/
			
			
			
			if($row->step_status==5 and $row->supervise_cancel==0 and $row->authority_cancel==0 and $row->audit_cancel==0)
			{
			$sap=anchor('bill/costbill/sap/'.$row->id.'/'.$row->payment_type,'Sap&nbsp;Entry',array('class'=>'add'));
			//$iouclear=anchor('bill/costbill/clear_by_iou/'.$row->id.'/'.$row->payment_type.'/'.$row->created_by,'Clear&nbsp;Against&nbsp;IOU',array('class'=>'add'));
			$iouclear="";
			}
			else {$sap ="";
			$iouclear="";
			}

	
			
			if($row->bill_type=="vendor"){
			$viewbill=anchor('reports/reports/view_bill/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));
			}
			else if($row->bill_type=="distribution"){
			$viewbill=anchor('reports/reports/view_distribution/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));
			$viewhistory='';
			}
			else{
				$viewbill=anchor('reports/reports/view_general/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));		
			}
			
			$costable="<div id='".$row->id."'  class='divcost' ><font color='#FF000'><b>Double click here to view Cost Allocation..<br/>Please Wait 3 seconds</b></font></div>";
			$Cheque_ready="";
			
			if($row->payment_type=="Cheque"  and $row->check_ready==0)
			$Cheque_ready=anchor('bill/costbill/Cheque_ready/'.$row->id,'Check&nbsp;Ready',array('class'=>'add','onclick'=>"return confirm('Are you sure that Cheque is ready?')"));
	
	
	
			$viewaction="";
			//$viewaction=anchor('reports/reports/view_action_bill/'.$row->id,'view&nbsp;step',array('class'=>'view','target'=>'about_blank'));	
			$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
			
			//$netpay=($row->amount-$row->advance)."&nbsp;BDT";
			//if($row->bill_type=='general')
		
			$netpay=($row->amount-$row->advance-$row->tds-$row->vds-$row->tot_auth_deduction-$row->general_deduction)."&nbsp;BDT";
			$this->table->add_row(
				$row->id,
				$row->bill_date,
				$loc,
				$row->bill_description,
				$row->suggested_cheque."<br/> Cheque&nbsp;Date:&nbsp;".$row->cheque_date,
				$netpay,
				$row->empName,
				$status,
				$costable,
				$viewbill."</br>".$viewaction."</br>".$sap."</br>".$iouclear."</br>".$Cheque_ready
				//$viewbilldoc
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	function Cheque_ready($id){	
		$this->billcostmodel->Cheque_ready($id);
		redirect('bill/costbill/billList/Cheque/Cheque');
		
	}
	
	function documten_table($items)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('No','Document','Action');
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($items as $row){

			$del='&nbsp;'.anchor('bill/costbill/deleteDoc/'.$row->id.'/'.$row->doc_id,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure want to delete?')"));
			$view='&nbsp;'.anchor('bill/costbill/viewDoc/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			
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

		//Search 
		$data['action']=site_url('bill/costbill/searchbill');;
		$data['title']="bill List";
		// set user message
		$data['message']='';
		
		// load data
		$bill = $this->billcostmodel->get_search_by_id($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = '';
		
		$data['table'] =$this->bill_table($bill);
		$data['page']='/billView/billList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	

	
	function submitAccounts($id){	
		$this->billcostmodel->submitAccounts($id);
		redirect('bill/costbill/billList/0/submitAccounts');
		
	}
	
	function cancel($id){	
		$this->billcostmodel->cancel($id);
		redirect('bill/costbill/billList');
		
	}
	
	
	
	function deleteDoc($id1,$id2){
		$this->billcostmodel->deleteDoc($id1);

		redirect('bill/costbill/updatebill/'.$id2, 'location');
	}
	
	

	
	
	function bill_Accept_account($id){
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['area']=$this->billcostmodel->list_area();
		$data['internal_order']=$this->billcostmodel->list_internal_order();
		// set common properties
		$data['title'] = 'Bill Accept Form';
		$data['action'] =  site_url('bill/costbill/bill_Accept_account/'.$id.'/');
		
		$this->_set_fields();
		$this->_set_rules();
		
		//$this->validation->EmpId =	$this->session->userdata('username');
		
		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			
		}
	
		else{
			// save data
			$data=$this->makeQuery();
					
			//print_r($request);
			$this->billcostmodel->update($id,$data);

			// set user message
	
			$data['message'] = '<div class="success" align=left>Request Send Successful..</div>';
	
			//$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);
			redirect('bill/costbill/billList/0/accept');
			
		}
			
			$data['page']='/billView/billAccept'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	function iou_set_entry($id,$type,$emp){
	
		$namount=$this->input->post('namount');
		$balance=$this->input->post('balance');
		$i=0;
		foreach ($this->input->post('iou') as $iou)
		{
			if($namount[$i]>0 and $balance[$i]>=$namount[$i]){
			$this->billcostmodel->iou_set_entry($iou,$namount[$i]);
				$i++;
			}	
			
		}
		
			redirect('bill/costbill/clear_by_iou/'.$id.'/'.$type.'/'.$emp.'/1');
		
	}
	function clear_by_iou($id,$type,$emp,$msg=""){
		
			$data['message'] = '';
			if($msg==1)
			$data['message'] = '<div class="success" align="left">Settlement Success!..</div>';
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'Clear Against IOU';
			$data['sap_link'] =  site_url('bill/costbill/sap/'.$id.'/'.$type.'/');
			$data['go_back'] =  site_url('bill/costbill/billList/'.$type.'/');
			
			$link=site_url('bill/costbill/iou_set_entry/'.$id.'/'.$type.'/'.$emp.'/');
			
			$ioulist = $this->billcostmodel->iou_by_emp($emp);
			$table="<form method='post' action='".$link."'><table><tr><td><b>IOU&nbsp;ID</b></td><td><b>Description</b></td><td><b>Amount</b></td><td><b>Settlement</b></td><td><b>Balance</b></td><td><b>Entry</b></td></tr>";
			$chk=0;
			foreach($ioulist as $iou)
			{
				$chk=$chk+1;
				$balance=$iou->amount-$iou->setamount;
				$table.="<tr>";
				$table.="<td>".$iou->id."<input type='hidden' value='".$iou->id."' name='iou[]'/></td>";
				$table.="<td>".$iou->purpose."</td>";
				$table.="<td>".$iou->amount."</td>";
				$table.="<td>".$iou->setamount."</td>";
				$table.="<td>".$balance."<input type='hidden' value='".$balance."' name='balance[]'/></td>";
				$table.="<td><input type='text' value='0' name='namount[]'/></td>";
				$table.="<tr>";
			
			}
			if($chk>0)
			$table.="</table><input type='submit' value='save'/></form>";
			else $table.="</table></form>";
			$data['table']=$table;
			$data['page']='/billView/billCLRbyIOU'; //add page name as a parameter
			$this->load->view('index', $data);
	}

	function sap($id,$type){
		
		// set common properties
		
		
		$this->_set_fields();
		$this->_set_rules();

		
		// run validation
		if ($this->validation->run() == FALSE){
			
			$data['message'] = '';
			
		}
	
		else if($this->billcostmodel->chk_duplicate_bill_payment($id)>0)
		{
			$data['message'] = '<div class="cancel" align=left>You have  already submitted sap id for this bill!</div>';
		
		}
		else{
			// save data
			$data=$this->makeQuery();
					
			//print_r($request);
			$this->billcostmodel->update($id,$data);
			
			$this->billcostmodel->updatePaymentDate($id);

			// set user message
	
			$data['message'] = '<div class="success" align=left>Entry Successful..</div>';
			
			$this->billcostmodel->action_doc(
					array(							
							'doc_id' =>$id,'action' =>'sap entry','user_id' => $this->session->userdata('username')					
						 )
					);
			
			
			redirect('bill/costbill/billList/'.$type.'/clear/'.$id);
			//$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);
			
		}
				$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'SAP Entry Form';
			$data['action'] =  site_url('bill/costbill/sap/'.$id.'/'.$type.'/');
			$data['page']='/billView/billSAP'; //add page name as a parameter
			$this->load->view('index', $data);
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
	
	
	function pending_date($message=''){
		// offset
	
		
		//Search 
		$data['action']=site_url('bill/costbill/searchbill');
		$data['title']="bill List";
		// set user message
		$data['message']=$message;
		
		
		
		if($message=='pass')
		{
			
			$data['message']="<div class='success' align=left>Assign&nbsp;Successful..!!</div>";		
		}		
		else if($message=='resubmit')
		$data['message']="<div class='success' align=left>Resubmit Successful..!!</div>";
		else if($message=='park')
		$data['message']="<div class='success' align=left>Parked Successful..!!</div>";
		
		
		
		// load data
		$bill = $this->billcostmodel->pending_date_list()->result();
		
		
		$data['table'] =$this->pending_table($bill);
		$data['page']='/billView/UnAssignedChequeBillList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function pending_table($bill)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id','billing&nbsp;date','location','description','Suggested Cheque','Net&nbsp;Pay','Submitted&nbsp;by','status','action','documents');

		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){			
	
		if($row->step_status==5)
		$status="Still Not Clear";
	
		
		if($row->supervise_cancel==1)
		$status="Cancel by Supervisor";		
		else if($row->authority_cancel==1)
		$status="Cancel by Authority";		
		else if($row->audit_cancel==1)
		$status="Cancel by Audit";
		

		$viewbilldoc="";
		$docsl=1;
		$pchk="start";
		$pcount=1;
		/*
		if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
		//$billdoc=$this->billcostmodel->get_documents($row->id);	
		$billdoc=$this->billmodel->get_special_documents($row->id);	
		foreach($billdoc->result() as $rows)
					{ 

					
					if(strlen($rows->doc_file)<5)
							$viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));	
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/costbill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
						$docsl=$docsl+1;
					}
		
		}else if($row->bill_type=="general"){
		$billdoc=$this->generalmodel->get_special_documents($row->id);
		foreach($billdoc->result() as $rows)
					{ 

						if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/costbill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
					
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->detail_id;
					}
		
		}
			
		*/	
			$doctable="<div id='".$row->id."'  class='divcost' ><font color='#FF000'><b>Double click here to view documents.</b></font></div>";
			
			$pass=anchor('bill/costbill/pass/'.$row->id,'Assign&nbsp;Cheque&nbsp;Date',array('class'=>'add'));
			$resubmit=anchor('bill/costbill/resubmitAudit/'.$row->id,'Resubmit&nbsp;to&nbsp;Audit',array('class'=>'add'));

	
			
			if($row->bill_type=="vendor"){
			$viewbill=anchor('reports/reports/view_bill/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));
			}
			else if($row->bill_type=="distribution"){
			$viewbill=anchor('reports/reports/view_distribution/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));
			$viewhistory='';
			}
			else{
				$viewbill=anchor('reports/reports/view_general/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));		
			}
			
		
			
			$Cheque_ready="";
			
			if($row->payment_type=="Cheque"  and $row->check_ready==0)
			$Cheque_ready=anchor('bill/costbill/Cheque_ready/'.$row->id,'Check&nbsp;Ready',array('class'=>'add','onclick'=>"return confirm('Are you sure that Cheque is ready?')"));
	
	
	
	
			$viewaction=anchor('reports/reports/view_action_bill/'.$row->id,'view&nbsp;step',array('class'=>'view','target'=>'about_blank'));	
			$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
			$park=anchor('bill/costbill/park/'.$row->id,'Park',array('class'=>'add'));
			
			$netpay=($row->amount-$row->advance-$row->tds-$row->vds-$row->tot_auth_deduction-$row->general_deduction)."&nbsp;BDT";
			$this->table->add_row(
				$row->id,
				$row->bill_date,
				$loc,
				$row->bill_description,
				$row->suggested_cheque."<br/> Cheque&nbsp;Date:&nbsp;".$row->cheque_date,
				$netpay,
				$row->empName,
				$status,
		
				$viewbill."</br>".$viewaction."</br>".$pass."</br>".$resubmit,
				$doctable
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	
	
	
	function get_doc_details()
	{
		$bill_id=$this->input->post('data');
		
		$row=$this->billcostmodel->get_by_id($bill_id)->row();

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
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/auditbill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
						$docsl=$docsl+1;
					}
		
		}else if($row->bill_type=="general"){
		
		$billdoc=$this->generalmodel->get_special_documents($row->id);	
		foreach($billdoc->result() as $rows)
					{ 
					$doccount=$doccount+1;
					if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/auditbill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
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
	
	
	
	
		function resubmitToAudit($id){	
	
		$this->billaccountmodel->resubmitAudit($id,
			array(
							'step_status' => 3,
							'account_head_pass' => 0,			
							'account_comment' =>$this->input->post('comment')						
							)
			);	
			$this->billaccountmodel->action_doc(
					array(							
							'doc_id' =>$id,'action' =>'Resubmit','user_id' => $this->session->userdata('username'),'comment' =>$this->input->post('comment')						
						 )
					);
			
			redirect('bill/costbill/pending_date/resubmit');
		
		
	}
	
		function resubmitAudit($id){
		
			$data['message'] = '';
			$data['comment'] = '';
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'Resubmit Reason';
			$data['action'] =  site_url('bill/costbill/resubmitToAudit/'.$id.'/');
			$data['page']='/billView/comment'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	
	function pass($id){	
	
		$data['message'] = '';
			$data['comment'] = '';
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'Comment';
			$data['action'] =  site_url('bill/costbill/passadd/'.$id.'/');
			$data['page']='/billView/assignDate'; //add page name as a parameter
			$data['nowdate']=date('d-m-Y'); //add page name as a parameter
			$this->load->view('index', $data);
			
			
	
	}
	

	
	function passadd($id){
	
		$this->billaccountmodel->update($id,		
			array(							
					'cheque_date' =>$this->mkDate($this->input->post('cheque_date'))				
				)
		);
			$this->billaccountmodel->action_doc(
					array(							
							'doc_id' =>$id,'action' =>'Cheque Date Assigned','user_id' => $this->session->userdata('username'),'comment' =>$this->input->post('comment')						
						 )
					);
					
				
		redirect('bill/costbill/pending_date/pass');
			
	}
	
	
	
	function iouList($message='',$offset = 0,$printid=0){
		// offset


		$data['action']=site_url('bill/iousup/searchIou');
		$data['title']="Pending IOU List";
		// set user message
		$data['message']=$message;
		
		if($message=='complete')
		$data['message']="<div class='success' align=left>Payment Made..!!</div>".anchor('reports/reports/iou_print/'.$printid,'print',array('class'=>'view','target'=>'about_blank'));
	


		
		// load data
		$iou = $this->billcostmodel->iou_list($this->limit, $offset)->result();
		


		
		$data['table'] =$this->iou_table($iou);
		$data['page']='/billView/adjustList'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	
	
	function iou_table($requisition)
	{
	
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('ID','request&nbsp;by','request&nbsp;date','Requested&nbsp;to','department&nbsp;approved&nbsp;date','AGM','AGM Approved Date','amount','purpose','status','action');

		$status="";
		

		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($requisition as $row){			

		if($row->step_status==1)	$status="Still Not Final Submitted";
		else if($row->step_status==2)	$status="Submitted to Department Head";
		else if($row->step_status==3)	$status="Submitted to AGM";
		else if($row->step_status==4)	$status="Submitted to Cost Center";
		else if($row->step_status==5)	$status="Payment made";



		if($row->step_status=4)
		$complete='&nbsp;'.anchor('bill/costbill/completeIou/'.$row->id,'complete',array('class'=>'accept','onclick'=>"return confirm('Are you sure want to complete?')"));
		else $complete="";		
		$report=anchor('reports/reports/view_iou/'.$row->id,'view',array('class'=>'view','target'=>'about_blank'));	
			$this->table->add_row(
			
				$row->id,
				$row->req_by." ".$row->createdName,
				$row->req_date,
				$row->deptName,
				$row->dep_accept_date,
				$row->agmName,				
			
				$row->dgm_accept_date,
				$row->amount, 
				$row->purpose,
		
				$status,
				$complete."<br/><br/>".$report

			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	function completeIou($id)
	{
			
			
			$this->billcostmodel->completeIou($id);
			redirect('bill/costbill/iouList/complete/0/'.$id, 'location');
			
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	//FOR AGM ARIF
	
	
	function pending_date_agm_park($message=''){
		// offset
	
		
		//Search 
		$data['action']=site_url('bill/costbill/searchbill');
		$data['title']="bill List";
		// set user message
		$data['message']=$message;
		
		
		
		if($message=='pass')
		{
			
			$data['message']="<div class='success' align=left>Assign&nbsp;Successful..!!</div>";		
		}		
		else if($message=='resubmit')
		$data['message']="<div class='success' align=left>Resubmit Successful..!!</div>";
		
		
		
		// load data
		$bill = $this->billcostmodel->get_agm_park_list()->result();
		
		
		$data['table'] =$this->pending_table_agm_park($bill);
		$data['page']='/billView/billListAll'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	function pending_table_agm_park($bill)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id','billing&nbsp;date','location','description','Suggested Cheque','Net&nbsp;Pay','Submitted&nbsp;by','status','action','documents');

		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){			
	
		if($row->step_status==5)
		$status="Still Not Clear";
	
		
		if($row->supervise_cancel==1)
		$status="Cancel by Supervisor";		
		else if($row->authority_cancel==1)
		$status="Cancel by Authority";		
		else if($row->audit_cancel==1)
		$status="Cancel by Audit";
		

		$viewbilldoc="";
		$docsl=1;
		$pchk="start";
		$pcount=1;
		
		if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
		//$billdoc=$this->billcostmodel->get_documents($row->id);	
		$billdoc=$this->billmodel->get_special_documents($row->id);	
		foreach($billdoc->result() as $rows)
					{ 

					
					if(strlen($rows->doc_file)<5)
							$viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));	
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/costbill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
						$docsl=$docsl+1;
					}
		
		}else if($row->bill_type=="general"){
		$billdoc=$this->generalmodel->get_special_documents($row->id);
		foreach($billdoc->result() as $rows)
					{ 

						if(strlen($rows->doc_file)<5)
					$report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
					else $report=base_url().'index.php/bill/costbill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
					
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->detail_id;
					}
		
		}
			
			
			
			
			$pass=anchor('bill/costbill/pass_park/'.$row->id,'Assign&nbsp;Cheque&nbsp;Date',array('class'=>'add'));
			$resubmit=anchor('bill/costbill/resubmitAudit/'.$row->id,'Resubmit&nbsp;to&nbsp;Audit',array('class'=>'add'));

	
			
			if($row->bill_type=="vendor"){
			$viewbill=anchor('reports/reports/view_bill/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));
			}
			else if($row->bill_type=="distribution"){
			$viewbill=anchor('reports/reports/view_distribution/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));
			$viewhistory='';
			}
			else{
				$viewbill=anchor('reports/reports/view_general/'.$row->id,'view&nbsp;bill',array('class'=>'view','target'=>'about_blank'));		
			}
			
		
			
			$Cheque_ready="";
			
			if($row->payment_type=="Cheque"  and $row->check_ready==0)
			$Cheque_ready=anchor('bill/costbill/Cheque_ready/'.$row->id,'Check&nbsp;Ready',array('class'=>'add','onclick'=>"return confirm('Are you sure that Cheque is ready?')"));
	
	
	
	
			$viewaction=anchor('reports/reports/view_action_bill/'.$row->id,'view&nbsp;step',array('class'=>'view','target'=>'about_blank'));	
			$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
		
			$status="Park&nbsp;by:&nbsp;".$row->park_by.'<br>Park&nbsp;Reason:&nbsp;'.$row->park_comment;
			
			$this->table->add_row(
				$row->id,
				$row->bill_date,
				$loc,
				$row->bill_description,
				$row->suggested_cheque."<br/> Cheque&nbsp;Date:&nbsp;".$row->cheque_date,
				($row->amount-$row->advance)."&nbsp;BDT",
				$row->empName,
				$status,
		
				$viewbill."</br>".$viewaction."</br>".$pass,
				$viewbilldoc
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	function pass_park($id){	
	
		$data['message'] = '';
			$data['comment'] = '';
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'Comment';
			$data['action'] =  site_url('bill/costbill/park_passadd/'.$id.'/');
			$data['page']='/billView/assignDate'; //add page name as a parameter
			$data['nowdate']=date('d-m-Y'); //add page name as a parameter
			$this->load->view('index', $data);
			
			
	
	}
	

	
	function park_passadd($id){
	
		$this->billaccountmodel->update($id,		
			array(							
					'cheque_date' =>$this->mkDate($this->input->post('cheque_date'))				
				)
		);
			$this->billaccountmodel->action_doc(
					array(							
							'doc_id' =>$id,'action' =>'Cheque Date Assigned','user_id' => $this->session->userdata('username'),'comment' =>$this->input->post('comment')						
						 )
					);
					
				
		redirect('bill/costbill/pending_date_agm_park/pass');
			
	}
	
		function park($id){
		
			$data['message'] = '';
			$data['comment'] = '';
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'Parking';
			$data['action'] =  site_url('bill/costbill/parkadd/'.$id.'/');
			$data['page']='/billView/comment'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	
	
	function parkadd($id){
		
			$this->billauditmodel->updatepark($id,
			array(
							'agm_park' => 1,'park_by' =>  $this->session->userdata('username'),
							
							'park_comment' =>$this->input->post('comment')
							
							)
			);	

			redirect('bill/costbill/pending_date/park');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function advanceList($payment= "cash",$message='',$printid=''){
	
		
		//Search 
		$data['title']="Advance List";
		// set user message
		$data['message']=$message;
		
		if($message=='pass')
		$data['message']="<div class='success' align=left>Payment Successful..!!</div>".anchor('reports/reports/advance_print/'.$printid,'print',array('class'=>'view','target'=>'about_blank'));;
	
		
		// load data
		$bill = $this->advancesupmodel->get_paged_list_cost($payment)->result();
		

		
		$data['table'] =$this->advance_table($bill,$payment);
		$data['page']='/billView/billListAll'; //add page name as a parameter
		$data['userlevel']=$this->session->userdata('authlevel');
		$this->load->view('index',$data);
	}
	
	
	
	
	
	
	function advance_table($bill,$payment)
	{
	// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id','advance&nbsp;date','location','company','description','Net&nbsp;Pay','Created&nbsp;by','Supervised&nbsp;by','Cost&nbspAllocation','status','action','Documents');

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
					else $report=base_url().'index.php/bill/costbill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;
					
					if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else if ($pchk==$rows->advance_details_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
				$docsl=$docsl+1;
				
				$pchk=$rows->advance_details_id;
					}
		
		
			
			
			if($row->check_ready==1)
			$status="&nbsp;Check&nbsp;is&nbsp;Ready&nbsp;";
			
		
			
			if($row->step_status==4)
			{
			$pass=anchor('bill/costbill/advancePass/'.$row->id.'/'.$payment,'payment&nbsp;made',array('class'=>'add'));	
			}
			else {
			$pass="";
			}
			
			if($row->cancel_staus==1)
			$status="&nbsp;Canceled&nbsp;";
			
			$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
			
			
		 if($row->advance_type=='Cheque' and $row->check_ready==0 )
			$Cheque_ready=anchor('bill/costbill/Cheque_ready_Advance/'.$row->id.'/'.$payment,'Check&nbsp;Ready',array('class'=>'add','onclick'=>"return confirm('Are you sure that Cheque is ready?')"));
		else 	$Cheque_ready="";


			$cost_center=$this->billaccountmodel->cost_center_advance($row->id);

			$costable='	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
			<tr>
				<td align="Center" width="1%"><b>SL</b></td>
				<td align="Center"><b>Vendor&nbsp;</b></td>
				<td align="Center"><b>Account&nbsp;Head&nbsp;</b></td>	
				<td align="Center"><b>Amount&nbsp;BDT</b></td>
				<td align="Center"><b>Remark</b></td>			
			</tr> ';

			$sl=0;
			foreach ($cost_center->result() as $mydata): 
			$sl=$sl+1;
			
			$costrow=
				'<tr><td align="center"><b>'.$sl.'</b></td><td align="center"><b>'.$mydata->vendor.'</b></td><td align="center"><b>'.$mydata->account_head.'</b></td>'.
			
				'<td align="center"><b>'.$mydata->amount.'</b></td><td align="center"><b>'.$mydata->remarks.'</b></td></tr>';	
				
			$costable=$costable.$costrow;	
			endforeach; 
			
			
			$costable=$costable.'</table>';		
			
			
			$this->table->add_row(
				$row->id,
				$row->advance_date,
				$loc,
				$row->vCompany,
				$row->advance_description,			
				$row->amount,
				$row->createdName,
				$row->superviseName,
				$costable,
				$status,
				$viewbill."<br/>".$pass."<br/>".$Cheque_ready,
				$viewbilldoc
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	function advancePass($id,$payment,$msg=1){
		
			$data['message'] = '';
			
			if($msg==2)
			$data['message'] = '<div class="cancel" align=left>You have  already submitted sap id for this advance!</div>';
			$data['comment'] = '';
			$data['userlevel']=$this->session->userdata('authlevel');
			$data['title'] = 'Advance Clear';
			$data['action'] =  site_url('bill/costbill/advancePassAdd/'.$id.'/'.$payment);
			$data['page']='/billView/advanceSAP'; //add page name as a parameter
			$this->load->view('index', $data);
	}
	
	
	function advancePassAdd($id,$payment){
		
			if($this->billcostmodel->chk_duplicate_advance_payment($id)>0)
			redirect('bill/costbill/advancePass/'.$id.'/'.$payment.'/2');
			
			
			$this->advancesupmodel->update($id,
			array(
							'step_status' => 5,'check_ready' => 0,'sap_id' => $this->input->post('sap')
							
							)
			);	
			
			$this->advancesupmodel->action_doc(
					array(							
							'advance_id' =>$id,'str_action' =>'Payment Made','user_id' => $this->session->userdata('username')						
						 )
					);
				

			redirect('bill/costbill/advanceList/'.$payment.'/pass/'.$id);
	}
	
	
	function Cheque_ready_Advance($id,$payment){
		
			$this->advancesupmodel->update($id,
			array(
							'check_ready' => 1
							
							)
			);	
			
			$this->advancesupmodel->action_doc(
					array(							
							'advance_id' =>$id,'str_action' =>'Cheque is Ready','user_id' => $this->session->userdata('username')						
						 )
					);
				

			redirect('bill/costbill/advanceList/'.$payment);
	}
	
	
	function get_cost_details()
	{
		$bill_id=$this->input->post('data');
		
		$cost_center=$this->billcostmodel->cost_center($bill_id);

			$costable='	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
			<tr>
				<td align="Center" width="1%"><b>SL</b></td>
				<td align="Center"><b>Company&nbsp;</b></td>
				<td align="Center"><b>Vendor&nbsp;</b></td>
				<td align="Center"><b>Business&nbsp;Area&nbsp;</b></td>
				<td align="Center"><b>Account&nbsp;Head&nbsp;</b></td>
				<td align="Center"><b>Profit&nbsp;Center&nbsp;</b></td>
				<td align="Center"><b>Cost&nbsp;Center&nbsp;</b></td>
				<td align="Center"><b>Internal&nbsp;Order&nbsp;</b></td>
				<td align="Center"><b>SAP&nbsp;ID&nbsp;</b></td>			
				<td align="Center"><b>Amount&nbsp;BDT</b></td>
				<td align="Center"><b>Remark</b></td>			
			</tr> ';
			
			
			$sl=0;
			foreach ($cost_center->result() as $mydata): 
			$sl=$sl+1;
			
			$costrow=
				'<tr><td align="center"><b>'.$sl.'</b></td><td align="center"><b>'.$mydata->vCompany.'</b></td><td align="center"><b>'.$mydata->vendor.'</b></td><td align="center"><b>'.$mydata->area.'</b></td><td align="center"><b>'.$mydata->account_head.'</b></td><td align="center"><b>'.$mydata->profit_center.'</b></td>'.
				'<td align="center"><b>'.$mydata->cost_id.'&nbsp;##&nbsp;'.$mydata->cost_text.'</b></td><td align="center"><b>'.$mydata->Order.'&nbsp;'.$mydata->Description.'</b></td>'.
				'<td align="center"><b>'.$mydata->sap_id.'</b></td><td align="center"><b>'.$mydata->divide_amount.'</b></td><td align="center"><b>'.$mydata->remark.'</b></td></tr>';	
				
			$costable=$costable.$costrow;	
			endforeach; 
			
			
			$costable=$costable.'</table>';	
			echo $costable;
	}

}
