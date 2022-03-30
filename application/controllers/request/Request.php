<?php
class Request extends MY_Controller
{

	// num of records per page
	private $limit = 10;
	private $data;
	function __construct()
	{
		parent::__construct();
		// load library
		$this->load->library(array('table', 'validation'));

		// load helper
		$this->load->helper('url');

		//load file
		$this->load->helper('file');

		// load model
		$this->load->model('/requestModel/requestmodel', '', TRUE);


		/*if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') == 2) {
		
			show_404();
		}*/
	}

	function Index()
	{
	}






	function RequestForConveyance()
	{
		$data['userlevel'] = $this->session->userdata('authlevel');

		if ($this->validation->run() == FALSE) {
			//Getting all drop down data
			$data['employee'] = $this->requestmodel->list_employeeid();
		}
		$data['company_list'] = $this->requestmodel->list_company();


		$reportTo = $this->requestmodel->reportTo($this->session->userdata('username'))->row()->ReportingOfficer;
		$data['reportTo'] = $reportTo;

		$data['boss_list'] = $this->requestmodel->list_boss($reportTo);




		// set common properties
		$data['title'] = 'Conveyance Bill Form';
		$data['action'] =  site_url('request/request/RequestForConveyance');

		$this->_set_fields_conveyance();
		$this->_set_rules_conveyance();

		$this->validation->EmpId =	$this->session->userdata('username');

		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
		} else if ($this->requestmodel->duplicate_chk_conveyance($this->input->post('EmpId'), $this->mkDate($this->input->post('journey_date')), $this->input->post('vfrom'), $this->input->post('vto'), $this->input->post('amount')) > 0) {
			$data['message'] = '<div class="error" align=left>Already Submitted!</div>';
		} else {
			// save data
			$request = $this->makeQueryForReqConvence();

			//print_r($request);
			$this->requestmodel->saveRequestConveyance($request);

			// set user message

			$data['message'] = '<div class="success" align=left>Request Send Successful..</div>';

			//$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);


		}

		$data['page'] = '/requestView/reqConveyanceNWOEdit'; //add page name as a parameter
		$this->load->view('index', $data);
	}






	function Dep_Request_Conveyance($msgtyp = 1)
	{
		$data['userlevel'] = $this->session->userdata('authlevel');
		if ($this->session->userdata('authlevel') == 6) {
			// offset
			$message = '';
			$uri_segment = 4;
			if ($msgtyp == 2) $message = '<div class="success" align=left>Accepted!</div>';
			if ($msgtyp == 3) $message = '<div class="cancel" align=left>Cancel!</div>';
			//Search 

			// set user message
			$data['message'] = $message;


			// load data
			$request = $this->requestmodel->get_req_conveyance_IS_list($this->session->userdata('username'))->result();



			// generate table data
			$this->load->library('table');
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Employee Id', 'Compnay', 'Employee Name', 'Journey Date', 'Apply Date', 'Purpose', 'From', 'To', 'Transport Mode', 'Updown', 'Amount', 'Action');

			//$i = 0 + $offset;

			foreach ($request as $row) {
				$link = '';


				$link = '&nbsp;' . anchor('request/request/Conveyance_Accept_Dep/' . $row->SL, 'accept', array('class' => 'confirm', 'onclick' => "return confirm('Are you sure want to accept?')"));

				$link = $link . ' ' . '&nbsp;' . anchor('request/request/Conveyance_Cancel_Dep/' . $row->SL, 'cancel', array('class' => 'cancel', 'onclick' => "return confirm('Are you sure want to cancel?')"));
				$this->table->add_row(
					$row->EmpId,
					$row->vCompany,
					$row->vEmpName,
					$row->journey_date,
					$row->created_date,
					$row->purpose,
					$row->vfrom,
					$row->vto,
					$row->trans_mode,
					$row->updown,
					$row->amount . ' ' . '&nbsp;' . anchor('request/request/Conveyance_Amount/' . $row->SL, 'Edit'),

					$link
				);
			}
			$data['table'] = $this->table->generate();
			$data['page'] = '/requestView/reqConveyanceDepList'; //add page name as a parameter
			$this->load->view('index', $data);
		}
	}









	function Conveyance_Amount($id)
	{
		$data['userlevel'] = $this->session->userdata('authlevel');


		// set common properties
		$data['title'] = 'Conveyance Bill Form';
		$data['action'] =  site_url('request/request/Update_Conveyance_Amount');
		$data['company_list'] = $this->requestmodel->list_company();


		$this->_set_fields_conveyance();
		$this->_set_rules_conveyance();

		$request = $this->requestmodel->get_req_conveyance_by_id($id)->row();



		$this->validation->SL =	$request->SL;

		$this->validation->EmpId =	$request->EmpId;
		$this->validation->journey_date =	date('d-m-Y', strtotime($request->journey_date));
		$this->validation->purpose =	$request->purpose;
		$this->validation->vfrom =	$request->vfrom;
		$this->validation->vto =	$request->vto;
		$this->validation->amount =	$request->amount;
		$this->validation->trans_mode =	$request->trans_mode;
		$this->validation->company =	$request->company;
		$this->validation->updown =	$request->updown;
		$this->validation->loc =	$request->loc;


		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
		} else {
			// save data
			//$request=$this->makeQueryForReqConvence();

			//print_r($request);
			//$this->requestmodel->saveRequestConveyance($request);

			// set user message

			//$data['message'] = '<div class="success" align=left>Request Send Successful..</div>';

			//$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);


		}

		$data['page'] = '/requestView/reqConveyanceEdit'; //add page name as a parameter
		$this->load->view('index', $data);
	}


	function Update_Conveyance_Amount()
	{

		// set common properties
		$data['title'] = 'Conveyance Bill Form';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['action'] = site_url('request/request/Update_Conveyance_Amount');
		$data['company_list'] = $this->requestmodel->list_company();
		//$data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));


		// set validation properties
		$this->_set_fields_conveyance();
		$this->_set_rules_conveyance();

		// run validation
		if ($this->validation->run() == FALSE) {
			$data['message'] = '';
			// load view 

		} else {
			// save data
			$SL = $this->input->post('SL');
			$request = $this->makeUpdateQueryForReqConvence();
			//print_r($request);

			$this->requestmodel->updateConveyance($SL, $request);
			// set user message
			$message = '<div class="success">update successful..</div>';
			//redirect to member list Page
			$data['message'] = $message; //add page name as a parameter

		}

		$data['page'] = '/requestView/reqConveyanceEdit'; //add page name as a parameter
		$this->load->view('index', $data);
	}

	function makeUpdateQueryForReqConvence()
	{
		if ($this->input->post('updown'))
			$updown = "yes";
		else $updown = "no";

		return $member = array(
			'EmpId' => $this->input->post('EmpId'),
			'journey_date' => $this->mkDate($this->input->post('journey_date')),
			'purpose' => $this->input->post('purpose'),
			'vfrom' => $this->input->post('vfrom'),
			'vto' => $this->input->post('vto'),
			'trans_mode' => $this->input->post('trans_mode'),
			'company' => $this->input->post('company'),
			'amount' => $this->input->post('amount'),
			'loc' => $this->input->post('loc'),
			'updown' => $updown
		);
	}



	function Audit_Request_Conveyance($msgtyp = 1)
	{
		$data['userlevel'] = $this->session->userdata('authlevel');
		if ($this->session->userdata('authlevel') == 7) {
			// offset
			$message = '';
			$uri_segment = 4;
			if ($msgtyp == 2) $message = '<div class="success" align=left>Accepted!</div>';
			if ($msgtyp == 3) $message = '<div class="cancel" align=left>Cancel!</div>';
			//Search 

			// set user message
			$data['message'] = $message;


			// load data
			$request = $this->requestmodel->get_req_conveyance_audit_list($this->session->userdata('username'))->result();



			// generate table data
			$this->load->library('table');
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Employee Id', 'Company', 'Employee Name', 'Supervisor', 'Journey Date', 'Apply Date', 'Purpose', 'From', 'To', 'Transport Mode', 'Updown', 'Amount', 'Action');

			//$i = 0 + $offset;

			foreach ($request as $row) {
				$link = '';


				$link = '&nbsp;' . anchor('request/request/Conveyance_Accept_Audit/' . $row->SL, 'accept', array('class' => 'confirm', ''));

				$link = $link . ' ' . '&nbsp;' . anchor('request/request/Conveyance_Cancel_Audit/' . $row->SL, 'cancel', array('class' => 'cancel', 'onclick' => "return confirm('Are you sure want to cancel?')"));
				$this->table->add_row(
					$row->EmpId,
					$row->vCompany,
					$row->vEmpName,
					$row->reporting,
					$row->journey_date,
					$row->created_date,
					$row->purpose,
					$row->vfrom,
					$row->vto,
					$row->trans_mode,
					$row->updown,
					$row->amount . ' ' . '&nbsp;' . anchor('request/request/Conveyance_Amount/' . $row->SL, 'Edit'),

					$link
				);
			}
			$data['table'] = $this->table->generate();
			$data['page'] = '/requestView/reqConveyanceAuditList'; //add page name as a parameter
			$this->load->view('index', $data);
		}
	}




	function Conveyance_Leave_status()
	{
		$data['userlevel'] = $this->session->userdata('authlevel');


		$uri_segment = 4;

		// set user message
		$data['message'] = '';


		// load data
		$request = $this->requestmodel->get_req_conveyance_status($this->session->userdata('username'))->result();



		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('Lot No', 'Employee Id', 'Company', 'Employee Name', 'Journey Date', 'Apply Date', 'Purpose', 'From', 'To', 'Transport Mode', 'Updown', 'Amount', 'Status');

		//$i = 0 + $offset;

		foreach ($request as $row) {

			$status = "<div class='pending' align=left>Pending</div>";
			if ($row->status == 1) $status = "<div class='Pending' align=left>recommended</div>";
			if ($row->status == 3) $status = "<div class='cancel' align=left>Cancel</div>";
			if ($row->status == 2 && $row->paid == 0) $status = "<div class='success' align=left>Accepted</div> <div class='pending' align=left>Payment&nbspPending</div>";
			if ($row->status == 2 && $row->paid == 1) $status = "<div class='success' align=left>Accepted</div> <div class='success' align=left>Payment&nbspMade</div>";
			$this->table->add_row(
				$row->lot_no,
				$row->EmpId,
				$row->vCompany,
				$row->vEmpName,
				$row->journey_date,
				$row->created_date,
				$row->purpose,
				$row->vfrom,
				$row->vto,
				$row->trans_mode,
				$row->updown,
				$row->amount,
				$status


			);
		}
		$data['table'] = $this->table->generate();
		$data['page'] = '/requestView/reqConveyanceStatusList'; //add page name as a parameter
		$this->load->view('index', $data);
	}








	function Conveyance_Accept_Dep($id)
	{
		if ($this->session->userdata('authlevel') == 6) {
			$this->requestmodel->accept_conveyance_by_is($id);

			$this->requestmodel->save_mis_conveyance_action(array(
				'user_id' => $this->session->userdata('username'),
				'con_id' => $id,
				'user_action' => 'accepted'
			));

			redirect('request/request/Dep_Request_Conveyance/2/');
		}
	}





	function Conveyance_Cancel_Dep($id)
	{
		if ($this->session->userdata('authlevel') == 6) {
			$this->requestmodel->cancel_conveyance_by_is($id);

			$this->requestmodel->save_mis_conveyance_action(array(
				'user_id' => $this->session->userdata('username'),
				'con_id' => $id,
				'user_action' => 'canceled'
			));

			redirect('request/request/Dep_Request_Conveyance/3/');
		}
	}


	/*
		function Conveyance_Accept_Audit($id){
		if($this->session->userdata('authlevel')==7)
		{
			$this->requestmodel->accept_conveyance_by_audit($id);

			redirect('request/request/Audit_Request_Conveyance/2/');
			
		}
		
	}
	
	*/



	function Conveyance_Cancel_Audit($id)
	{
		if ($this->session->userdata('authlevel') == 7) {
			$this->requestmodel->cancel_conveyance_by_audit($id);

			$this->requestmodel->save_mis_conveyance_action(array(
				'user_id' => $this->session->userdata('username'),
				'con_id' => $id,
				'user_action' => 'canceled'
			));

			redirect('request/request/Audit_Request_Conveyance/3/');
		}
	}



	function mkDate($userDate)
	{
		$date_arr = explode('-', $userDate);
		$data = date("Y-m-d", mktime(0, 0, 0, $date_arr[1], $date_arr[0], $date_arr[2]));
		return $data;
	}

	function _set_fields_conveyance()
	{

		$fields['SL'] = 'SL';
		$fields['EmpId'] = 'EmpId';
		$fields['reporting_to'] = 'reporting_to';
		$fields['journey_date'] = 'journey_date';
		$fields['purpose'] = 'purpose';
		$fields['vfrom'] = 'vfrom';
		$fields['vto'] = 'vto';
		$fields['trans_mode'] = 'trans_mode';
		$fields['amount'] = 'amount';
		$fields['company'] = 'company';
		$fields['updown'] = 'updown';
		$fields['loc'] = 'location';
		$this->validation->set_fields($fields);
	}

	function _set_rules_conveyance()
	{
		$rules['journey_date'] = 'trim|required';
		$rules['purpose'] = 'trim|required';
		$rules['vfrom'] = 'trim|required';
		$rules['vto'] = 'trim|required';
		$rules['trans_mode'] = 'trim|required';
		$rules['amount'] = 'trim|required';
		$rules['company'] = 'trim|required';
		$rules['loc'] = 'trim|required';
		$this->validation->set_rules($rules);
		$this->validation->set_message('required', '* required');
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}


	function makeQueryForReqConvence()
	{


		$purpose =	str_replace("'", " ", $this->input->post('purpose'));
		$purpose =	str_replace("\"", " ", $purpose);
		$purpose =	str_replace("�", " ", $purpose);

		$vfrom =	str_replace("'", " ", $this->input->post('vfrom'));
		$vfrom =	str_replace("\"", " ", $vfrom);
		$vfrom =	str_replace("�", " ", $vfrom);

		$vto =		str_replace("'", " ", $this->input->post('vto'));
		$vto =		str_replace("\"", " ", $vto);
		$vto =		str_replace("�", " ", $vto);


		return $member = array(
			'EmpId' => 		$this->input->post('EmpId'),
			'journey_date' => 	$this->mkDate($this->input->post('journey_date')),
			'purpose' => 		$purpose,
			'vfrom' => 			$vfrom,
			'vto' => 			$vto,
			'trans_mode' => 	$this->input->post('trans_mode'),
			'amount' => 		$this->input->post('amount'),
			'created_date' =>  	date('Y-m-d'),
			'company' =>  		$this->input->post('company'),
			'updown' =>  		$this->input->post('updown'),
			'loc' =>  			$this->input->post('loc'),
			'reporting_to' => 	$this->input->post('reporting_to')
		);
	}




	function Conveyance_Accept_Audit($id)
	{
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['costcentre'] = $this->requestmodel->list_costcentre();
		$data['area'] = $this->requestmodel->list_area();
		$data['internal_order'] = $this->requestmodel->list_internal_order();
		// set common properties
		$data['title'] = 'Conveyance Bill Accept Form';
		$data['action'] =  site_url('request/request/Conveyance_Accept_Audit/' . $id . '/');

		$this->_set_fields_conveyance_accept();
		$this->_set_rules_conveyance_accept();

		//$this->validation->EmpId =	$this->session->userdata('username');

		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
		} else {
			// save data
			$request = $this->makeQueryForAcceptConvence();

			//print_r($request);
			$this->requestmodel->saveAcceptConveyance($id, $request);

			$this->requestmodel->save_mis_conveyance_action(array(
				'user_id' => $this->session->userdata('username'),
				'con_id' => $id,
				'user_action' => 'accepted'
			));

			// set user message

			$data['message'] = '<div class="success" align=left>Request Send Successful..</div>';

			//$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);
			redirect('request/request/Audit_Request_Conveyance/2/');
		}
		$this->validation->lot_no = $this->requestmodel->get_lot_no();
		$data['page'] = '/requestView/reqConveyanceAccept'; //add page name as a parameter
		$this->load->view('index', $data);
	}



	function _set_fields_conveyance_accept()
	{

		$fields['lot_no'] = 'lot_no';
		$fields['CostCentre'] = 'CostCentre';
		$fields['Company'] = 'Company';
		$fields['internal_order'] = 'internal_order';

		$this->validation->set_fields($fields);
	}

	function _set_rules_conveyance_accept()
	{
		$rules['CostCentre'] = 'trim|required';
		$rules['Company'] = 'trim|required';
		$rules['internal_order'] = 'trim|required';
		$this->validation->set_rules($rules);
		$this->validation->set_message('required', '* required');
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}

	function makeQueryForAcceptConvence()
	{

		return $member = array(
			'lot_no' => $this->input->post('lot_no'),
			'lot_date' => date('Y-m-d'),
			'costcenter_id' => $this->input->post('CostCentre'),
			'business_area' => $this->input->post('Company'),
			'internal_order' => $this->input->post('internal_order'),
			'status' => 2

		);
	}




	function Cost_Pending_Conveyance()
	{

		$data['results'] = $this->requestmodel->pending_conveyance_cost($this->input->post('lot_no'), $this->input->post('company'));
		$data['lot_no'] = $this->input->post('lot_no');
		$data['options'] = $this->input->post('reportType');
		$this->load->view('requestView/viewPendingConveyanceBillCost', $data);
	}




	function Cost_Request_Conveyance($msgtyp = 1)
	{
		$data['userlevel'] = $this->session->userdata('authlevel');
		if ($this->session->userdata('authlevel') == 8 or $this->session->userdata('username') == 1098) {
			// offset
			$message = '';
			$uri_segment = 4;
			if ($msgtyp == 2) $message = '<div class="success" align=left>Payment made!</div>';
			if ($msgtyp == 3) $message = '<div class="cancel" align=left>Cancel!</div>';
			//Search 

			// set user message
			$data['message'] = $message;


			// load data
			$request = $this->requestmodel->get_accept_conveyance_audit_list()->result();



			// generate table data
			$this->load->library('table');
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Lot No', 'Employee Id', 'Company', 'Employee Name', 'Journey Date', 'Apply Date', 'Purpose', 'From', 'To', 'Transport Mode', 'Updown', 'Amount', 'Action');

			//$i = 0 + $offset;

			foreach ($request as $row) {
				$link = '';


				$link = '&nbsp;' . anchor('request/request/Conveyance_Paid_Cost/' . $row->SL, 'Make&nbsp;Payment', array('class' => 'confirm', 'onclick' => "return confirm('Are you sure want to accept?')"));
				$link = $link . '&nbsp;' . anchor('request/request/view_conveyance_bill/' . $row->SL, 'view', array('class' => 'view', 'target' => '_blank'));;
				$this->table->add_row(
					$row->lot_no,
					$row->EmpId,
					$row->vCompany,
					$row->vEmpName,
					$row->journey_date,
					$row->created_date,
					$row->purpose,
					$row->vfrom,
					$row->vto,
					$row->trans_mode,
					$row->updown,
					$row->amount,

					$link
				);
			}
			$data['table'] = $this->table->generate();
			$data['page'] = '/requestView/reqConveyanceDepList'; //add page name as a parameter
			$this->load->view('index', $data);
		}
	}



	function view_conveyance_bill($sl)
	{
		$data['page_title'] = 'Conveyance Bill';
		$data['results'] = $this->requestmodel->view_conveyance_bill($sl);
		$this->load->view('requestView/viewConveyanceBill', $data);
	}

	function Conveyance_Paid_Cost($id)
	{
		if ($this->session->userdata('authlevel') == 8) {


			$this->requestmodel->payment_made($id);

			redirect('request/request/Cost_Request_Conveyance/2/');
		}
	}




	function Quick_Pay_Conveyance()
	{
		$data['userlevel'] = $this->session->userdata('authlevel');

		$data['lot_list'] = $this->requestmodel->lot_list();



		// set common properties
		$data['title'] = 'Quick Conveyance Pay';
		$data['action'] =  site_url('request/request/Quick_Pay_Conveyance');

		$this->_set_fields_quick_conveyance_pay();
		$this->_set_rules_quick_conveyance_pay();

		//$this->validation->EmpId =	$this->session->userdata('username');

		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
			$data['page'] = '/requestView/quickConveyanceAccept'; //add page name as a parameter
		} else {
			$data['action'] =  site_url('request/request/process_Quick_Pay_Conveyance');

			$data['lot_no'] =  $this->input->post('lot_no');
			$data['companyid'] =  $this->input->post('companyid');
			$data['employeeid'] =  $this->input->post('employeeid');

			$data['results'] = $this->requestmodel->get_quick_conveyance_info($this->input->post('lot_no'), $this->input->post('companyid'), $this->input->post('employeeid'));
			$data['page'] = '/requestView/viewquickConveyanceAccept'; //add page name as a parameter		
		}


		$this->load->view('index', $data);
	}


	function process_Quick_Pay_Conveyance()
	{

		$this->requestmodel->process_quick_conveyance($this->input->post('lot_no'), $this->input->post('companyid'), $this->input->post('employeeid'));


		$data['userlevel'] = $this->session->userdata('authlevel');


		$data['company_list'] = $this->requestmodel->list_company();
		$data['employee_list'] = $this->requestmodel->list_conveyance_employeeid();


		// set common properties
		$data['title'] = 'Quick Conveyance Pay';
		$data['action'] =  site_url('request/request/Quick_Pay_Conveyance');

		$this->_set_fields_quick_conveyance_pay();
		$this->_set_rules_quick_conveyance_pay();
		$data['message'] = '<div class="success" align=left>Conveyance Paid Successful!</div>';

		$data['page'] = '/requestView/quickConveyanceAccept'; //add page name as a parameter
		$this->load->view('index', $data);
	}







	function _set_fields_quick_conveyance_pay()
	{

		$fields['lot_no'] = 'lot_no';
		$fields['companyid'] = 'companyid';
		$fields['employeeid'] = 'employeeid';


		$this->validation->set_fields($fields);
	}

	function _set_rules_quick_conveyance_pay()
	{
		$rules['lot_no'] = 'trim|required';
		$rules['companyid'] = 'trim|required';
		$rules['employeeid'] = 'trim|required';
		$this->validation->set_rules($rules);
		$this->validation->set_message('required', '* required');
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}


	function get_company_by_lot()
	{

		$lot = $this->input->post('data');
		$sql = $this->requestmodel->get_company_by_lot($lot);

		$option = "<option value=''>-&nbsp;-SELECT-&nbsp;-</option>";

		foreach ($sql->result() as $row) {
			$option = $option . "<option value='" . $row->iId . "'>" . $row->vCompany . "</option>";
		}


		echo $option;
	}


	function get_employee_by_lot()
	{

		$lot = $this->input->post('lot');
		$company = $this->input->post('company');
		$sql = $this->requestmodel->get_employee_by_lot($lot, $company);
		$option = "<option value=''>-&nbsp;-SELECT-&nbsp;-</option>";
		foreach ($sql->result() as $row) {
			$option = $option . "<option value='" . $row->EmpId . "'>" . $row->EmpId . "&nbsp;--&nbsp;" . $row->displayname . "</option>";
		}


		echo $option;
	}

	function Search_Audit_Request_Conveyance($msgtyp = 1, $company = 1)
	{
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['company_list'] = $this->requestmodel->list_company();


		if ($this->input->post('company'))
			$company = $this->input->post('company');


		$data['action'] =  site_url('request/request/Search_Audit_Request_Conveyance/1/' . $company);
		if ($this->session->userdata('authlevel') == 7) {
			// offset
			$message = '';
			$uri_segment = 4;
			if ($msgtyp == 2) $message = '<div class="success" align=left>Accepted!</div>';
			if ($msgtyp == 3) $message = '<div class="cancel" align=left>Cancel!</div>';
			//Search 

			// set user message
			$data['message'] = $message;


			// load data
			$request = $this->requestmodel->get_search_req_conveyance_audit_list($company)->result();
			// echo "hello";


			// generate table data
			$this->load->library('table');
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('Employee Id', 'Company', 'Employee Name', 'Journey Date', 'Apply Date', 'Purpose', 'From', 'To', 'Transport Mode', 'Updown', 'Amount', 'Action');

			//$i = 0 + $offset;

			foreach ($request as $row) {
				$link = '';


				$link = '&nbsp;' . anchor('request/request/SV_Conveyance_Accept_Audit/' . $row->SL . '/' . $company, 'accept', array('class' => 'confirm', ''));

				$link = $link . ' ' . '&nbsp;' . anchor('request/request/SV_Conveyance_Cancel_Audit/' . $row->SL . '/' . $company, 'cancel', array('class' => 'cancel', 'onclick' => "return confirm('Are you sure want to cancel?')"));
				$this->table->add_row(
					$row->EmpId,
					$row->vCompany,
					$row->vEmpName,
					$row->journey_date,
					$row->created_date,
					$row->purpose,
					$row->vfrom,
					$row->vto,
					$row->trans_mode,
					$row->updown,
					$row->amount,
					//$row->amount.' '.'&nbsp;'.anchor('request/request/Conveyance_Amount/'.$row->SL,'Edit'),
					$link
				);
			}
			$data['table'] = $this->table->generate();
			$data['page'] = '/requestView/reqSearchConveyanceAuditList'; //add page name as a parameter
			$this->load->view('index', $data);
		}
	}


	function SV_Conveyance_Accept_Audit($id, $company)
	{
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['costcentre'] = $this->requestmodel->list_costcentre();
		$data['area'] = $this->requestmodel->list_area();
		$data['internal_order'] = $this->requestmodel->list_internal_order();
		// set common properties
		$data['title'] = 'Conveyance Bill Accept Form';
		$data['action'] =  site_url('request/request/SV_Conveyance_Accept_Audit/' . $id . '/' . $company);

		$this->_set_fields_conveyance_accept();
		$this->_set_rules_conveyance_accept();

		//$this->validation->EmpId =	$this->session->userdata('username');

		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
		} else {
			// save data
			$request = $this->makeQueryForAcceptConvence();

			//print_r($request);
			$this->requestmodel->saveAcceptConveyance($id, $request);

			$this->requestmodel->save_mis_conveyance_action(array(
				'user_id' => $this->session->userdata('username'),
				'con_id' => $id,
				'user_action' => 'accepted'
			));

			// set user message

			$data['message'] = '<div class="success" align=left>Request Send Successful..</div>';

			//$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);
			redirect('request/request/Search_Audit_Request_Conveyance/2/' . $company);
		}
		$this->validation->lot_no = $this->requestmodel->get_lot_no();
		$data['page'] = '/requestView/reqConveyanceAccept'; //add page name as a parameter
		$this->load->view('index', $data);
	}


	function SV_Conveyance_Cancel_Audit($id, $company)
	{
		if ($this->session->userdata('authlevel') == 7) {

			$this->requestmodel->cancel_conveyance_by_audit($id);

			$this->requestmodel->save_mis_conveyance_action(array(
				'user_id' => $this->session->userdata('username'),
				'con_id' => $id,
				'user_action' => 'canceled'
			));

			redirect('request/request/Search_Audit_Request_Conveyance/3/' . $company);
		}
	}
}
