<?php
Class Reports extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	
	function __construct(){
		parent::__construct();		
		// load model
		$this->load->model('/reportsModel/reportsmodel','',TRUE);

	}
	//unused this function
	function set_empty_param(){
		//Set parmameter Empty
		$data['sap_id']='n';
		$data['complete_status']='n';
		$data['todateShow']='n';
		$data['fromdateShow']='n';
		$data['yearID']='n';
		$data['companylist']='n';
		$data['lotShow']='n';
		$data['companyShow']='n';
		$data['locShow']='n';
		
		
		return $data;
	}
	
	function mkDate($userDate){
		$date_arr = explode('-', $userDate);
		$data = date("Y-m-d", mktime(0,0,0,$date_arr[1], $date_arr[0], $date_arr[2] ) );
		return $data;
	}
	
	function investigation($id){
	
		$data['results']=$this->reportsmodel->investigation($id);
	
		
		$data['options']=1;
		$this->load->view('reports/report/rpt_investigation', $data);
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
		
	}
	
	function count_date_wise_bill_doc(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_count_date_wise_bill_doc');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';
		$data['companyShow']='y';
		$data['locShow']='y';
		
		$data['company_list']=$this->reportsmodel->list_company();

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}	
	
	function view_count_date_wise_bill_doc(){	
	

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));  
		$com=$this->input->post('company');  
		$loc=$this->input->post('loc'); 
		
		$data['company']=$this->reportsmodel->get_company_by_id($com)->row()->vCompany;

		if($loc==1)$location="Chittagong Head Office";
		else if($loc==2) $location="Dhaka Office";
		else if($loc==3) $location="Mohakhali Office";
		$data['location']=$location;
		
		$data['from']=$from;
		$data['to']=$to;		
		$data['results']=$this->reportsmodel->count_date_wise_bill_doc($from,$to,$com,$loc);
	
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_count_date_wise_bill_doc', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	
	function payment_date_wise_bill(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_payment_date_wise_bill');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	function view_payment_date_wise_bill(){	
	

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));  
		$data['from']=$from;
		$data['to']=$to;		
		$data['results']=$this->reportsmodel->payment_date_wise_bill($from,$to);
	
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_payment_date_wise_bill', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	function pending_payment_date_unit_bill(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_pending_payment_date_unit_bill');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	function view_pending_payment_date_unit_bill(){	
	

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));  
		$data['from']=$from;
		$data['to']=$to;		
		$data['results']=$this->reportsmodel->pending_payment_date_unit_bill($from,$to,'Cash');
		$data['results2']=$this->reportsmodel->pending_payment_date_unit_bill($from,$to,'Cheque');
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_pending_payment_date_unit_bill', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	
	
	function under_my_supervision_bill(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_under_my_supervision_bill');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	function my_requisition_by_date_wise(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_my_requisition_by_date_wise');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	
	function view_my_requisition_by_date_wise(){	
	

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));  
		$data['from']=$from;
		$data['to']=$to;		
		$data['results']=$this->reportsmodel->my_requisition_by_date_wise($from,$to);
	
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_my_requisition_by_date_wise', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	
	
	
	
	function view_under_my_supervision_bill(){	
	

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));  
		$data['from']=$from;
		$data['to']=$to;		
		$data['results']=$this->reportsmodel->under_my_supervision_bill_payment($from,$to);
	
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_under_my_supervision_bill', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	
	
	function bill_Report_For_Req(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_bill_Report_For_Req');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';
		$data['complete_status']='y';	
		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	function view_bill_Report_For_Req(){	
	

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));  
		$data['from']=$from;
		$data['to']=$to;
		$complete_status=$this->input->post('complete_status');  
		
		$results=$this->reportsmodel->bill_Report_For_Req($from,$to,$complete_status);
		
		$data['results']=$results;
		$table='';
		$completed='Completed';
		foreach ($results->result() as $mydata): 
		if($mydata->bill_status==0)	$completed='Not Completed'; else $completed='Completed';
		
		$bill=$this->reportsmodel->get_bill_id_by_req_id($mydata->Requisiotion_ID);
		$bill_id='';
		foreach ($bill as $bill_row): 	
			$bill_id.=$bill_row->doc_id.'&nbsp<a href="'.base_url().'index.php/reports/reports/view_bill_Report_by_ID/'.$bill_row->doc_id.'" target="about_blank" class="view">view</a>'.'<br/>';
		endforeach;
		
		$table.='<tr>';
		$table=$table.'<td align="center"><b>'.$mydata->Requisiotion_ID.'</b></td>';
		$table=$table.'<td align="center"><b>'.$mydata->Request_Date.'</b></td>';
		$table=$table.'<td align="center"><b>'.$mydata->Assigned_Person.'</b></td>';
		$table=$table.'<td align="center"><b>'.$mydata->Requested_By.'</b></td>';
		$table=$table.'<td align="center"><b>'.$mydata->Requested_Department.'</b></td>';
		$table=$table.'<td align="center"><b>'.$mydata->Supervised_By.'</b></td>';
		$table=$table.'<td align="center"><b>'.$mydata->Procurement_Aprroved_date.'</b></td>';
		$table=$table.'<td align="Center"><b>'.$completed.'</b></td>'	;
		$table=$table.'<td align="Center"><b>'.$bill_id.'</b></td>'	;
		$table.='</tr>';

		endforeach;
		
		
		$data['table']=$table;
		
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_bill_Report_For_Req', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	
	
	function view_bill_Report_by_ID($id){
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['options']=2;
		
		$data['results2']=$this->reportsmodel->cost_center($id);
		
		$billtype=$this->reportsmodel->bill_type($id);
		
		if($billtype=='vendor')
		{
		$data['results']=$this->reportsmodel->view_bill($id);
		$data['documents']=$this->documents_vendor_scan($id);
		$this->load->view('reports/report/rpt_view_invoice', $data);}
		else if($billtype=='distribution')
		{
		redirect('reports/reports/view_distribution/'.$id, 'location');
		}
		else	
		{
			$data['results']=$this->reportsmodel->view_general($id);
			$results3=$this->reportsmodel->view_detials($id);
			$table3="";
			$sl=0;
			foreach ($results3->result() as $mydata3): 
			$sl=$sl+1;
			
			
			$str_scan=$this->documents_scan($mydata3->id);
			
			$table3=$table3.
				'<tr>
				<td align="center"><b>Particular:&nbsp;'.$sl.'</b></td>
				<td align="center"><b>'.$mydata3->particular.'</b></td>
				<td align="center"><b>'.$mydata3->total.'</b></td>
				<td align="center"><b>'.$str_scan.'</b></td>
				</tr>';	
			 endforeach; 

			 $data['table3']=$table3;
			$this->load->view('reports/report/rpt_view_general', $data);
		}
	}
	
	
	function bill_Report(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_bill_Report');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['sap_id']='y';	

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	function scan_Report(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_scan_Report');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['sap_id']='y';	

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	function view_scan_Report(){

		$res=$this->reportsmodel->view_docs_by_scan($this->input->post('id'));
			echo "<table border='1'><tr><td><b>Bill ID</b></td><td><b>Scan ID</b></td></tr>";
		
		foreach ($res->result() as $mydata): 

			$link = site_url('reports/reports/view_bill_Report_by_ID/'.$mydata->doc_id);	

			echo "<tr><td><a href='".$link."' target='about_blank'>".$mydata->doc_id."</a></td>";
			echo "<td>".$mydata->doc_file."</td></tr>";
		endforeach; 
		
		echo "</table>";
		
	}
	
	
	function bill_Report_For_Sap(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_bill_Report_For_Sap');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['sap_id']='y';	

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	
	function bill_Report_by_sap_year(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_bill_Report_by_sap_year');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['sap_id']='y';	
		$data['yearID']='y';
		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	function view_bill_Report_by_sap_year(){

		$res=$this->reportsmodel->view_bill_Report_by_sap_year($this->input->post('id'),$this->input->post('yearID'));
			echo "<table border='1'><tr><td><b>Bill Date</b></td><td><b>Bill ID</b></td></tr>";
		
		foreach ($res->result() as $mydata): 

			$link = site_url('reports/reports/view_bill_Report_by_ID/'.$mydata->id);	

			
			echo "<tr><td>".$mydata->bill_date."</td>";
			echo "<td><a href='".$link."' target='about_blank'>".$mydata->id."</a></td></tr>";
		endforeach; 
		
		echo "</table>";
		
	}
	
	
	
	function Advance_Report(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_advance_by_id');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['sap_id']='y';	

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	function view_advance_by_id(){
		redirect('reports/reports/view_advance/'.$this->input->post('id'), 'location');
	}
	
	function view_advance_by_sap_id(){
		$results=$this->reportsmodel->get_advance_id_by_sap_id($this->input->post('id'));
		if($results->result()){
		
		foreach ($results->result() as $mydata): 
		$advance_id=$mydata->id;
		endforeach;
		
		
		redirect('reports/reports/view_advance/'.$advance_id, 'location');}
		else echo "No Record Found!";
	}
	
	
	function Advance_Report_For_Sap(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_advance_by_sap_id');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['sap_id']='y';	

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	function view_bill_Report_For_Sap(){
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['options']=2;
		$billid=$this->reportsmodel->get_bill_id($this->input->post('id'));
		
		
		$data['results2']=$this->reportsmodel->cost_center($billid);
		
		$billtype=$this->reportsmodel->bill_type($billid);
		
		if($billtype=='vendor')
		{
		$data['results']=$this->reportsmodel->view_bill($billid);
		$data['documents']=$this->documents_vendor_scan($billid);
		$this->load->view('reports/report/rpt_view_invoice', $data);}
		else if($billtype=='distribution')
		{
		redirect('reports/reports/view_distribution/'.$this->input->post('id'), 'location');
		}
		else	
		{
			$data['results']=$this->reportsmodel->view_general($billid);
			$results3=$this->reportsmodel->view_detials($billid);
			$table3="";
			$sl=0;
			foreach ($results3->result() as $mydata3): 
			$sl=$sl+1;
			
			
			$str_scan=$this->documents_scan($mydata3->id);
			
			$table3=$table3.
				'<tr>
				<td align="center"><b>Particular:&nbsp;'.$sl.'</b></td>
				<td align="center"><b>'.$mydata3->particular.'</b></td>
				<td align="center"><b>'.$mydata3->total.'</b></td>
				<td align="center"><b>'.$str_scan.'</b></td>
				</tr>';	
			 endforeach; 

			 $data['table3']=$table3;
			$this->load->view('reports/report/rpt_view_general', $data);
		}
		
	}
	

	function view_bill_Report(){
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['options']=2;
		
		$data['results2']=$this->reportsmodel->cost_center($this->input->post('id'));
		
		$billtype=$this->reportsmodel->bill_type($this->input->post('id'));
		
		if($billtype=='vendor')
		{
		$data['results']=$this->reportsmodel->view_bill($this->input->post('id'));
		$data['documents']=$this->documents_vendor_scan($this->input->post('id'));
		$this->load->view('reports/report/rpt_view_invoice', $data);}
		else if($billtype=='distribution')
		{
		redirect('reports/reports/view_distribution/'.$this->input->post('id'), 'location');
		}
		else	
		{
			$data['results']=$this->reportsmodel->view_general($this->input->post('id'));
			$results3=$this->reportsmodel->view_detials($this->input->post('id'));
			$table3="";
			$sl=0;
			foreach ($results3->result() as $mydata3): 
			$sl=$sl+1;
			
			
			$str_scan=$this->documents_scan($mydata3->id);
			
			$table3=$table3.
				'<tr>
				<td align="center"><b>Particular:&nbsp;'.$sl.'</b></td>
				<td align="center"><b>'.$mydata3->particular.'</b></td>
				<td align="center"><b>'.$mydata3->particular_date.'</b></td>
				<td align="center"><b>'.$mydata3->total.'</b></td>
				<td align="center"><b>'.$str_scan.'</b></td>
				</tr>';	
			 endforeach; 

			 $data['table3']=$table3;
			$this->load->view('reports/report/rpt_view_general', $data);
		}
	}
	
	

	
	function view_bill($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->view_bill($id);
		$data['results2']=$this->reportsmodel->cost_center($id);
		$data['documents']=$this->documents_vendor_scan($id);
		
		$this->load->view('reports/report/rpt_view_invoice', $data);
	
	}
	
	
	function view_action_bill($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->action_bill($id);
		$this->load->view('reports/report/rpt_view_bill_action', $data);
	
	}
	
	
	function bill_print($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->bill_print($id);
		$this->load->view('reports/report/rpt_bill_print', $data);
	
	}
	
		function iou_print($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->iou_print($id);
		$this->load->view('reports/report/rpt_iou_print', $data);
	
	}
	
	
	
	
	
	function view_general($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->view_general($id);
		$data['results2']=$this->reportsmodel->cost_center($id);
		$results3=$this->reportsmodel->view_detials($id);
		
		$table3="";
		$sl=0;
			foreach ($results3->result() as $mydata3): 
			$sl=$sl+1;
			
			
			$str_scan=$this->documents_scan($mydata3->id);
			
			$table3=$table3.
				'<tr>
				<td align="center"><b>Particular:&nbsp;'.$sl.'</b></td>
				<td align="center"><b>'.$mydata3->particular.'</b></td>
				<td align="center"><b>'.$mydata3->particular_date.'</b></td>
				<td align="center"><b>'.$mydata3->total.'</b></td>
				<td align="center"><b>'.$str_scan.'</b></td>
				</tr>';	
			 endforeach; 

			 $data['table3']=$table3;

		$this->load->view('reports/report/rpt_view_general', $data);
	
	}
	
	
	function view_distribution($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->view_general($id);
		$data['results2']=$this->reportsmodel->cost_center($id);
		$data['documents']=$this->documents_vendor_scan($id);
		$results3=$this->reportsmodel->view_pumps($id);
		$contractual_status=$this->reportsmodel->contractual_status($id);
		$table3="";
		$sl=0;
			foreach ($results3->result() as $mydata3): 
			$sl=$sl+1;
			
			
			$str_ref=$this->ref_scan($mydata3->id);
			if($contractual_status==0)
			{
			$table3=$table3.
				'<tr>
				<td align="center"><b>'.$sl.'</b></td>
				<td align="center"><b>'.$mydata3->model_no.'</b></td>
				<td align="center"><b>'.$mydata3->quantiy.'</b></td>
				<td align="center"><b>'.$mydata3->price.'</b></td>
				<td align="center"><b>'.$mydata3->req_no.'</b></td>
				</tr>';	
			}else{
			$table3=$table3.
				'<tr>
				<td align="center"><b>'.$sl.'</b></td>
				<td align="center"><b>'.$mydata3->model_no.'</b></td>
				<td align="center"><b>'.$mydata3->quantiy.'</b></td>

				<td align="center"><b>'.$mydata3->req_no.'</b></td>
				</tr>';	


			}	
			 endforeach; 

			 $data['table3']=$table3;

		$this->load->view('reports/report/rpt_view_distribution', $data);
	
	}
	
	function ref_scan($id)
	{
			$documents=$this->reportsmodel->get_referance($id);
			$str="";

			foreach ($documents->result() as $mydata): 
			$str=$str.'&nbsp;'.$mydata->referance_no.'</br>';

			endforeach;

		
			 
			 return $str;
	}
	
	function documents_scan($id)
	{
			$documents=$this->reportsmodel->get_documents($id);
			$str="";
			$sl=1;
			foreach ($documents->result() as $mydata): 
			$str=$str.'Document-'.$sl.'&nbsp<a href="'.base_url().'index.php/reports/reports/url/'.$mydata->doc_file.'" target="about_blank" class="view">view</a>&nbsp;<b>Category:</b>&nbsp;'.$mydata->doc_category.'<br/>';
			$sl=$sl+1;
			endforeach;

			$requisition=$this->reportsmodel->get_requisition($id);
			foreach ($requisition->result() as $mydata): 
			$str=$str.'Document-'.$sl.'&nbsp<a href="'.base_url().'index.php/reports/reports/order_material_list/'.$mydata->doc_id.'/'.$mydata->detail_id.'/'.$mydata->req_id.'/'.$mydata->bill_by.'" target="about_blank" class="view">view</a>'.'<br/>';
			$sl=$sl+1;
			endforeach;

		
			 
			 return $str;
	}
	
	function documents_vendor_scan($id)
	{
			$documents=$this->reportsmodel->get_vendor_documents($id);
			$str="";
			$sl=1;
			foreach ($documents->result() as $mydata): 
			$str=$str.'Document-'.$sl.'&nbsp<a href="'.base_url().'index.php/reports/reports/url/'.$mydata->doc_file.'" target="about_blank" class="view">view</a>&nbsp;<b>Category:</b>&nbsp;'.$mydata->doc_category.'<br/>';
			$sl=$sl+1;
			endforeach;

			$requisition=$this->reportsmodel->get_vendor_requisition($id);
			foreach ($requisition->result() as $mydata): 
			$str=$str.'Document-'.$sl.'&nbsp<a href="'.base_url().'index.php/reports/reports/vendor_material_list/'.$mydata->doc_id.'/'.$mydata->req_id.'/'.$mydata->bill_by.'" target="about_blank" class="view">view</a>'.'<br/>';
			$sl=$sl+1;
			endforeach;

		
			 
			 return $str;
	}
	
	function view_history($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->view_history($id);
		$this->load->view('reports/report/rpt_view_history', $data);
	
	}
	
	
	function view_history_iou($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->view_history_iou($id);
		$this->load->view('reports/report/rpt_iou_history', $data);
	
	}
	
	
	function view_details_history($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->view_details_history($id);
		$this->load->view('reports/report/rpt_view_history_details', $data);
	
	}
	
	

	function view_material_list($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->get_material_list($id);
		$data['results2']=$this->reportsmodel->get_mis_requisition_action_list($id);
		$this->load->view('reports/report/rpt_view_material', $data);
	
	}
	
	function view_iou($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->get_iou_list($id);
		$data['results2']=$this->reportsmodel->get_iou_details_list($id);
		$this->load->view('reports/report/rpt_view_iou', $data);
	
	}
	
	
	
	
	
	function order_material_list($doc_id,$detail_id,$master_id,$bill_by){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->get_order_list($doc_id,$detail_id,$master_id,$bill_by);
		$data['results2']=$this->reportsmodel->get_mis_requisition_action_list($master_id);
		$this->load->view('reports/report/rpt_order_material', $data);
	
	}
	
	
	function vendor_material_list($doc_id,$master_id,$bill_by){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->vendor_order_list($doc_id,$master_id,$bill_by);
		$data['results2']=$this->reportsmodel->get_mis_requisition_action_list($master_id);
		$this->load->view('reports/report/rpt_order_material', $data);
	
	}
	
	
	
	function pending_cheque(){	
	
		$data['options']=2;
		$results=$this->reportsmodel->pending_cheque();
		$table='<table border=1><tr>
			<td align="center"><font size=2><b>Bill&nbsp;ID</b></td>
			<td align="center"><font size=2><b>Submitted&nbsp;By&nbsp;</b></td>
			<td align="center"><font size=2><b>Company</b></td>
			<td align="center"><font size=2><b>Vendor&nbsp;</b></td>
			<td align="center"><font size=2><b>Cheque&nbsp;Name&nbsp;</b></td>
			<td align="center"><font size=2><b>Description&nbsp;</b></td>			
			<td align="center"><font size=2><b>Approved</b></td>
			<td align="center"><font size=2><b>Amount</b></td>
		</tr>';
		$xrow=0;
		foreach ($results->result() as $mydata){
		
			$get_cheque_assigned_name=$this->reportsmodel->get_cheque_assigned_name($mydata->id);
			
			$vendor=$mydata->vendor_name.'##'.$mydata->vendor_code;
			if($mydata->vendor_code==null or $mydata->vendor_code=='')$vendor=$this->reportsmodel->account_vendor($mydata->id);
			$xrow=$xrow+1;

			$table=$table.
					'<tr id="y'.$xrow.'">
					<td align="center"><font size=2><b>'.$mydata->id.'</b><input type="button" id="x'.$xrow.'" value="x"/></td>
					<td align="center"><font size=2><b>'.$mydata->vEmpName.'</b></td>
					<td align="center"><font size=2><b>'.$mydata->vCompany.'</b></td>
					<td align="center"><font size=2><b>'.$vendor.'</b></td>';
			if($mydata->cheque_date=='')
			{$table=$table.'<td align="center"><font size=2><b>'.$mydata->suggested_cheque.'</b></td>';}
			else {$table=$table.'<td align="center"><font size=2><b>'.$mydata->suggested_cheque.'<br/> Cheque&nbsp;Date:&nbsp;'.$mydata->cheque_date.'<br/>
			Cheque&nbsp;Date&nbsp;Assign&nbsp;By:&nbsp;'.$get_cheque_assigned_name.'
			</b></td>';}
			$table=$table.'<td align="center"><font size=2><b>'.$mydata->bill_description.'</b></td>';	
		
			$table2="<table border=1 width='100%'>"; 
			$results2=$this->reportsmodel->accepted_history($mydata->id);
			
			
				$sl=0; 
				foreach ($results2->result() as $mydata2){ 
				$sl=$sl+1;
				
				
			
				
				$table2=$table2.
					'<tr>
					<td align="center"><font size=2><b>'.$sl.'</b></td>
					<td align="center"><font size=2><b>'.$mydata2->displayname.'</b></td>
					<td align="center"><font size=2><b>'.$mydata2->vDesignation.'</b></td>
					<td align="center"><font size=2><b>'.$mydata2->date.'</b></td>
					</tr>';	
				}    
			$table2=$table2."</table>";	
			//if($mydata->bill_type=='general')
			$netamount=$mydata->amount-$mydata->advance-$mydata->tds-$mydata->vds-$mydata->tot_auth_deduction-$mydata->general_deduction;
		
			$table=$table.'<td align="center"><b>'.$table2.'</b></td><td align="center"><font size=2><b>'.$netamount.'</b></td></tr>';

			
		} 
		$table=$table.'</table>';	
		$data['table']=$table;
		$this->load->view('reports/report/rpt_pending_cheque', $data);
	
	}
	
	
	
	
	function pending_cheque_com($cmp=0){	
	
		$data['options']=2;
		$results=$this->reportsmodel->pending_cheque_com($cmp);
		$table='<table border=1><tr>
			<td align="center"><font size=2><b>Bill&nbsp;ID</b></td>
			<td align="center"><font size=2><b>Submitted&nbsp;By&nbsp;</b></td>
			<td align="center"><font size=2><b>Company</b></td>
			<td align="center"><font size=2><b>Vendor&nbsp;</b></td>
			<td align="center"><font size=2><b>Cheque&nbsp;Name&nbsp;</b></td>
			<td align="center"><font size=2><b>Description&nbsp;</b></td>			
			<td align="center"><font size=2><b></b></td>
			<td align="center"><font size=2><b>Amount</b></td>
		</tr>';
		$xrow=0;
		foreach ($results->result() as $mydata){
		//Cheque&nbsp;Date&nbsp;Assign&nbsp;By:&nbsp;'.$get_cheque_assigned_name.'
			//$get_cheque_assigned_name=$this->reportsmodel->get_cheque_assigned_name($mydata->id);
			
			$vendor=$mydata->vendor_name.'##'.$mydata->vendor_code;
			if($mydata->vendor_code==null or $mydata->vendor_code=='')$vendor=$this->reportsmodel->account_vendor($mydata->id);
			$xrow=$xrow+1;
			
			//$vendor="";
			$get_cheque_assigned_name="";
			
			$table=$table.
					'<tr id="y'.$xrow.'">
					<td align="center"><font size=2><b>'.$mydata->id.'</b><input type="button" id="x'.$xrow.'" value="x"/></td>
					<td align="center"><font size=2><b>'.$mydata->vEmpName.'</b></td>
					<td align="center"><font size=2><b>'.$mydata->vCompany.'</b></td>
					<td align="center"><font size=2><b>'.$vendor.'</b></td>';
			if($mydata->cheque_date=='')
			{$table=$table.'<td align="center"><font size=2><b>'.$mydata->suggested_cheque.'</b></td>';}
			else {$table=$table.'<td align="center"><font size=2><b>'.$mydata->suggested_cheque.'<br/> Cheque&nbsp;Date:&nbsp;'.$mydata->cheque_date.'<br/>
			
			</b></td>';}
			$table=$table.'<td align="center"><font size=2><b>'.$mydata->bill_description.'</b></td>';	
		/*
			$table2="<table border=1 width='100%'>"; 
			$results2=$this->reportsmodel->accepted_history($mydata->id);
			
			
				$sl=0; 
				foreach ($results2->result() as $mydata2){ 
				$sl=$sl+1;
				
				
			
				
				$table2=$table2.
					'<tr>
					<td align="center"><font size=2><b>'.$sl.'</b></td>
					<td align="center"><font size=2><b>'.$mydata2->displayname.'</b></td>
					<td align="center"><font size=2><b>'.$mydata2->vDesignation.'</b></td>
					<td align="center"><font size=2><b>'.$mydata2->date.'</b></td>
					</tr>';	
				}    
			$table2=$table2."</table>";	
			//if($mydata->bill_type=='general') */
			$netamount=$mydata->amount-$mydata->advance-$mydata->tds-$mydata->vds-$mydata->tot_auth_deduction-$mydata->general_deduction;
			$table2="";
			$table=$table.'<td align="center"><b>'.$table2.'</b></td><td align="center"><font size=2><b>'.$netamount.'</b></td></tr>';

			
		} 
		$table=$table.'</table>';	
		$data['table']=$table;
		$this->load->view('reports/report/rpt_pending_cheque', $data);
	
	}
	
	
	
	
	
	
	
	
	function pending_cash($cmp=0){	
	
		$data['options']=2;
		$results=$this->reportsmodel->pending_cash($cmp);
		$table='<table border=1><tr>
			<td align="center"><font size=2><b>Bill&nbsp;ID</b></td>
			<td align="center"><font size=2><b>Submitted&nbsp;By&nbsp;</b></td>
			<td align="center"><font size=2><b>Company</b></td>
	

			<td align="center"><font size=2><b>Description&nbsp;</b></td>			

			<td align="center"><font size=2><b>Amount</b></td>
		</tr>';
		$xrow=0;
		foreach ($results->result() as $mydata){
		
			//$get_cheque_assigned_name=$this->reportsmodel->get_cheque_assigned_name($mydata->id);
			
			$vendor=$mydata->vendor_name.'##'.$mydata->vendor_code;
			if($mydata->vendor_code==null or $mydata->vendor_code=='')$vendor=$this->reportsmodel->account_vendor($mydata->id);
			$xrow=$xrow+1;

			$table=$table.
					'<tr id="y'.$xrow.'">
					<td align="center"><font size=2><b>'.$mydata->id.'</b><input type="button" id="x'.$xrow.'" value="x"/></td>
					<td align="center"><font size=2><b>'.$mydata->vEmpName.'</b></td>
					<td align="center"><font size=2><b>'.$mydata->vCompany.'</b></td>
				';
		
			$table=$table.'<td align="center"><font size=2><b>'.$mydata->bill_description.'</b></td>';	
		 
			
			//if($mydata->bill_type=='general')
			$netamount=$mydata->amount-$mydata->advance-$mydata->tds-$mydata->vds-$mydata->tot_auth_deduction-$mydata->general_deduction;
		
			$table=$table.'<td align="center"><font size=2><b>'.$netamount.'</b></td></tr>';

			
		} 
		$table=$table.'</table>';	
		$data['table']=$table;
		$this->load->view('reports/report/rpt_pending_cheque', $data);
	
	}
	
	
	
	function pending_adjust($cmp=0){	
	
		$data['options']=2;
		$results=$this->reportsmodel->pending_adjust($cmp);
		$table='<table border=1><tr>
			<td align="center"><font size=2><b>Bill&nbsp;ID</b></td>
			<td align="center"><font size=2><b>Submitted&nbsp;By&nbsp;</b></td>
			<td align="center"><font size=2><b>Company</b></td>
	

			<td align="center"><font size=2><b>Description&nbsp;</b></td>			

			<td align="center"><font size=2><b>Amount</b></td>
		</tr>';
		$xrow=0;
		foreach ($results->result() as $mydata){
		
			//$get_cheque_assigned_name=$this->reportsmodel->get_cheque_assigned_name($mydata->id);
			
			$vendor=$mydata->vendor_name.'##'.$mydata->vendor_code;
			if($mydata->vendor_code==null or $mydata->vendor_code=='')$vendor=$this->reportsmodel->account_vendor($mydata->id);
			$xrow=$xrow+1;

			$table=$table.
					'<tr id="y'.$xrow.'">
					<td align="center"><font size=2><b>'.$mydata->id.'</b><input type="button" id="x'.$xrow.'" value="x"/></td>
					<td align="center"><font size=2><b>'.$mydata->vEmpName.'</b></td>
					<td align="center"><font size=2><b>'.$mydata->vCompany.'</b></td>
				';
		
			$table=$table.'<td align="center"><font size=2><b>'.$mydata->bill_description.'</b></td>';	
		 
			
			//if($mydata->bill_type=='general')
			$netamount=$mydata->amount-$mydata->advance-$mydata->tds-$mydata->vds-$mydata->tot_auth_deduction-$mydata->general_deduction;
		
			$table=$table.'<td align="center"><font size=2><b>'.$netamount.'</b></td></tr>';

			
		} 
		$table=$table.'</table>';	
		$data['table']=$table;
		$this->load->view('reports/report/rpt_pending_cheque', $data);
	
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

	
	function view_iou_for_audit_by_emp($emp_id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->get_iou_for_audit_by_emp($emp_id);
		$this->load->view('reports/report/rpt_iou_for_audit_by_emp', $data);
	
	}
	
	
	
	function bill_Report_For_WithoutPO(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_bill_Report_For_WithoutPO');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['sap_id']='y';	

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	function view_bill_Report_For_WithoutPO(){
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['options']=$this->input->post('reportType');
	
		$data['results']=$this->reportsmodel->cost_center_by_sapid($this->input->post('id'));
		
			$this->load->view('reports/report/rpt_view_cost_by_sap', $data);
		
		
	}
	
	
	
	function view_advance($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->view_advance($id);
		$data['results2']=$this->reportsmodel->get_advance_action($id);
		$data['results3']=$this->reportsmodel->cost_center_advance($id);

		$results3=$this->reportsmodel->view_advance_detials($id);
		
		$table3="";
		$sl=0;
			foreach ($results3->result() as $mydata3): 
			$sl=$sl+1;
			
			
			$str_scan=$this->advance_documents_scan($mydata3->id);
			
			$table3=$table3.
				'<tr>
				<td align="center"><b>Particular:&nbsp;'.$sl.'</b></td>
				<td align="center"><b>'.$mydata3->partcular_details.'</b></td>
				<td align="center"><b>'.$mydata3->particular_amount.'</b></td>
				<td align="center"><b>'.$str_scan.'</b></td>
				</tr>';	
			 endforeach; 

			 $data['table3']=$table3;

		$this->load->view('reports/report/rpt_view_advance', $data);
	
	}
	
	
	function advance_documents_scan($id)
	{
			$documents=$this->reportsmodel->get_advance_documents($id);
			$str="";
			$sl=1;
			foreach ($documents->result() as $mydata): 
			$str=$str.'Document-'.$sl.'&nbsp<a href="'.base_url().'index.php/reports/reports/url/'.$mydata->doc_file.'" target="about_blank" class="view">view</a>'.'<br/>';
			$sl=$sl+1;
			endforeach;

			 return $str;
	}
	
	
		function advance_print($id){	
	
		$data['options']=2;
		$data['results']=$this->reportsmodel->advance_print($id);
		$this->load->view('reports/report/rpt_advance_print', $data);
	
	}
	
	
	function download_advance_rpt(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_download_advance_rpt');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	
	function view_download_advance_rpt(){	
	

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));  
		$data['from']=$from;
		$data['to']=$to;		
		$data['results']=$this->reportsmodel->download_advance($from,$to);
	
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_download_advance', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	
	function download_iou_rpt(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_download_iou_rpt');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	
	
	function view_download_iou_rpt(){	
	

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));  
		$data['from']=$from;
		$data['to']=$to;		
		$data['results']=$this->reportsmodel->download_iou($from,$to);
	
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_download_iou', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	
	
	
	function download_audit_kpi(){
		$data=$this->set_empty_param();	
		$data['action'] = site_url('reports/reports/view_download_audit_kpi');	
		$data['userlevel']=$this->session->userdata('authlevel');
		$data['todateShow']='y';	
		$data['fromdateShow']='y';
		$data['companylist']='y';

		
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
		// echo '<pre>'  ;
		// var_dump($data) ;
		// echo '</pre>';
	}
	
	
	
	function view_download_audit_kpi(){	
		

		//getting post data 
		$from=$this->mkDate($this->input->post('from'));
		$to=$this->mkDate($this->input->post('to'));
		$company = $this->input->post('company');

		$data['from']=$from;
		$data['to']=$to;
		$data['company']=$company;

		
		$data['results']=$this->reportsmodel->download_audit_kpi($from,$to,$company);
	
		if($data['results']!=FALSE){
			//View the Report
			if($this->input->post('reportType')==2){
				echo "Still Open";
			}elseif($this->input->post('reportType')==1 || $this->input->post('reportType')==3){
				
				//View HTML Or XLS Report
				$data['options']=$this->input->post('reportType');
				$this->load->view('reports/report/rpt_download_audit_kpi', $data);
			}
		}
		// if this is getting emailed, don't delete just yet
		// instead just give back the invoice number
	
	}
	
	
	
	function Cost_Pending_Conveyance(){
		//get Empty Parameter
		$data=$this->set_empty_param();
		$data['userlevel']=$this->session->userdata('authlevel');
		// set validation properties
		//$this->_set_fields();
		
		$data['action'] = site_url('request/request/Cost_Pending_Conveyance');

		//load Company List
		$data['company_list']=$this->reportsmodel->list_company();

		
		//assign Report parameter
		//$data['fromdate']='y';
		$data['companyShow']='y';
		$data['lotShow']='y';
		//Load View	
		$data['page']='/reports/reportsParameter'; //add page name as a parameter
		$this->load->view('index',$data);
	}
	
	function advanceList($payment= "cash",$cmp=12){
	
		
		//Search 
		$data['title']="Advance List";
		// set user message
		$data['message']='';
	
		
		// load data
		$bill = $this->reportsmodel->get_advance_paged_list_cost($payment,$cmp)->result();
		

		
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
		$this->table->set_heading('id','advance&nbsp;date','location','company','description','Net&nbsp;Pay','Created&nbsp;by','Supervised&nbsp;by','Cost&nbspAllocation');

		$status="";
		
		//$i = 0 + $offset;
		$sl=1;
		foreach ($bill as $row){			
	

			
	
			
			$loc="";
			if($row->loc==1)$loc="Chittagong Head Office";
			else if($row->loc==2) $loc="Dhaka Office";
			else if($row->loc==3) $loc="Mohakhali Office";
			
			
		 if($row->advance_type=='Cheque' and $row->check_ready==0 )
			$Cheque_ready=anchor('bill/costbill/Cheque_ready_Advance/'.$row->id.'/'.$payment,'Check&nbsp;Ready',array('class'=>'add','onclick'=>"return confirm('Are you sure that Cheque is ready?')"));
		else 	$Cheque_ready="";


			$cost_center=$this->reportsmodel->cost_center_advance($row->id);

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
				$costable
	
			);
		$sl=$sl+1;
		}
		 return $this->table->generate();	
	}
	
	
	
	
}
