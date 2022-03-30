<?php
class vendorforreq extends MY_Controller
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
		$this->load->model('/vendorforreqmodel', '', TRUE);
		$this->load->model('/billmodel', '', TRUE);
		$this->load->model('/billsupervisemodel', '', TRUE);
		$this->load->model('/requisitionmodel', '', TRUE);



		if ($this->userauth->is_logged_in() and $this->session->userdata('authlevel') != 5 and $this->session->userdata('authlevel') != 6 and $this->session->userdata('authlevel') != 9) {

			show_401();
		}
	}

	function Index()
	{

		$data = $this->getListData();
		$this->_set_fields();
		$this->validation->bill_date = 	date('d-m-Y');
		$data['title'] = 'Claim Vendor Bill';
		$data['message'] = '';
		$data['openmodel'] = 'no';
		$data['action'] = site_url('bill/vendorforreq/add');
		$data['page'] = '/billView/vendorforreqEdit';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->validation->advance = 0;
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
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
		$fields['ReportingOfficer'] = 'Reporting Officer';

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

		$rules['gr_no'] = 'trim|required';
		$rules['gr_date'] = 'trim|required';

		$rules['bill_date'] = 'trim|required';
		$rules['bill_description'] = 'trim|required';
		$rules['Company'] = 'trim|required';
		$rules['Vendor'] = 'trim|required';
		$rules['advance'] = 'trim|required';
		$rules['retypeamount'] = 'trim|required';
		$rules['amount'] = 'trim|required';
		//$rules['ReportingOfficer'] = 'trim|required';
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
		$data['docs'] = $this->vendorforreqmodel->get_documents($id);
		$this->load->view('billView/viewDocs', $data);
	}


	function add()
	{

		$data = $this->getListData();
		// set common properties
		$data['title'] = 'Claim Vendor Bill';


		$this->_set_fields();
		$this->_set_rules();

		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
			$data['action'] =  site_url('bill/vendorforreq/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		} else if ($this->input->post('ReportingOfficer') == '') {
			$data['message'] = 			'<div class="cancel" align=left>please select reporitng to..</div>';
			$data['action'] =  site_url('bill/vendorforreq/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		} else if ($this->input->post('payment_type') == 'Cheque' and $this->input->post('suggested_cheque') == '') {
			$data['message'] = 			'<div class="cancel" align=left>please input Cheque Name..</div>';
			$data['action'] =  site_url('bill/vendorforreq/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		} else {
			// save data	

			$this->vendorforreqmodel->save($this->makeQueryinsert());

			$data['openmodel'] = 		'yes';
			$billId = 				$this->vendorforreqmodel->get_last_entry_id($this->mkDate($this->input->post('bill_date')));

			$data['action'] =  			site_url('bill/vendorforreq/updatebill/' . $billId);
			$data['itemcount'] = 		$this->vendorforreqmodel->tot_document_by_bill_id($billId);
			$data['action2'] =  site_url('bill/vendorforreq/addDoc/' . $billId);
			$data['action3'] =  site_url('bill/bill/submitSupervisor/' . $billId);
			$data['action4'] =  site_url('bill/vendorforreq/addParticular/' . $billId);
			$data['action5'] =  site_url('bill/vendorforreq/addItem/' . $billId);

			$items = $this->billmodel->get_documents($billId)->result();




			$data['table'] =			$this->documten_table($items, $billId);


			$data['message'] = 			'<div class="success" align=left>save successful..</div>';
		}

		$data['page'] = '/billView/vendorforreqEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
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
			'amount' => $this->input->post('amount'),
			'advance' => $this->input->post('advance'),
			'payment_type' => $this->input->post('payment_type'),
			'suggested_cheque' => $this->input->post('suggested_cheque'),
			'supervise_by' => $this->input->post('ReportingOfficer'),
			'flow_type' => $this->input->post('flow_type'),
			'loc' => $this->input->post('loc')

		);
	}


	function makeSuperviseQuery()
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
			'amount' => $this->input->post('amount'),
			'advance' => $this->input->post('advance'),
			'payment_type' => $this->input->post('payment_type'),
			'suggested_cheque' => $this->input->post('suggested_cheque'),

			'flow_type' => $this->input->post('flow_type'),
			'payment_type' => $this->input->post('payment_type'),
			'loc' => $this->input->post('loc')


		);
	}


	function makeAuditQuery()
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
			'flow_type' => $this->input->post('flow_type'),
			'audit_by' => $this->session->userdata('username'),
			'loc' => $this->input->post('loc')
		);
	}





	function makeQueryinsert()
	{
		//$id=$this->vendorforreqmodel->id();
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
			'flow_type' => $this->input->post('flow_type'),
			'payment_type' => $this->input->post('payment_type'),
			'supervise_by' => $this->input->post('ReportingOfficer'),
			'suggested_cheque' => $this->input->post('suggested_cheque'),
			'requisition_status' => 1,
			'created_by' => $this->session->userdata('username'),
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

		$data['list_requisition'] = $this->vendorforreqmodel->list_requisition();
		$data['company'] = $this->vendorforreqmodel->list_company();

		$data['vendor'] = $this->vendorforreqmodel->list_vendor();
		$data['costcentre'] = $this->vendorforreqmodel->list_costcentre();

		$data['reportingOfficerList'] = $this->vendorforreqmodel->list_head();
		return $data;
	}


	function documten_table($items, $doc_id)
	{
		$table = '<table>';

		//$i = 0 + $offset;
		$sl = 1;

		foreach ($items as $row) {

			$table = $table . '<tr>';
			$del = '&nbsp;' . anchor('bill/vendorforreq/deleteDoc/' . $row->id . '/' . $row->doc_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')")) . "&nbsp;&nbsp;<b>Category:</b>&nbsp;" . $row->doc_category;
			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			//$view='<a href="ftp://billmaster:stargold@192.168.1.117/Common_share/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$view = '<a href="' . base_url() . 'index.php/bill/bill/url/' . $row->doc_file . '" target="about_blank" class="view">view</a>';

			$doc_file = $row->doc_file;

			$table = $table . '<td>' . $sl . '</td>' . '<td>' . $doc_file . '</td>' . '<td>' . $view . '&nbsp;' . $del . '</td>';
			$sl = $sl + 1;
			$table = $table . '</tr>';
		}

		$get_map_by_id = $this->vendorforreqmodel->get_map_by_id($doc_id);


		foreach ($get_map_by_id as $row2) {
			$table = $table . '<tr>';
			$del = '&nbsp;' . anchor('bill/vendorforreq/deleteItem/' . $row2->id . '/' . $row2->doc_id . '/' . $row2->req_id . '/' . $row2->bill_by, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')")) . '&nbsp;&nbsp;<b>Category:</b>&nbsp;Requisition';
			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));

			$view = anchor('reports/reports/vendor_material_list/' . $row2->doc_id . '/' . $row2->req_id . '/' . $row2->bill_by, 'view', array('class' => 'view', 'target' => 'about_blank'));


			$table = $table . '<td>' . $sl . '</td><td>Requisition ## ' . $row2->req_id . '</td>' . '<td>' . $view . '&nbsp;' . $del . '</td>';
			$sl = $sl + 1;
			$table = $table . '</tr>';
		}





		$table = $table . '</table>';
		return $table;
	}




	function updatebill($id, $filechk = 1)
	{

		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();

		$rows = $this->vendorforreqmodel->get_by_id($id)->row();
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

		$data = $this->getListData();

		$data['openmodel'] = 		'yes';
		$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->vendorforreqmodel->tot_document_by_bill_id($id);
		$items = $this->vendorforreqmodel->get_documents($id)->result();

		$data['table'] =			$this->documten_table($items, $id);

		// run validation
		if ($this->validation->run() == FALSE) {

			if ($filechk == 2)
				$data['message'] = '<div class="cancel" align="left">File Not Found..</div>';
			else if ($filechk == 3)
				$data['message'] = '<div class="cancel" align="left">FTP Connection Fail..</div>';
			else if ($filechk == 4)
				$data['message'] = '<div class="cancel" align="left">FTP Login Fail..</div>';
			else if ($filechk == 5)
				$data['message'] = '<div class="cancel" align="left">Please Select Document Category..</div>';
			else if ($filechk == 6)
				$data['message'] = '<div class="cancel" align="left">Please Select Particual..</div>';
			else if ($filechk == 7)
				$data['message'] = '<div class="cancel" align="left">Please Complete All Particual fields..</div>';
			else if ($filechk == 11)
				$data['message'] = '<div class="cancel" align="left">This file is already saved in the system..</div>';
			else if ($filechk == 21)
				$data['message'] = '<div class="cancel" align="left">Requisition not found or Already exist in another bill..</div>';
			else if ($filechk == 31)
				$data['message'] = '<div class="cancel" align="left">Incorrect Order.Please check New Order before add.</div>';
			else if ($filechk == 32)
				$data['message'] = '<div class="cancel" align="left">You did not chose any item! All check box found empty!</div>';
			else $data['message'] = '';
			// load view 



		} else if ($this->input->post('ReportingOfficer') == '' and $rows->step_status == 1) {
			$data['message'] = 			'<div class="cancel" align=left>please select reporitng to..</div>';
		} else if ($this->input->post('payment_type') == 'Cheque' and $this->input->post('suggested_cheque') == '') {
			$data['message'] = 			'<div class="cancel" align=left>please input Cheque Name..</div>';
		} else {
			// save data

			if ($rows->step_status == 1) $this->vendorforreqmodel->update($id, $this->makeQuery());
			else if ($rows->step_status == 2) $this->vendorforreqmodel->update($id, $this->makeSuperviseQuery());
			else if ($rows->step_status == 3) $this->vendorforreqmodel->update($id, $this->makeAuditQuery());



			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}

		$data['list_particular'] = $this->vendorforreqmodel->list_particular($id);
		$data['title'] = 'Claim Vendor Bill';
		$data['action'] = site_url('bill/vendorforreq/updatebill/' . $id);
		$data['action2'] =  site_url('bill/vendorforreq/addDoc/' . $id);
		$data['action5'] =  site_url('bill/vendorforreq/addItem/' . $id);
		$data['action3'] =  site_url('bill/bill/submitSupervisor/' . $id);






		$data['action4'] =  site_url('bill/vendorforreq/addParticular/' . $id);
		$data['page'] = '/billView/vendorforreqEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;



		if ($rows->step_status == 2) {
			$data['reportingOfficerList2'] = $this->billsupervisemodel->list_head($this->validation->amount, $this->validation->Company);
			$data['usercode'] = $this->billsupervisemodel->user_code();
			$data['action3'] =  site_url('bill/supervisebill/FinalSubmit/' . $id);
			$data['entry'] = 0;
			$data['recommend'] = 1;


			$dep_code = $this->billsupervisemodel->dep_code_by_id($this->session->userdata('username'));


			if (in_array($this->validation->Company, array(7))) {

				if ($this->session->userdata('username') != 2405 and $this->session->userdata('username') != "003") {

					$data['usercode'] = 4;
				}


				if ($dep_code == 0)
					$data['reportingOfficerList2'] = $this->billsupervisemodel->list_dep_head();
				else if ($dep_code == 1 and $this->session->userdata('username') != 2405 and $this->session->userdata('username') != "003") {
					$data['reportingOfficerList2'] = $this->billsupervisemodel->list_fin_head();
					$data['usercode'] = 4;
				}
			}




			if (in_array($this->validation->Company, array(2, 8, 9))) {

				if ($this->session->userdata('username') != 2346 and $this->session->userdata('username') != "003") {

					$data['usercode'] = 4;
				}


				if ($dep_code == 0)
					$data['reportingOfficerList2'] = $this->billsupervisemodel->list_dep_head();
				else if ($dep_code == 1 and $this->session->userdata('username') != 2346 and $this->session->userdata('username') != "003") {

					//echo "hello2";

					if ($this->validation->amount > 10000)
						$data['reportingOfficerList2'] = $this->billsupervisemodel->list_fin_head_second();
					else  $data['reportingOfficerList2'] = $this->billsupervisemodel->list_sel_audit();
					$data['usercode'] = 4;
				}
			}

			if (in_array($this->validation->Company, array(1, 3))) {

				if ($this->session->userdata('username') != "003") {

					$data['usercode'] = 4;
				}


				if ($dep_code == 0)
					$data['reportingOfficerList2'] = $this->billsupervisemodel->list_dep_head();
				else if (
					$dep_code == 1 and $this->session->userdata('username') != 2346

					and $this->session->userdata('username') != 1970
					and $this->session->userdata('username') != "003"
				) {

					//echo "hello2";

					/* 	if($this->validation->amount>10000)			
				$data['reportingOfficerList2']=$this->billsupervisemodel->list_fin_head_second();
				else  $data['reportingOfficerList2']=$this->billsupervisemodel->list_sel_audit();
				$data['usercode']=4; */

					$data['reportingOfficerList2'] = $this->billsupervisemodel->list_sel_audit();
				} else if ($rows->finance_head_by != 2346 and $this->session->userdata('username') == 2346) {
					$data['reportingOfficerList2'] = $this->billsupervisemodel->list_sel_audit();
				} else if ($rows->finance_head_by == 2346 and $this->session->userdata('username') == 2346) {


					if ($this->validation->amount > 50000  and  $rows->supervise_by != 1970)
						$data['reportingOfficerList2'] = $this->billsupervisemodel->list_coo_head();
					else $data['reportingOfficerList2'] = $this->billsupervisemodel->list_sel_account();
				} else if ($rows->ceo_by != 1970 and $this->session->userdata('username') == 1970) {
					$data['reportingOfficerList2'] = $this->billsupervisemodel->list_sel_audit();
				} else if ($rows->ceo_by == 1970 and $this->session->userdata('username') == 1970) {
					$data['reportingOfficerList2'] = $this->billsupervisemodel->list_sel_account();
					if ($rows->audit_approved_date == null or $rows->audit_approved_date == "")
						$data['reportingOfficerList2'] = $this->billsupervisemodel->list_sel_audit();
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


		if ($rows->step_status == 3) {

			$data['usercode'] = 'audit';
			if (in_array($this->validation->Company, array(1, 3))) {
				$data['usercode'] = 4;
				$data['reportingOfficerList2'] = $this->billsupervisemodel->list_audit_options($this->validation->amount);
			}
			$data['action3'] =  site_url('bill/auditbill/FinalSubmit/' . $id);
			$data['entry'] = 0;
			$data['recommend'] = 1;
		}


		$this->load->view('index', $data);
	}

	function addDoc($id)
	{

		if (strlen($this->input->post('doc_category')) < 2)
			redirect('bill/vendorforreq/updatebill/' . $id . '/5', 'location');


		$ftp_server = "192.168.1.117";
		$conn_id = ftp_connect($ftp_server)
			or redirect('bill/vendorforreq/updatebill/' . $id . '/3', 'location');

		$login_result = ftp_login($conn_id, "bill", "bill007");
		if ((!$conn_id) || (!$login_result))
			redirect('bill/vendorforreq/updatebill/' . $id . '/4', 'location');




		$str = str_replace('\\', '/', $this->input->post('doc_file'));
		$str = trim($str, " ");
		$doubleFileChk = $this->vendorforreqmodel->doubleFileChk($str);
		if ($doubleFileChk > 0)
			redirect('bill/vendorforreq/updatebill/' . $id . '/11', 'location');

		$pizza  = $str;
		$pieces = explode("/", $pizza);
		$piecescount = count($pieces);

		if ($piecescount != 2) {
			ftp_close($conn_id);
			redirect('bill/vendorforreq/updatebill/' . $id . '/2', 'location');
		}


		$path = "/BILL/" . $pieces[0] . "/";
		ftp_pasv($conn_id, true);
		$contents_on_server = ftp_nlist($conn_id, $path);
		ftp_close($conn_id);


		if (in_array($pieces[1], $contents_on_server) and $this->input->post('doc_file') != "") {

			$this->vendorforreqmodel->saveDoc(
				array(
					'doc_file' => $str,
					'doc_category' => $this->input->post('doc_category'),
					'doc_id' => $id
				)
			);
			redirect('bill/vendorforreq/updatebill/' . $id . '/1', 'location');
		} else redirect('bill/vendorforreq/updatebill/' . $id . '/2', 'location');
	}



	function addItem($id)
	{

		if ($this->input->post('master') != "") {


			$total = $this->input->post('total');



			$username = $this->vendorforreqmodel->get_by_id($id)->row()->created_by;


			$chkreqsum = 0;
			for ($x = 1; $x < $total; $x++) {

				if ($this->input->post('itm' . $x) != '')
					$chkreqsum = 1;
			}


			if ($chkreqsum == 0)
				redirect('bill/vendorforreq/updatebill/' . $id . '/32', 'location');


			for ($x = 1; $x < $total; $x++) {


				$chkqty = $this->input->post('minqty' . $x) + $this->input->post('vqty' . $x);
				if ($this->input->post('itm' . $x) != '' and $chkqty > $this->input->post('reqqty' . $x))
					redirect('bill/vendorforreq/updatebill/' . $id . '/31', 'location');
			}
			for ($x = 1; $x < $total; $x++) {
				if ($this->input->post('itm' . $x) != '') {
					$this->vendorforreqmodel->saveOrder(
						array(
							'master_id' => $this->input->post('master'),
							'item_id' => $this->input->post('itm' . $x),
							'qty' => $this->input->post('minqty' . $x),
							'doc_id' => $id,
							'bill_by' => $username
						)
					);
				}

				$chkqty = $this->input->post('minqty' . $x) + $this->input->post('vqty' . $x);
				if ($this->input->post('itm' . $x) != '' and $chkqty == $this->input->post('reqqty' . $x))
					$this->vendorforreqmodel->bill_item_complete($this->input->post('itm' . $x));
				$this->vendorforreqmodel->bill_master_complete($this->input->post('master'));
				//echo $this->input->post('itm'.$x).' '.$this->input->post('vqty'.$x).' '.$this->input->post('minqty'.$x).'<br/>';

			}
			$this->vendorforreqmodel->saveMap(
				array(
					'doc_id' => $id,
					'req_id' => $this->input->post('master'),
					'bill_by' => $username
				)
			);
			redirect('bill/vendorforreq/updatebill/' . $id . '/1', 'location');
		} else redirect('bill/vendorforreq/updatebill/' . $id . '/6', 'location');
	}


	function updateParticular($id1, $id2)
	{

		if ($this->input->post('particular') != "" and $this->input->post('particular_date' . $id2) != ""  and $this->input->post('amount') != "") {
			$this->vendorforreqmodel->updateParticular(
				$id2,
				array(
					'particular_date' => $this->mkDate($this->input->post('particular_date' . $id2)),
					'particular' => $this->input->post('particular'),
					'total' => $this->input->post('amount')
				),
				$this->input->post('amount')
			);
			$this->vendorforreqmodel->updateSumParticular($id1);
			redirect('bill/vendorforreq/updatebill/' . $id1 . '/1', 'location');
		} else redirect('bill/vendorforreq/updatebill/' . $id1 . '/7', 'location');
	}



	function deleteDoc($id1, $id2)
	{
		$this->vendorforreqmodel->deleteDoc($id1);


		redirect('bill/vendorforreq/updatebill/' . $id2, 'location');
	}

	function deleteItem($id1, $id2, $id4, $id5)
	{
		$this->vendorforreqmodel->deleteItem($id1, $id2, $id4, $id5);
		redirect('bill/vendorforreq/updatebill/' . $id2, 'location');
	}




	function deleteRequisition($id1, $id2)
	{
		$this->vendorforreqmodel->requisitionDelete($id1);


		redirect('bill/vendorforreq/updatebill/' . $id2, 'location');
	}


	function deleteParticular($id1, $id2)
	{
		$this->vendorforreqmodel->deleteParticular($id1);
		$this->vendorforreqmodel->updateSumParticular($id2);
		redirect('bill/vendorforreq/updatebill/' . $id2, 'location');
	}




	function get_item_by_req()
	{

		$option = "<table><tr><td><b>Item</b></td><td><b>Required</b></td><td><b>Already Order</b></td><td><b>New Order</b></td></tr>";

		$req = $this->input->post('data');
		$items = $this->vendorforreqmodel->get_item_by_req($req);
		$x = 1;
		$master_id = 0;
		foreach ($items as $row) {

			$minqty = $row->qty_req - $row->qty;
			$option = $option . "<tr>
			<td>" . $row->item_name . "<input type='checkbox' name='itm" . $x . "' value='" . $row->id . "'/></td>
			<td><input type='text' size='2' readonly name='reqqty" . $x . "' value='" . $row->qty_req . "'/></td>
			<td><input type='text' size='2' readonly name='vqty" . $x . "' value='" . $row->qty . "'/></td>
			<td><input type='text' size='2' name='minqty" . $x . "' value='" . $minqty . "'/></td>
			</tr>";
			$x = $x + 1;
			$master_id = $row->master_id;
		}

		echo $option . "</table><input type='hidden' name='total' value='" . $x . "' /><input type='hidden' name='master' value='" . $master_id . "' />";
	}
}
