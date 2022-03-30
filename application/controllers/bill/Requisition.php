<?php
class Requisition extends MY_Controller
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
		$this->load->model('/requisitionmodel', '', TRUE);
		$this->load->model('/reqsupmodel', '', TRUE);
		$this->load->model('/billmodel', '', TRUE);
		if ($this->userauth->is_logged_in() and $this->session->userdata('authlevel') != 5 and $this->session->userdata('authlevel') != 6) {

			show_401();
		}
	}

	function Index()
	{

		$data = $this->getListData();
		$this->_set_fields();
		$data['title'] = 'Claim Material Requisition';
		$data['message'] = '';
		$data['openmodel'] = 'no';
		$data['action'] = site_url('bill/requisition/add');
		$data['page'] = '/billView/requisitionEdit';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
	}

	function Index2()
	{

		$data = $this->getListData();
		$this->_set_fields();
		$data['title'] = 'Claim Material Requisition';
		$data['message'] = '';
		$data['openmodel'] = 'no';
		$data['action'] = site_url('bill/requisition/add2');
		$data['page'] = '/billView2/requisitionEdit';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
	}

	function _set_fields()
	{

		$fields['loc'] = 'Location';
		$fields['Company'] = 'Company';
		$fields['ReportingOfficer'] = 'Reporting Officer';

		$this->validation->set_fields($fields);
	}

	function _set_rules()
	{
		$rules['loc'] = 'trim|required';
		$rules['Company'] = 'trim|required';
		$rules['ReportingOfficer'] = 'trim|required';

		$this->validation->set_rules($rules);
		$this->validation->set_message('required', '* required');
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}


	function add()
	{

		$data = $this->getListData();
		// set common properties
		$data['title'] = 'Claim Material Requisition';


		$this->_set_fields();
		$this->_set_rules();

		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
			$data['action'] =  site_url('bill/requisition/add');
			$data['openmodel'] = 'no';
		} else {
			// save data	

			$this->requisitionmodel->save($this->makeQueryinsert());

			$data['openmodel'] = 		'yes';
			$reqId = 				$this->requisitionmodel->get_last_entry_id();



			$data['IT']					= $this->requisitionmodel->get_boss_id("IT", 0);
			$data['Director']			= $this->requisitionmodel->get_boss_id("Director", 0);
			$data['HR']					= $this->requisitionmodel->get_boss_id("HR", 0);
			$data['Proc1']				= $this->requisitionmodel->get_boss_id("Proc", 1);
			$data['Proc2']				= $this->requisitionmodel->get_boss_id("Proc", 2);



			$data['action'] =  			site_url('bill/requisition/updateReq/' . $reqId);
			$data['itemcount'] = 		0;
			$data['action2'] =  site_url('bill/requisition/addItem/' . $reqId);
			$data['action3'] =  site_url('bill/requisition/FinalSubmit/' . $reqId);

			$data['message'] = 			'<div class="success" align=left>save successful..</div>';
		}

		$data['page'] = '/billView/requisitionEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
	}

	function add2()
	{

		$data = $this->getListData();
		// set common properties
		$data['title'] = 'Claim Material Requisition';


		$this->_set_fields();
		$this->_set_rules();

		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
			$data['action'] =  site_url('bill/requisition/add2');
			$data['openmodel'] = 'no';
		} else {
			// save data	

			$this->requisitionmodel->save($this->makeQueryinsert());

			$data['openmodel'] = 		'yes';
			$reqId = 				$this->requisitionmodel->get_last_entry_id();



			$data['IT']					= $this->requisitionmodel->get_boss_id("IT", 0);
			$data['Director']			= $this->requisitionmodel->get_boss_id("Director", 0);
			$data['HR']					= $this->requisitionmodel->get_boss_id("HR", 0);
			$data['Proc1']				= $this->requisitionmodel->get_boss_id("Proc", 1);
			$data['Proc2']				= $this->requisitionmodel->get_boss_id("Proc", 2);



			$data['action'] =  			site_url('bill/requisition/updateReq2/' . $reqId);
			$data['itemcount'] = 		0;
			$data['action2'] =  site_url('bill/requisition/addItem2/' . $reqId);
			$data['action3'] =  site_url('bill/requisition/FinalSubmit2/' . $reqId);

			$data['message'] = 			'<div class="success" align=left>save successful..</div>';
		}

		$data['page'] = '/billView2/requisitionEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
	}

	function makeQuery()
	{
		return $member = array(
			'company' => $this->input->post('Company'),
			'req_loc' => $this->input->post('loc'),
			'approve_by' => $this->input->post('ReportingOfficer'),
			'hold_by' => $this->input->post('ReportingOfficer')

		);
	}



	function makeQueryinsert()
	{
		//$id=$this->requisitionmodel->id();
		return $member = array(
			'company' => $this->input->post('Company'),
			'req_loc' => $this->input->post('loc'),
			'approve_by' => $this->input->post('ReportingOfficer'),
			'hold_by' => $this->input->post('ReportingOfficer'),
			'request_by' => $this->session->userdata('username')

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
		$data['company'] = $this->requisitionmodel->list_company();
		$data['list_material'] = $this->requisitionmodel->list_material();
		$data['reportingOfficerList'] = $this->requisitionmodel->list_head();
		return $data;
	}


	function searchRequisition()
	{

		//Search 
		$data['action'] = site_url('bill/requisition/searchRequisition');;
		$data['action2'] = site_url('bill/requisition/searchRequisitionID');
		$data['title'] = "bill List";
		// set user message
		$data['message'] = '';

		// load data
		$requisition = $this->requisitionmodel->get_search_by_id($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')));

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		//$data['totalrecord'] = $this->requisitionmodel->count_search($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')));
		$data['totalrecord'] = $requisition->num_rows();
		$data['table'] = $this->requisition_table($requisition->result());
		$data['page'] = '/billView/requisitionList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}


	function searchRequisitionID()
	{

		//Search 
		$data['action'] = site_url('bill/requisition/searchRequisition');;
		$data['action2'] = site_url('bill/requisition/searchRequisitionID');
		$data['title'] = "bill List";
		// set user message
		$data['message'] = '';

		// load data
		$requisition = $this->requisitionmodel->get_search_by_material_id($this->input->post('matID'));

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		//$data['totalrecord'] = $this->requisitionmodel->count_search($this->mkDate($this->input->post('from')),$this->mkDate($this->input->post('to')));
		$data['totalrecord'] = $requisition->num_rows();
		$data['table'] = $this->requisition_table($requisition->result());
		$data['page'] = '/billView/requisitionList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}


	function requisitionList($message = '', $offset = 0, $MID = 0)
	{
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		$data['action'] = site_url('bill/requisition/searchRequisition');
		$data['action2'] = site_url('bill/requisition/searchRequisitionID');
		$data['title'] = "Material Requisition List";
		// set user message
		$data['message'] = $message;
		if ($message == 'submit')
			$data['message'] = "<div class='success' align=left>Submitted Successful..!! Your Material ID: <font color='red'><b>" . $MID . "</b></font> </div>";
		else if ($message == 'purchased')
			$data['message'] = "<div class='success' align=left>Purchased Completed..!!</div>";
		else if ($message == 'return')
			$data['message'] = "<div class='success' align=left>Return Successful..!!</div>";


		// load data
		$requisition = $this->requisitionmodel->get_paged_list($this->limit, $offset)->result();

		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/requisition/requisitionList/');
		$number_of_rows = $this->requisitionmodel->count_all();



		$config['total_rows'] = $number_of_rows;
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;


		$data['table'] = $this->requisition_table($requisition);
		$data['page'] = '/billView/requisitionList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}






	function requisition_table($requisition)
	{

		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('ID', 'request&nbsp;by', 'request&nbsp;date', 'Requested&nbsp;to', 'approved&nbsp;date', 'assigned&nbsp;person', 'Procurement&nbsp;Approved', 'Complete&nbsp;Status', 'Purchase&nbsp;Status', 'Cancel&nbsp;Status', 'hold&nbsp;by', 'action');

		$status = "";

		$procurement_pass = "No";

		//$i = 0 + $offset;
		$sl = 1;
		foreach ($requisition as $row) {

			if ($row->submit_status == 0 and $row->request_by == $this->session->userdata('username')) $view = anchor('bill/requisition/updateReq/' . $row->id, 'update&nbsp;', array('class' => 'update'));
			else $view = "";

			if ($row->cancel_status == 0)
				$cancel_status = "No";
			else $cancel_status = "Canceled";

			if ($row->purchase_staus == 0 and $row->bill_status == 0)
				$purchase_staus = "Not Yet";
			else $purchase_staus = "Purchased&nbsp;done<br/>Purchased&nbsp;date:" . $row->purchase_date . "<br/>Comments:" . $row->purchase_comment;

			if ($row->assigned_person == $this->session->userdata('username') && $row->purchase_staus == 0) {
				$purchased = anchor('bill/requisition/purchased/' . $row->id, 'Purchase&nbsp;Complete&nbsp;', array('class' => 'update'));
				$purchased = $purchased . '<br/>' . anchor('bill/requisition/makereturn/' . $row->id, 'return', array('class' => 'update'));
			} else $purchased = '';

			$report = anchor('reports/reports/view_material_list/' . $row->id, 'view', array('class' => 'view', 'target' => 'about_blank'));

			if ($row->bill_status == 0) $complete_status = "No";
			else $complete_status = "Yes";
			if ($row->procurement_pass == 0) $procurement_pass = "No";
			else $procurement_pass = "Yes";

			$hold = 'no';

			if ($row->procurement_pass == 0 and $row->submit_status == 1)
				$hold = $this->billmodel->get_holdby($row->hold_by);


			$wcolor = "#ff8e29";
			$now = time(); // or your date as well
			$your_date = strtotime($row->request_date);
			$datediff = $now - $your_date;

			$days = round($datediff / (60 * 60 * 24));

			if ($row->purchase_staus == 0 and $days >= 7) {
				$wcolor = "#ff0000";
				$complete_status = "<font color='#ff0000'><b>No</b></font>";
			}
			if ($row->purchase_staus == 1 or $row->bill_status == 1)
				$wcolor = "#0ac116";




			$this->table->add_row(

				$row->id,
				$row->request_by,
				$row->request_date,
				$row->approveName,
				$row->approved_date,
				$row->assignName,
				$procurement_pass,
				$complete_status,
				"<font color='$wcolor'><b>" . $purchase_staus . "</b></font>",
				$cancel_status,
				$hold,
				$report . '<br/>' . $purchased . '<br/>' . $view
			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}




	function updateReq($id, $filechk = 1)
	{

		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();

		$rows = $this->requisitionmodel->get_by_id($id)->row();

		$this->validation->Company = 						$rows->company;
		$this->validation->loc = 							$rows->req_loc;

		$this->validation->ReportingOfficer = 				$rows->approve_by;

		$data = $this->getListData();

		$data['openmodel'] = 		'yes';
		//$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->requisitionmodel->tot_item_by_id($id);
		$items = $this->requisitionmodel->get_items($id)->result();
		$data['table'] =			$this->item_table($items);

		// run validation
		if ($this->validation->run() == FALSE) {

			if ($filechk == 7)
				$data['message'] = '<div class="cancel" align="left">Please Complete Item name and Quantity Required and Contact Person Details..</div>';
			else if ($filechk == 8)
				$data['message'] = '<div class="cancel" align="left">Please click on add first..</div>';
			else $data['message'] = '';
			// load view 



		} else {
			// save data

			$this->requisitionmodel->update($id, $this->makeQuery());

			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}



		$data['IT']					= $this->requisitionmodel->get_boss_id("IT", 0);
		$data['Director']			= $this->requisitionmodel->get_boss_id("Director", 0);
		$data['HR']					= $this->requisitionmodel->get_boss_id("HR", 0);
		$data['Proc1']				= $this->requisitionmodel->get_boss_id("Proc", 1);
		$data['Proc2']				= $this->requisitionmodel->get_boss_id("Proc", 2);
		$data['AzneoFinance']		= $this->requisitionmodel->get_boss_id("AzneoFinance", 0);

		$data['title'] = 'Claim Material Requisition';
		$data['action'] = site_url('bill/requisition/updateReq/' . $id);
		$data['action2'] =  site_url('bill/requisition/addItem/' . $id);
		$data['action3'] =  site_url('bill/requisition/FinalSubmit/' . $id);
		$data['page'] = '/billView/requisitionEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
	}

	function updateReq2($id, $filechk = 1)
	{

		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();

		$rows = $this->requisitionmodel->get_by_id($id)->row();

		$this->validation->Company = 						$rows->company;
		$this->validation->loc = 							$rows->req_loc;

		$this->validation->ReportingOfficer = 				$rows->approve_by;

		$data = $this->getListData();

		$data['openmodel'] = 		'yes';
		//$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->requisitionmodel->tot_item_by_id($id);
		$items = $this->requisitionmodel->get_items($id)->result();
		$data['table'] =			$this->item_table($items);

		// run validation
		if ($this->validation->run() == FALSE) {

			if ($filechk == 7)
				$data['message'] = '<div class="cancel" align="left">Please Complete Item name and Quantity Required and Contact Person Details..</div>';
			else if ($filechk == 8)
				$data['message'] = '<div class="cancel" align="left">Please click on add first..</div>';
			else $data['message'] = '';
			// load view 



		} else {
			// save data

			$this->requisitionmodel->update($id, $this->makeQuery());

			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}



		$data['IT']					= $this->requisitionmodel->get_boss_id("IT", 0);
		$data['Director']			= $this->requisitionmodel->get_boss_id("Director", 0);
		$data['HR']					= $this->requisitionmodel->get_boss_id("HR", 0);
		$data['Proc1']				= $this->requisitionmodel->get_boss_id("Proc", 1);
		$data['Proc2']				= $this->requisitionmodel->get_boss_id("Proc", 2);
		$data['AzneoFinance']		= $this->requisitionmodel->get_boss_id("AzneoFinance", 0);

		$data['title'] = 'Claim Material Requisition';
		$data['action'] = site_url('bill/requisition/updateReq2/' . $id);
		$data['action2'] =  site_url('bill/requisition/addItem2/' . $id);
		$data['action3'] =  site_url('bill/requisition/FinalSubmit/' . $id);
		$data['page'] = '/billView2/requisitionEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
	}


	function item_table($items)
	{
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('SL', 'Items&nbsp;');

		//$i = 0 + $offset;
		$sl = 1;
		foreach ($items as $row) {

			$del = '&nbsp;' . anchor('bill/requisition/deleteItem/' . $row->id . '/' . $row->master_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));

			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			$ac = site_url('bill/requisition/updateItem/' . $row->master_id . '/' . $row->id);
			$this->table->add_row(

				$sl,
				'<form method="post" action="' . $ac . '"><table style="font: 0.90em arial;width:690;"><tr><td>Item&nbsp;Name&nbsp;<br/><input readonly type="text" name="item_name" class="text" value="' . $row->item_name . '"/></td>' .
					'<td>Specs&nbsp;<br/><input type="text" name="req_sap_id" class="text" value="' . $row->req_sap_id . '"/></td>' .
					'<td>
				<table><tr><td>Delivery&nbsp;Date&nbsp;<br/><input type="text" name="delivery_date' . $row->id . '" onclick="displayDatePicker(\'delivery_date' . $row->id . '\');" value="' . date('d-m-Y', strtotime($row->delivery_date)) . '" class="text"/></td>
				<td><a href="javascript:void(0);" onclick="displayDatePicker(\'delivery_date' . $row->id . '\');"><img src="' . base_url() . 'style/images/calendar.png" alt="calendar" border="0"></a></td></tr></table>
				</td>' .
					'<td>Quantity&nbsp;in&nbsp;hand&nbsp;<br/><input size=3  type="text" name="qty_hand" class="text" value="' . $row->qty_hand . '"/></td>
				<td>Quantity&nbsp;Required&nbsp;<br/><input size=3  type="text" name="qty_req" class="text" value="' . $row->qty_req . '"/></td>
				<td>Unit&nbsp;<br/><input size=10  type="text" name="item_unit" class="text" value="' . $row->item_unit . '"/></td>
				<td>Contact&nbsp;Person&nbsp;Details&nbsp;<br/><textarea rows=3  name="contact_details"  cols=20>' . $row->contact_details . '</textarea></td>
				<td>Reason&nbsp;For&nbsp;Request&nbsp;<br/><textarea rows=3  name="reason_req"  cols=20>' . $row->reason_req . '</textarea></td>
				<td>Remark&nbsp;<br/><textarea rows=3  name="remarks"  cols=20>' . $row->remarks . '</textarea></td>
				<td><input type="submit" value="update"/><br/><br/>' . $del . '</td></tr></table></form>'
			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}

	function addItem($id)
	{

		if ($this->input->post('item_name') != "" and $this->input->post('qty_req') != "" and $this->input->post('qty_req') > 0  and $this->input->post('contact_details') != "") {

			$this->requisitionmodel->saveItem(
				array(
					'delivery_date' => $this->mkDate($this->input->post('delivery_date')),
					'item_name' => $this->input->post('item_name'),
					'req_sap_id' => $this->input->post('req_sap_id'),
					'qty_hand' => $this->input->post('qty_hand'),
					'qty_req' => $this->input->post('qty_req'),
					'item_unit' => $this->input->post('item_unit'),
					'contact_details' => $this->input->post('contact_details'),
					'reason_req' => $this->input->post('reason_req'),
					'remarks' => $this->input->post('remarks'),
					'master_id' => $id
				)
			);

			redirect('bill/requisition/updateReq/' . $id . '/1', 'location');
		} else redirect('bill/requisition/updateReq/' . $id . '/7', 'location');
	}

	function addItem2($id)
	{

		if ($this->input->post('item_name') != "" and $this->input->post('qty_req') != "" and $this->input->post('qty_req') > 0  and $this->input->post('contact_details') != "") {

			$this->requisitionmodel->saveItem(
				array(
					'delivery_date' => $this->mkDate($this->input->post('delivery_date')),
					'item_name' => $this->input->post('item_name'),
					'req_sap_id' => $this->input->post('req_sap_id'),
					'qty_hand' => $this->input->post('qty_hand'),
					'qty_req' => $this->input->post('qty_req'),
					'item_unit' => $this->input->post('item_unit'),
					'contact_details' => $this->input->post('contact_details'),
					'reason_req' => $this->input->post('reason_req'),
					'remarks' => $this->input->post('remarks'),
					'master_id' => $id
				)
			);

			redirect('bill/requisition/updateReq2/' . $id . '/1', 'location');
		} else redirect('bill/requisition/updateReq2/' . $id . '/7', 'location');
	}


	function deleteItem($id1, $id2)
	{
		$this->requisitionmodel->deleteItem($id1);
		redirect('bill/requisition/updateReq/' . $id2, 'location');
	}


	function updateItem($id1, $id2)
	{

		if ($this->input->post('item_name') != "" and $this->input->post('qty_hand') != ""  and $this->input->post('qty_req') != "" and $this->input->post('qty_req') > 0  and $this->input->post('delivery_date' . $id2) != "") {
			$this->requisitionmodel->updateItem(
				$id2,
				array(
					'delivery_date' => $this->mkDate($this->input->post('delivery_date' . $id2)),
					'item_name' => $this->input->post('item_name'),
					'req_sap_id' => $this->input->post('req_sap_id'),
					'qty_hand' => $this->input->post('qty_hand'),
					'qty_req' => $this->input->post('qty_req'),
					'item_unit' => $this->input->post('item_unit'),
					'contact_details' => $this->input->post('contact_details'),
					'reason_req' => $this->input->post('reason_req'),
					'remarks' => $this->input->post('remarks')
				)
			);

			redirect('bill/requisition/updateReq/' . $id1 . '/1', 'location');
		} else redirect('bill/requisition/updateReq/' . $id1 . '/8', 'location');
	}


	function FinalSubmit($id)
	{
		$action = array(
			'master_id' => $id,
			'action' => 'Submitted',
			'user_id' => $this->session->userdata('username')

		);
		$this->reqsupmodel->saveAction($action);

		$countReq = $this->requisitionmodel->countReq($id);
		if ($countReq > 0) {
			$this->requisitionmodel->FinalSubmit($id);
			redirect('bill/requisition/requisitionList/submit/0/' . $id, 'location');
		} else redirect('bill/requisition/updateReq/' . $id . '/8', 'location');
	}



	function purchased($id)
	{

		$data['message'] = '';
		$data['comment'] = '';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['title'] = 'Comment';
		$data['action'] =  site_url('bill/requisition/purchasedadd/' . $id . '/');
		$data['page'] = '/billView/reqpurchaseDate'; //add page name as a parameter
		$data['nowdate'] = date('d-m-Y'); //add page name as a parameter
		$this->load->view('index', $data);
	}



	function purchasedadd($id)
	{


		$this->requisitionmodel->purchasedadd(
			array(
				'doc_id' => $id, 'purchase_staus' => 1, 'purchase_date' => $this->mkDate($this->input->post('purchase_date')), 'purchase_comment' => $this->input->post('comment')
			),
			$id
		);


		redirect('bill/requisition/requisitionList/purchased');
	}


	function makereturn($id)
	{

		$data['message'] = '';
		$data['comment'] = '';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['title'] = 'Comment';
		$data['action'] =  site_url('bill/requisition/returnmade/' . $id . '/');
		$data['page'] = '/billView/comment'; //add page name as a parameter
		$this->load->view('index', $data);
	}



	function returnmade($id)
	{

		$this->reqsupmodel->assignee_returnmade($id);

		$action = array(
			'master_id' => $id,
			'action' => 'Returned',
			'user_id' => $this->session->userdata('username'),
			'comments' => $this->input->post('comment')
		);
		$this->reqsupmodel->saveAction($action);

		redirect('bill/requisition/requisitionList/return/0');
	}
}
