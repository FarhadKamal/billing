<?php
class Auditbill extends MY_Controller
{

	// num of records per page
	private $limit = 10;
	private $data;
	function __construct()
	{
		parent::__construct();
		// load library
		$this->load->library(array('table', 'validation'));
		// load model
		$this->load->model('/billauditmodel', '', TRUE);
		$this->load->model('/billsupervisemodel', '', TRUE);
		$this->load->model('/generalmodel', '', TRUE);
		$this->load->model('/billmodel', '', TRUE);
		if ($this->userauth->is_logged_in() and $this->session->userdata('authlevel') != 9) {

			show_401();
		}
	}

	function Index()
	{
	}

	function _set_fields()
	{

		$fields['general_deduction'] = 'General Deduction';
		$fields['general_deduction_note'] = 'General Deduction Note';
		$fields['tot_auth_deduction'] = '';
		$fields['bill_date'] = 'bill date';
		$fields['flow_type'] = 'flow type';
		$fields['loc'] = 'Location';
		$fields['Company'] = 'Company';
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

	function _set_rules()
	{
		$rules['loc'] = 'trim|required';
		$rules['po_no'] = 'trim|required';
		$rules['po_date'] = 'trim|required';
		$rules['iv_no'] = 'trim|required';
		$rules['iv_date'] = 'trim|required';

		$fields['gr_no'] = 'GR NO';
		$fields['gr_date'] = 'GR DATE';

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


	function viewDoc($doc)
	{
		$data['doc'] = $doc;
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('billView/viewDoc', $data);
	}


	function viewDocs($id)
	{
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['docs'] = $this->billauditmodel->get_documents($id);
		$this->load->view('billView/viewDocs', $data);
	}





	function makeQuery()
	{
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


			'tds' => $this->input->post('tds'),
			'vds' => $this->input->post('vds'),
			'general_deduction' => $this->input->post('general_deduction'),
			'general_deduction_note' => $this->input->post('general_deduction_note'),

			'amount' => $this->input->post('amount'),
			'advance' => $this->input->post('advance'),

			'payment_type' => $this->input->post('payment_type'),
			'suggested_cheque' => $this->input->post('suggested_cheque'),

			'audit_by' => $this->session->userdata('username'),
			'loc' => $this->input->post('loc')

		);
	}




	function mkDate($userDate)
	{
		if ($userDate != '') {
			$date_arr = explode('-', $userDate);
			$data = date("Y-m-d", mktime(0, 0, 0, $date_arr[1], $date_arr[0], $date_arr[2]));
			return $data;
		} else return '';
	}

	function getListData()
	{


		$data['company'] = $this->billauditmodel->list_company();
		$data['vendor'] = $this->billauditmodel->list_vendor();
		$data['costcentre'] = $this->billauditmodel->list_costcentre();
		$data['reportingOfficerList'] = $this->billauditmodel->list_head();
		return $data;
	}



	function billList($offset = 0, $message = '')
	{
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);


		$this->session->set_userdata('offset', $offset);





		//Search 
		$data['action'] = site_url('bill/auditbill/searchbill');
		$data['action2'] = site_url('bill/auditbill/searchSpecialbill');
		$data['title'] = "Bill List";
		// set user message
		$data['message'] = $message;

		if ($message == 'submitAccounts')
			$data['message'] = "<div class='success' align=left>Submitted Successful..!!</div>";
		else if ($message == 'cancel')
			$data['message'] = "<div class='cancel' align=left>Cancel Successful..!!</div>";
		else if ($message == 'park')
			$data['message'] = "<div class='success' align=left>Park Successful..!!</div>";

		// load data
		$bill = $this->billauditmodel->get_paged_list($this->limit, $offset)->result();

		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/auditbill/billList/');
		$number_of_rows = $this->billauditmodel->count_all();
		$config['total_rows'] = $number_of_rows;
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;

		$data['table'] = $this->bill_table($bill);
		$data['page'] = '/billView/auditList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}




	function parkList($offset = 0, $message = '')
	{
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		$data['title'] = "Parked bill List";
		// set user message
		$data['message'] = $message;

		if ($message == 'submitAccounts')
			$data['message'] = "<div class='success' align=left>Submitted Successful..!!</div>";


		// load data
		$bill = $this->billauditmodel->get_park_list($this->limit, $offset)->result();



		$data['table'] = $this->bill_table($bill);
		$data['page'] = '/billView/auditParkListAll'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}






	function bill_table($bill)
	{
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id', 'billing&nbsp;date', 'location', 'description', 'sap&nbsp;id', 'Payable&nbspAmount', 'Submitted&nbsp;by', 'status', 'last&nbsp;comment', 'action', 'documents');

		$status = "";

		//$i = 0 + $offset;
		$sl = 1;
		foreach ($bill as $row) {

			if ($row->step_status == 3)
				$status = "Processing";
			else if ($row->step_status == 4 and $row->account_head_pass == 0 and $row->account_pass == 0)
				$status = "Submitted to Accounts";
			else if ($row->step_status == 4 and $row->account_pass == 1)
				$status = "Submitted to Account Head";
			else if ($row->step_status == 5) {
				$status = "Accounts Clear";
				if ($row->payment_type == "Cheque" and $row->cheque_date == "" and $row->bill_date > "2013-06-15")
					$status = "Cheque Date Still not Assigned";
			} else if ($row->step_status == 6)
				$status = "Payment Made";

			if ($row->supervise_cancel == 1)
				$status = "Cancel by Supervisor<br/>Reason:&nbsp;" . $row->cancel_reason;
			else if ($row->authority_cancel == 1)
				$status = "Cancel by Authority<br/>Reason:&nbsp;" . $row->cancel_reason;
			else if ($row->audit_cancel == 1)
				$status = "Cancel by Audit<br/>Reason:&nbsp;" . $row->cancel_reason;


			if ($row->step_status == 3 and $row->supervise_cancel == 0 and $row->authority_cancel == 0 and $row->audit_cancel == 0) {


				if ($row->bill_type == "general" and $row->requisition_status == 1) {
					$view = anchor('bill/billforreq/updatebill/' . $row->id, 'process&nbsp;bill', array('class' => 'update'));
				} else if ($row->bill_type == "general" and $row->requisition_status == 0) {
					$view = anchor('bill/general/updatebill/' . $row->id, 'process&nbsp;bill', array('class' => 'update'));
				} else if ($row->bill_type == "vendor" and $row->requisition_status == 1) {
					$view = anchor('bill/vendorforreq/updatebill/' . $row->id, 'process&nbsp;bill', array('class' => 'update'));
				} else if ($row->bill_type == "distribution") {
					$view = anchor('bill/distribution/updatebill/' . $row->id, 'update&nbsp;bill', array('class' => 'update'));
				} else {
					$view = anchor('bill/auditbill/updatebill/' . $row->id, 'process&nbsp;bill', array('class' => 'update'));
				}

				$cancel = anchor('bill/auditbill/cancel/' . $row->id, 'cancel', array(
					'class' => 'cancel',
					'onclick' => "return confirm('Are you sure want to cancel?')"
				));
			} else {
				$view = "";
				$cancel = "";
			}


			if ($row->bill_type == "vendor") {
				$viewbill = anchor('reports/reports/view_bill/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
				$viewhistory = anchor('reports/reports/view_history/' . $row->id, 'view&nbsp;history', array('class' => 'view', 'target' => 'about_blank'));
			} else if ($row->bill_type == "distribution") {
				$viewbill = anchor('reports/reports/view_distribution/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
				$viewhistory = '';
			} else {
				$viewbill = anchor('reports/reports/view_general/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
				$viewhistory = anchor('reports/reports/view_details_history/' . $row->id, 'view&nbsp;history', array('class' => 'view', 'target' => 'about_blank'));
			}









			$viewbilldoc = "";
			/*
			$pchk="start";
			$pcount=1;
			$docsl=1;
			if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
			//$billdoc=$this->billauditmodel->get_documents($row->id);
			$billdoc=$this->billmodel->get_special_documents($row->id);		
			foreach($billdoc->result() as $rows)
						{ 						
						if(strlen($rows->doc_file)<5)
							$viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));	
							else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/auditbill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
						$docsl=$docsl+1;
						}
			
			}else if($row->bill_type=="general"){
			$billdoc=$this->generalmodel->get_special_documents($row->id);
			foreach($billdoc->result() as $rows)
						{ 

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
			*/
			$doctable = "<div id='" . $row->id . "'  class='divcost' ><font color='#FF000'><b>Double click here to view documents.</b></font></div>";

			if ($row->park_status == 0 and $row->supervise_cancel == 0 and $row->authority_cancel == 0 and $row->audit_cancel == 0) {
				$park = anchor('bill/auditbill/park/' . $row->id, 'Park', array('class' => 'add'));
			} else {
				$park = "";
				$status = "Park&nbsp;by:&nbsp;" . $row->park_by . '<br>Park&nbsp;Reason:&nbsp;' . $row->park_comment;
			}


			if ($row->step_status > 3) $park = "";

			$viewaction = anchor('reports/reports/view_action_bill/' . $row->id, 'view&nbsp;step', array('class' => 'view', 'target' => 'about_blank'));

			$viewiou = anchor('reports/reports/view_iou_for_audit_by_emp/' . $row->created_by, 'view&nbsp;iou', array('class' => 'view', 'target' => 'about_blank'));

			$loc = "";
			if ($row->loc == 1) $loc = "Chittagong Head Office";
			else if ($row->loc == 2) $loc = "Dhaka Office";
			else if ($row->loc == 3) $loc = "Mohakhali Office";

			$empName = $this->billmodel->get_holdby($row->created_by);

			//$netpay=($row->amount-$row->advance)."&nbsp;BDT";
			//if($row->bill_type=='general')
			$netpay = ($row->amount - $row->advance - $row->tds - $row->vds - $row->tot_auth_deduction - $row->general_deduction) . "&nbsp;BDT";


			$rowcmt = $this->billauditmodel->last_comment($row->id);

			if ($rowcmt->num_rows() == 1)
				$last_comment = $rowcmt->row()->last_comment;
			else $last_comment = '';


			$investigation = anchor('reports/reports/investigation/' . $row->id, 'investigation', array('class' => 'view', 'target' => 'about_blank'));



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
				$viewbill . "</br>" . $viewhistory . "</br>" . $viewaction . "</br>" . $view . "</br>" . $viewiou . "</br>" . $cancel . "</br>" . $park . "</br>" . $investigation,
				$doctable
			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}

	function get_doc_details()
	{
		$bill_id = $this->input->post('data');

		$row = $this->billauditmodel->get_by_id($bill_id)->row();

		$viewbilldoc = "";
		$docsl = 1;
		$pchk = "start";
		$pcount = 1;
		$doccount = 0;

		if ($row->bill_type == "vendor" or $row->bill_type == "distribution") {

			$billdoc = $this->billmodel->get_special_documents($row->id);
			foreach ($billdoc->result() as $rows) {

				$doccount = $doccount + 1;

				if (strlen($rows->doc_file) < 5)
					$viewbilldoc = $viewbilldoc . anchor('reports/reports/vendor_material_list/' . $rows->doc_id . '/' . $rows->detail_id . '/' . $rows->doc_file, 'document-' . $docsl, array('class' => 'view', 'target' => 'about_blank'));
				else $viewbilldoc =	$viewbilldoc . '<a href="' . base_url() . 'index.php/bill/auditbill/url/' . $rows->doc_file . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
				$docsl = $docsl + 1;
			}
		} else if ($row->bill_type == "general") {

			$billdoc = $this->generalmodel->get_special_documents($row->id);
			foreach ($billdoc->result() as $rows) {
				$doccount = $doccount + 1;
				if (strlen($rows->doc_file) < 5)
					$report = base_url() . 'index.php/reports/reports/order_material_list/' . $rows->doc_id . '/' . $rows->detail_id . '/' . $rows->particular . '/' . $rows->doc_file;
				else $report = base_url() . 'index.php/bill/auditbill/url/' . $rows->doc_file; //'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;

				if ($pchk == "start") {
					$viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
				} else if ($pchk == $rows->detail_id) {
					$viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
				} else {
					$pcount = $pcount + 1;
					$viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
				}
				$docsl = $docsl + 1;

				$pchk = $rows->detail_id;
			}
		}
		if ($doccount == 0) echo "<font color='#FF000'><b>No Document Found..</font>";
		echo $viewbilldoc;
	}



	function documten_table($items)
	{
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");


		//$i = 0 + $offset;
		$sl = 1;
		foreach ($items as $row) {

			$del = '&nbsp;' . anchor('bill/auditbill/deleteDoc/' . $row->id . '/' . $row->doc_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));
			//$view='&nbsp;'.anchor('bill/auditbill/viewDoc/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			//$view='<a href="ftp://billmaster:stargold@192.168.1.117/Common_share/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$view = '<a href="' . base_url() . 'index.php/bill/bill/url/' . $row->doc_file . '" target="about_blank" class="view">view</a>';
			$doc_file = $row->doc_file;
			if ($row->doc_file == "0") {
				$doc_file = "Requisition";
				$view = anchor('reports/reports/view_material_list/' . $row->id, 'view', array('class' => 'view', 'target' => 'about_blank'));
				$del = '&nbsp;' . anchor('bill/auditbill/deleteRequisition/' . $row->id . '/' . $row->doc_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));
			}
			$this->table->add_row(
				$sl,
				$doc_file,
				$view . '&nbsp;' . $del
			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}




	function searchbill()
	{

		//Search 
		$data['action'] = site_url('bill/auditbill/searchbill');
		$data['action2'] = site_url('bill/auditbill/searchSpecialbill');
		$data['title'] = "bill List";
		// set user message
		$data['message'] = '';

		// load data
		$bill = $this->billauditmodel->get_search_by_id($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')), $this->input->post('loc'))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->billauditmodel->count_search($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')), $this->input->post('loc'));

		$data['table'] = $this->bill_table($bill);
		$data['page'] = '/billView/auditList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}



	function searchSpecialbill()
	{

		//Search 
		$data['action'] = site_url('bill/auditbill/searchbill');
		$data['action2'] = site_url('bill/auditbill/searchSpecialbill');
		$data['title'] = "bill List";
		// set user message
		$data['message'] = '';

		// load data
		$bill = $this->billauditmodel->get_special_search_by_id($this->input->post('stype'), $this->input->post('svalue'))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->billauditmodel->count_search_special($this->input->post('stype'), $this->input->post('svalue'));

		$data['table'] = $this->bill_table($bill);
		$data['page'] = '/billView/auditList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}





	function submitAccounts($id)
	{
		$this->billauditmodel->submitAccounts($id);
		redirect('bill/auditbill/billList/0/submitAccounts');
	}




	function deleteDoc($id1, $id2)
	{
		$this->billauditmodel->deleteDoc($id1);

		redirect('bill/auditbill/updatebill/' . $id2, 'location');
	}




	function updatebill($id, $filechk = 1)
	{

		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();

		$rows = $this->billauditmodel->get_by_id($id)->row();
		$this->validation->bill_date = 						date('d-m-Y', strtotime($rows->bill_date));
		$this->validation->Company = 						$rows->company_id;
		$this->validation->Vendor = 						$rows->vendor_code;
		$this->validation->po_no = 							$rows->po_no;
		$this->validation->po_date = 						date('d-m-Y', strtotime($rows->po_date));
		$this->validation->gr_no = 							$rows->gr_no;
		$this->validation->gr_date = 						date('d-m-Y', strtotime($rows->gr_date));
		$this->validation->iv_no = 							$rows->iv_no;
		$this->validation->iv_date = 						date('d-m-Y', strtotime($rows->iv_date));
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
		$this->validation->tot_auth_deduction = 			$rows->tot_auth_deduction;
		$this->validation->general_deduction_note = 		$rows->general_deduction_note;
		$this->validation->general_deduction = 				$rows->general_deduction;
		$this->validation->step_status = 					$rows->step_status;
		$data = $this->getListData();

		$data['openmodel'] = 		'yes';
		$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->billauditmodel->tot_document_by_bill_id($id);
		//$items=$this->billauditmodel->get_documents($id)->result();	
		$items = $this->billmodel->get_documents_requisition($id)->result();
		$data['table'] =			$this->documten_table($items);

		// run validation
		if ($this->validation->run() == FALSE) {


			if ($filechk == 2)
				$data['message'] = '<div class="cancel" align="left">File Not Found..</div>';
			else if ($filechk == 3)
				$data['message'] = '<div class="cancel" align="left">FTP Connection Fail..</div>';
			else if ($filechk == 4)
				$data['message'] = '<div class="cancel" align="left">FTP Login Fail..</div>';
			else if ($filechk == 11)
				$data['message'] = '<div class="cancel" align="left">This file is already saved in the system..</div>';
			else $data['message'] = '';
		} else if ($this->input->post('amount') != $this->input->post('retypeamount')) {
			$data['message'] = '<div class="cancel" align=left>Please Check Amount</div>';
		} else {
			// save data

			$this->billauditmodel->update($id, $this->makeQuery());

			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}


		$data['title'] = 'Process Vendor Bill';
		$data['action'] = site_url('bill/auditbill/updatebill/' . $id);
		$data['action2'] =  site_url('bill/auditbill/addDoc/' . $id);
		$data['action3'] =  site_url('bill/auditbill/FinalSubmit/' . $id);
		$data['page'] = '/billView/billEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['usercode'] = "audit";
		if (in_array($this->validation->Company, array(1, 3))) {
			$data['usercode'] = 4;
			$data['reportingOfficerList'] = $this->billsupervisemodel->list_audit_options($this->validation->amount);
		}




		$data['entry'] = 0;
		$data['recommend'] = 1;
		$this->load->view('index', $data);
	}



	function addDoc($id)
	{


		$ftp_server = "192.168.1.117";
		$conn_id = ftp_connect($ftp_server)
			or redirect('bill/auditbill/updatebill/' . $id . '/3', 'location');

		$login_result = ftp_login($conn_id, "bill", "bill007");
		if ((!$conn_id) || (!$login_result))
			redirect('bill/auditbill/updatebill/' . $id . '/4', 'location');




		$str = str_replace('\\', '/', $this->input->post('doc_file'));
		$doubleFileChk = $this->billauditmodel->doubleFileChk($str);
		if ($doubleFileChk > 0)
			redirect('bill/auditbill/updatebill/' . $id . '/11', 'location');
		$pizza  = $str;
		$pieces = explode("/", $pizza);
		$piecescount = count($pieces);

		if ($piecescount != 2) {
			ftp_close($conn_id);
			redirect('bill/auditbill/updatebill/' . $id . '/2', 'location');
		}


		$path = "/BILL/" . $pieces[0] . "/";
		ftp_pasv($conn_id, true);
		$contents_on_server = ftp_nlist($conn_id, $path);
		ftp_close($conn_id);

		if (in_array($pieces[1], $contents_on_server) and $this->input->post('doc_file') != "") {

			$this->billauditmodel->saveDoc(
				array(
					'doc_file' => $str,
					'doc_id' => $id
				)
			);
			redirect('bill/auditbill/updatebill/' . $id . '/1', 'location');
		} else redirect('bill/auditbill/updatebill/' . $id . '/2', 'location');
	}



	function park($id)
	{

		$data['message'] = '';
		$data['comment'] = '';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['title'] = 'Parking';
		$data['action'] =  site_url('bill/auditbill/parkadd/' . $id . '/');
		$data['page'] = '/billView/comment'; //add page name as a parameter
		$this->load->view('index', $data);
	}


	function parkadd($id)
	{

		$this->billauditmodel->updatepark(
			$id,
			array(
				'park_status' => 1, 'park_by' =>  $this->session->userdata('username'),

				'park_comment' => $this->input->post('comment')

			)
		);

		redirect('bill/auditbill/billList/0/park');
	}


	function cancel($id)
	{

		$data['message'] = '';
		$data['comment'] = '';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['title'] = 'Cancel Reason';
		$data['action'] =  site_url('bill/auditbill/canceladd/' . $id . '/');
		$data['page'] = '/billView/comment'; //add page name as a parameter
		$this->load->view('index', $data);
	}


	function canceladd($id)
	{

		$this->billauditmodel->cancel(
			$id,
			array(
				'audit_cancel' => 1, 'audit_by' =>  $this->session->userdata('username'),

				'cancel_reason' => $this->input->post('comment')

			)
		);
		$this->billauditmodel->action_doc(
			array(
				'doc_id' => $id, 'action' => 'cancel', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
			)
		);

		redirect('bill/auditbill/billList/0/cancel');
	}


	function FinalSubmit($id)
	{

		$compchk = $this->billsupervisemodel->get_by_id($id)->row()->company_id;
		if ($this->input->post('ReportingOfficer') == "account") {
			$this->billauditmodel->FinalSubmit(
				$id,
				array(
					'park_status' => 0, 'step_status' => 4, 'audit_by' => $this->session->userdata('username'), 'audit_approved_date' => date("Y-m-d H:i:s"), 'audit_comment' => $this->input->post('comment')
				)
			);
			$this->billauditmodel->action_doc(
				array(
					'doc_id' => $id, 'action' => 'accepted', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
				)
			);
		} else if ($this->input->post('ReportingOfficer') == "fin") {
			if ($compchk == 6 or $compchk == 18 or $compchk == 21 or $compchk == 24  or $compchk == 7)
				$this->billauditmodel->SubmitFinNew($id);
			else
				$this->billauditmodel->SubmitFin($id);
			$this->billauditmodel->FinalSubmit(
				$id,
				array(
					'audit_approved_date' => date("Y-m-d H:i:s"), 'audit_comment' => $this->input->post('comment')
				)
			);
			$this->billauditmodel->action_doc(
				array(
					'doc_id' => $id, 'action' => 'accepted', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
				)
			);
		} else if ($this->input->post('ReportingOfficer') == "director") {
			$this->billauditmodel->ReSubmitDirector($id);
			$this->billauditmodel->FinalSubmit(
				$id,
				array(
					'audit_approved_date' => date("Y-m-d H:i:s"), 'audit_comment' => $this->input->post('comment')
				)
			);
			$this->billauditmodel->action_doc(
				array(
					'doc_id' => $id, 'action' => 'accepted', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
				)
			);
		} else if ($this->input->post('ReportingOfficer') == "dep") {
			$this->billauditmodel->ReSubmitDep($id);
			$this->billauditmodel->FinalSubmit(
				$id,
				array(
					'audit_comment' => $this->input->post('comment')
				)
			);

			$this->billauditmodel->action_doc(
				array(
					'doc_id' => $id, 'action' => 'Returned', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
				)
			);
		} else if ($this->input->post('ReportingOfficer') == "claimer") {
			$this->billauditmodel->ReSubmitEmp($id);
			$this->billauditmodel->FinalSubmit(
				$id,
				array(
					'audit_comment' => $this->input->post('comment')
				)
			);
			$this->billauditmodel->action_doc(
				array(
					'doc_id' => $id, 'action' => 'Returned', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
				)
			);
		}

		if (strlen($this->session->userdata('offset')) > 0)
			$offset = $this->session->userdata('offset');
		else $offset = 0;

		redirect('bill/auditbill/billList/' . $offset . '/submitAccounts');
	}

	function deleteRequisition($id1, $id2)
	{
		$this->generalmodel->requisitionDelete($id1);


		redirect('bill/auditbill/updatebill/' . $id2, 'location');
	}

	/*
	function url($url1,$url2)
	{	
		//redirect('ftp://billmaster:stargold@192.168.1.117/Common_share/'.$url, 'refresh');
		$location='location: ftp://bill:bill007@192.168.1.117/BILL/'.$url1.'/'.$url2;
	
		header($location);
	}
	*/
	function url($url1, $url2)
	{
		//redirect('ftp://billmaster:stargold@192.168.1.117/Common_share/'.$url, 'refresh');
		$location = 'location: ftp://bill:bill007@192.168.1.117/BILL/' . $url1 . '/' . $url2;
		$chkjpg = strtoupper($url2);
		if (strpos($chkjpg, 'JPG')) {

			//header('Content-Type: image/jpeg');

			echo  '<img style="width:900px;" src="data:image/jpeg;Base64,' . base64_encode(file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/" . $url1 . '/' . $url2)) . '"/>';
		}/*
		else if(strpos( $chkjpg , 'PDF' ) ) 
		{

			//header('Content-Type: image/jpeg');
		
		
			echo  '<embed type="application/pdf" src="'.base64_encode(file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/".$url1.'/'.$url2)).'"/>';
		}*/ else {
			// header($location);	

			$this->load->helper('download');

			$data = file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/" . $url1 . '/' . $url2);
			$name = $url2;

			force_download($name, $data);
		}
	}
}
