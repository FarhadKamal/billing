<?php
class Reqsup extends MY_Controller
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
		$this->load->model('/reqsupmodel', '', TRUE);

		if ($this->userauth->is_logged_in() and $this->session->userdata('authlevel') != 5 and $this->session->userdata('authlevel') != 6) {

			show_401();
		}
	}

	function Index()
	{
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


	function makeQuery()
	{
		return $member = array(
			'company' => $this->input->post('Company'),
			'req_loc' => $this->input->post('loc')

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
		$data['company'] = $this->reqsupmodel->list_company();
		$data['list_material'] = $this->reqsupmodel->list_material();
		$data['reportingOfficerList'] = $this->reqsupmodel->list_head();
		$count_req_boss = $this->reqsupmodel->count_req_boss();

		if ($count_req_boss > 0)
			$data['assigned_personList'] = $this->reqsupmodel->assigned_personList();

		return $data;
	}



	function requisitionList2($message = '')
	{


		$data['title'] = "Material Requisition List";
		// set user message
		$data['message'] = $message;

		if ($message == 'submit')
			$data['message'] = "<div class='success' align=left>Approved Successful..!!</div>";
		else	if ($message == 'del')
			$data['message'] = "<div class='cancel' align=left>Cancel Successful..!!</div>";


		// load data
		$requisition = $this->reqsupmodel->get_paged_list()->result();


		$data['table'] = $this->requisition_table($requisition);
		$data['page'] = '/billView/billListAll'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}

	function requisitionList($offset = 0, $message = '')
	{

		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		$data['title'] = "Material Requisition List";
		// set user message
		$data['message'] = $message;

		$data['action'] = site_url('bill/reqsup/searchRequisition');

		if ($message == 'submit')
			$data['message'] = "<div class='success' align=left>Approved Successful..!!</div>";
		else	if ($message == 'del')
			$data['message'] = "<div class='cancel' align=left>Cancel Successful..!!</div>";
		else	if ($message == 'return')
			$data['message'] = "<div class='success' align=left>Return Successful..!!</div>";


		// load data
		$requisition = $this->reqsupmodel->get_paged_list($this->limit, $offset)->result();

		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/reqsup/requisitionList/');
		$number_of_rows = $this->reqsupmodel->count_all();
		$config['total_rows'] = $number_of_rows;
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;


		$data['table'] = $this->requisition_table($requisition);
		$data['page'] = '/billView/reqsupList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}

	function searchRequisition()
	{

		//Search 
		$data['action'] = site_url('bill/reqsup/searchRequisition');
		$data['title'] = "Material Requisition List";
		// set user message
		$data['message'] = '';

		// load data
		$requisition = $this->reqsupmodel->get_search_by_id($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->reqsupmodel->count_search($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')));

		$data['table'] = $this->requisition_table($requisition);
		$data['page'] = '/billView/reqsupList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}


	function makecancel($id)
	{

		$data['message'] = '';
		$data['comment'] = '';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['title'] = 'Comment';
		$data['action'] =  site_url('bill/reqsup/cancelmade/' . $id . '/');
		$data['page'] = '/billView/comment'; //add page name as a parameter
		$this->load->view('index', $data);
	}

	function cancelmade($id)
	{

		$this->reqsupmodel->cancel($id);

		$action = array(
			'master_id' => $id,
			'action' => 'Canceled',
			'user_id' => $this->session->userdata('username'),
			'comments' => $this->input->post('comment')
		);
		$this->reqsupmodel->saveAction($action);
		redirect('bill/reqsup/requisitionList/0/del');
	}


	function makereturn($id)
	{

		$data['message'] = '';
		$data['comment'] = '';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['title'] = 'Comment';
		$data['action'] =  site_url('bill/reqsup/returnmade/' . $id . '/');
		$data['page'] = '/billView/comment'; //add page name as a parameter
		$this->load->view('index', $data);
	}



	function returnmade($id)
	{

		$this->reqsupmodel->returnmade($id);

		$action = array(
			'master_id' => $id,
			'action' => 'Returned',
			'user_id' => $this->session->userdata('username'),
			'comments' => $this->input->post('comment')
		);
		$this->reqsupmodel->saveAction($action);

		redirect('bill/reqsup/requisitionList/0/return');
	}



	function requisition_table($requisition)
	{
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('ID', 'request&nbsp;by', 'request&nbsp;date', 'Requested&nbsp;to', 'approved&nbsp;date', 'assigned&nbsp;person', 'Procurement&nbsp;Approved', 'Purchase&nbsp;Status', 'Complete&nbsp;Status', 'Cancel&nbsp;Status', 'action');

		$status = "";
		$approve = "";

		//$i = 0 + $offset;
		$sl = 1;
		foreach ($requisition as $row) {

			if ($row->procurement_pass == 0 and $row->submit_status == 1  and $row->cancel_status == 0 and $row->hold_by == $this->session->userdata('username')) $view = anchor('bill/reqsup/updateReq/' . $row->id, 'process', array('class' => 'update'));
			else if ($row->procurement_pass == 1 and $row->cancel_status == 0 and $row->bill_status == 0 and $row->hold_by == $this->session->userdata('username')) $view = anchor('bill/reqsup/updateReq/' . $row->id, 'change&nbsp;assigned&nbsp;person', array('class' => 'update'));
			else $view = "";
			$report = anchor('reports/reports/view_material_list/' . $row->id, 'view', array('class' => 'view', 'target' => 'about_blank'));


			if ($row->cancel_status == 0 and $row->procurement_pass == 0) {
				$cancel = anchor('bill/reqsup/makecancel/' . $row->id, 'cancel', array('class' => 'cancel'));
				$cancel = $cancel . '<br/>' . anchor('bill/reqsup/makereturn/' . $row->id, 'return', array('class' => 'update'));
			} else $cancel = "";


			if ($row->cancel_status == 0)
				$cancel_status = "No";
			else $cancel_status = "Canceled";

			if ($row->purchase_staus == 0 and $row->bill_status == 0)
				$purchase_staus = "Not Yet";
			else $purchase_staus = "Purchased&nbsp;done<br/>Purchased&nbsp;date:" . $row->purchase_date . "<br/>Comments:" . $row->purchase_comment;

			if ($row->bill_status == 0) $complete_status = "<font color='#ff8e29'><b>No</b></font>";
			else $complete_status = "<font color='#0ac116'><b>Yes</b></font>";
			if ($row->procurement_pass == 0) $procurement_pass = "No";
			else $procurement_pass = "Yes";


			$itemdoc = $this->reqsupmodel->getDocId($row->id)->result();
			$doctable = "<table>";
			$docsl = 0;
			foreach ($itemdoc as $rowdoc) {
				$docsl = $docsl + 1;
				$viewbillReport = "";
				if ($rowdoc->bill_type == "vendor") {
					$viewbillReport = anchor('reports/reports/view_bill/' . $rowdoc->doc_id, 'bill-' . $docsl, array('class' => 'view', 'target' => 'about_blank'));
				} else
					$viewbillReport = anchor('reports/reports/view_general/' . $rowdoc->doc_id, 'bill-' . $docsl, array('class' => 'view', 'target' => 'about_blank'));

				$doctable .= "<tr>";
				$doctable .= "<td>";
				$doctable .= $viewbillReport;
				$doctable .= "</td>";
				$doctable .= "</tr>";
			}
			$doctable .= "</table>";


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
				$row->request_by . '# ' . $row->createdName,
				$row->request_date,
				$row->approveName,
				$row->approved_date,
				$row->assignName,
				$procurement_pass,

				"<font color='$wcolor'><b>" . $purchase_staus . "</b></font>",
				$complete_status,
				$cancel_status,
				$report . '<br/>' . $view . '<br/>' . $cancel . '<br/>' . $doctable . '<br/>'
			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}


	function approve($id)
	{
		$this->reqsupmodel->approve($id);

		redirect('bill/reqsup/requisitionList/0/submit');
	}

	function updateReq($id, $filechk = 1)
	{

		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();

		$rows = $this->reqsupmodel->get_by_id($id)->row();

		$this->validation->Company = 						$rows->company;
		$this->validation->loc = 							$rows->req_loc;
		$this->validation->ReportingOfficer = 				$rows->approve_by;


		$data = $this->getListData();
		$data['assigned_person'] = 							$rows->assigned_person;
		$data['openmodel'] = 		'yes';
		//$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->reqsupmodel->tot_item_by_id($id);
		$items = $this->reqsupmodel->get_items($id)->result();
		$data['table'] =			$this->item_table($items);

		// run validation
		if ($this->validation->run() == FALSE) {

			if ($filechk == 7)
				$data['message'] = '<div class="cancel" align="left">Please Complete Item name and Quantity Required and Contact Person Details..</div>';
			else $data['message'] = '';
			// load view 



		} else {
			// save data

			$this->reqsupmodel->update($id, $this->makeQuery());

			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}


		$data['IT']					= $this->reqsupmodel->get_boss_id("IT", 0);
		$data['Director']			= $this->reqsupmodel->get_boss_id("Director", 0);
		$data['HR']					= $this->reqsupmodel->get_boss_id("HR", 0);
		$data['Proc1']				= $this->reqsupmodel->get_boss_id("Proc", 1);
		$data['Proc2']				= $this->reqsupmodel->get_boss_id("Proc", 2);
		$data['AzneoFinance']		= $this->reqsupmodel->get_boss_id("AzneoFinance", 0);

		$data['title'] = 'Claim Material Requisition';
		$data['action'] = site_url('bill/reqsup/updateReq/' . $id);
		$data['action2'] =  site_url('bill/reqsup/addItem/' . $id);
		$data['action3'] =  site_url('bill/reqsup/FinalSubmit/' . $id);
		$data['page'] = '/billView/requisitionEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 0;
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

			$del = '&nbsp;' . anchor('bill/reqsup/deleteItem/' . $row->id . '/' . $row->master_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));

			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			$ac = site_url('bill/reqsup/updateItem/' . $row->master_id . '/' . $row->id);
			$this->table->add_row(

				$sl,
				'<form method="post" action="' . $ac . '"><table style="font: 0.90em arial;width:690;"><tr><td>Item&nbsp;Name&nbsp;<br/><input type="text" readonly name="item_name" class="text" value="' . $row->item_name . '"/></td>' .
					'<td>specs&nbsp;<br/><input type="text" name="req_sap_id" class="text" value="' . $row->req_sap_id . '"/></td>' .
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

		if ($this->input->post('item_name') != "" and $this->input->post('qty_req') != ""   and $this->input->post('contact_details') != "") {

			$this->reqsupmodel->saveItem(
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

			redirect('bill/reqsup/updateReq/' . $id . '/1', 'location');
		} else redirect('bill/reqsup/updateReq/' . $id . '/7', 'location');
	}


	function deleteItem($id1, $id2)
	{
		$this->reqsupmodel->deleteItem($id1);
		redirect('bill/reqsup/updateReq/' . $id2, 'location');
	}


	function updateItem($id1, $id2)
	{

		if ($this->input->post('item_name') != "" and $this->input->post('qty_hand') != ""  and $this->input->post('qty_req') != ""  and $this->input->post('delivery_date' . $id2) != "") {
			$this->reqsupmodel->updateItem(
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

			redirect('bill/reqsup/updateReq/' . $id1 . '/1', 'location');
		} else redirect('bill/reqsup/updateReq/' . $id1 . '/7', 'location');
	}


	function FinalSubmit($id)
	{

		$action = array(
			'master_id' => $id,
			'action' => 'Approved',
			'user_id' => $this->session->userdata('username'),
			'comments' => $this->input->post('comment')
		);
		$this->reqsupmodel->saveAction($action);

		$count_req_boss = $this->reqsupmodel->count_req_boss();

		if ($count_req_boss > 0) {
			$this->reqsupmodel->approve($id, $this->input->post('assigned_person'));
			redirect('bill/reqsup/requisitionList/0/submit');
		}
		$this->reqsupmodel->FinalSubmit($id, $this->input->post('forward_person'));
		redirect('bill/reqsup/requisitionList/0/submit', 'location');
	}
}
