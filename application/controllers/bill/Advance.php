<?php
class Advance extends MY_Controller
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
		$this->load->model('/advancemodel', '', TRUE);
		$this->load->model('/billmodel', '', TRUE);

		if ($this->userauth->is_logged_in() and $this->session->userdata('authlevel') != 5 and $this->session->userdata('authlevel') != 6 and $this->session->userdata('authlevel') != 7) {

			show_401();
		}
	}

	function Index()
	{

		$data = $this->getListData();
		$this->_set_fields();
		$this->validation->bill_date = 	date('d-m-Y');
		$data['title'] = 'Claim Advance';
		$data['message'] = '';
		$data['openmodel'] = 'no';
		$data['action'] = site_url('bill/advance/add');
		$data['page'] = '/billView/advanceEdit';
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->validation->advance = 0;
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
	}

	function _set_fields()
	{

		$fields['advance_date'] = 'advance date';
		$fields['Company'] = 'Company';
		$fields['loc'] = 'Location';
		$fields['advance_description'] = 'advance description';
		$fields['advance_type'] = 'payment type';
		$fields['doc_file'] = 'doc file';
		$fields['ReportingOfficer'] = 'Reporting Officer';
		$fields['amount'] = 'Amount';
		$fields['suggested_cheque'] = 'Suggested Cheque';
		$this->validation->set_fields($fields);
	}

	function _set_rules()
	{
		$rules['advance_date'] = 'trim|required';
		$rules['loc'] = 'trim|required';
		$rules['advance_description'] = 'trim|required';
		$rules['Company'] = 'trim|required';

		$rules['advance_type'] = 'trim|required';
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
		$data['docs'] = $this->advancemodel->get_documents($id);
		$this->load->view('billView/viewDocs', $data);
	}


	function add()
	{

		$data = $this->getListData();
		// set common properties
		$data['title'] = 'Claim Advance';


		$this->_set_fields();
		$this->_set_rules();




		// run validation
		if ($this->validation->run() == FALSE) {

			$data['message'] = '';
			$data['action'] =  site_url('bill/advance/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		} else if ($this->input->post('ReportingOfficer') == '') {
			$data['message'] = 			'<div class="cancel" align=left>please select reporitng to..</div>';
			$data['action'] =  site_url('bill/advance/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		} else if ($this->input->post('payment_type') == 'Cheque' and $this->input->post('suggested_cheque') == '') {
			$data['message'] = 			'<div class="cancel" align=left>please input Cheque Name..</div>';
			$data['action'] =  site_url('bill/advance/add');
			$data['openmodel'] = 'no';
			$data['itemcount'] = 0;
		} else {
			// save data	

			$this->advancemodel->save($this->makeQueryinsert());

			$data['openmodel'] = 		'yes';
			$advanceId = 				$this->advancemodel->get_last_entry_id($this->mkDate($this->input->post('advance_date')));

			$data['action'] =  			site_url('bill/advance/updatebill/' . $advanceId);
			$data['itemcount'] = 		$this->advancemodel->tot_document_by_bill_id($advanceId);
			$data['action2'] =  site_url('bill/advance/addDoc/' . $advanceId);
			$data['action3'] =  site_url('bill/advance/submitSupervisor/' . $advanceId);
			$data['action4'] =  site_url('bill/advance/addParticular/' . $advanceId);

			$items = $this->advancemodel->get_documents($advanceId)->result();

			$particularitems = $this->advancemodel->get_particular($advanceId)->result();
			$data['table2'] =			$this->particular_table($particularitems);

			$data['table'] =			$this->documten_table($items);
			$data['list_particular'] = $this->advancemodel->list_particular($advanceId);

			$data['message'] = 			'<div class="success" align=left>save successful..</div>';
		}




		$data['page'] = '/billView/advanceEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;
		$this->load->view('index', $data);
	}

	function makeQuery()
	{

		$loc = $this->input->post('loc');
		if ($this->input->post('advance_type') == 'Cheque')
			$loc = 1;
		$sup = $this->input->post('ReportingOfficer');
		$comp = $this->input->post('Company');
		if ($comp == 4 or $comp == 12)
			$sup = 1085;

		// if ($comp == 22 or $comp == 23)
		// 	$sup = 2571;


		return $member = array(
			'advance_description' => $this->input->post('advance_description'),
			'advance_date' => $this->mkDate($this->input->post('advance_date')),
			'company' => $this->input->post('Company'),
			'amount' => $this->input->post('amount'),
			'advance_type' => $this->input->post('advance_type'),
			'supervised_by' => $sup,
			'hold_by' => $sup,
			'cheque_name' => $this->input->post('suggested_cheque'),
			'req_by' => $this->session->userdata('username'),
			'loc' => $loc

		);
	}







	function makeQueryinsert()
	{
		//$id=$this->advancemodel->id();
		$loc = $this->input->post('loc');
		if ($this->input->post('advance_type') == 'Cheque')
			$loc = 1;

		return $member = array(

			'advance_description' => $this->input->post('advance_description'),
			'advance_date' => $this->mkDate($this->input->post('advance_date')),
			'company' => $this->input->post('Company'),
			'hold_by' => $this->input->post('ReportingOfficer'),
			'advance_type' => $this->input->post('advance_type'),
			'supervised_by' => $this->input->post('ReportingOfficer'),
			'cheque_name' => $this->input->post('suggested_cheque'),
			'req_by' => $this->session->userdata('username'),
			'loc' => $loc

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


		$data['company'] = $this->advancemodel->list_company();
		$data['reportingOfficerList'] = $this->advancemodel->list_head();
		return $data;
	}



	function documten_table($items)
	{
		$table = '<table>';

		//$i = 0 + $offset;
		$sl = 1;
		foreach ($items as $row) {
			$table = $table . '<tr>';
			$del = '&nbsp;' . anchor('bill/advance/deleteDoc/' . $row->id . '/' . $row->advance_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));
			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			//$view='<a href="ftp://billmaster:stargold@192.168.1.117/Common_share/'.$row->doc_file.'" target="about_blank" class="view">view</a>';
			$view = '<a href="' . base_url() . 'index.php/bill/bill/url/' . $row->doc_file . '" target="about_blank" class="view">view</a>';

			$doc_file = $row->doc_file;


			$table = $table . '<td>' . $sl . '</td>' . '<td>' . $row->partcular_details . '</td>' . '<td>' . $doc_file . '</td>' . '<td>' . $view . '&nbsp;' . $del . '</td>';
			$sl = $sl + 1;
			$table = $table . '</tr>';
		}

		$table = $table . '</table>';
		return $table;
	}



	function particular_table($items)
	{
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('No', 'Particular', 'Action');

		//$i = 0 + $offset;
		$sl = 1;
		foreach ($items as $row) {

			$del = '&nbsp;' . anchor('bill/advance/deleteParticular/' . $row->id . '/' . $row->advance_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));

			//$view='&nbsp;'.anchor('docs/'.$row->doc_file,'view',array('class'=>'view','target'=>"about_blank"));
			$ac = site_url('bill/advance/updateParticular/' . $row->advance_id . '/' . $row->id);
			$this->table->add_row(

				$sl,
				'<form method="post" action="' . $ac . '"><table><tr><td><textarea rows=3  name="particular"  cols=40>' . $row->partcular_details . '</textarea></td>' .

					'<td><input type="text" name="particular_amount" class="text" value="' . $row->particular_amount . '"/><input type="submit" value="update"/></td></tr></table></form>',
				'&nbsp;' . $del
			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}







	function updatebill($id, $filechk = 1, $msg = 0)
	{

		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();

		$rows = $this->advancemodel->get_by_id($id)->row();
		$this->validation->advance_date = 						date('d-m-Y', strtotime($rows->advance_date));
		$this->validation->Company = 						$rows->company;
		$this->validation->advance_description = 				$rows->advance_description;
		$this->validation->amount = 						$rows->amount;
		$this->validation->advance_type = 					$rows->advance_type;
		$this->validation->ReportingOfficer = 				$rows->supervised_by;
		$this->validation->suggested_cheque = 				$rows->cheque_name;
		$this->validation->loc = 							$rows->loc;
		$data = $this->getListData();

		$data['openmodel'] = 		'yes';
		$data['billId'] = 		$id;
		$data['itemcount'] = 		$this->advancemodel->tot_document_by_bill_id($id);
		$items = $this->advancemodel->get_documents($id)->result();
		$particularitems = $this->advancemodel->get_particular($id)->result();
		$data['table'] =			$this->documten_table($items);
		$data['table2'] =			$this->particular_table($particularitems);
		// run validation
		if ($this->validation->run() == FALSE) {

			if ($filechk == 2)
				$data['message'] = '<div class="cancel" align="left">File Not Found..</div>';
			else if ($filechk == 3)
				$data['message'] = '<div class="cancel" align="left">FTP Connection Fail..</div>';
			else if ($filechk == 4)
				$data['message'] = '<div class="cancel" align="left">FTP Login Fail..</div>';
			else if ($filechk == 6)
				$data['message'] = '<div class="cancel" align="left">Please Select Particual..</div>';
			else if ($filechk == 7)
				$data['message'] = '<div class="cancel" align="left">Please Complete All Particual fields..</div>';
			else if ($filechk == 11)
				$data['message'] = '<div class="cancel" align="left">This file is already saved in the system..</div>';
			else if ($filechk == 21)
				$data['message'] = '<div class="cancel" align="left">Requisition not found or Already exist in another bill..</div>';
			else $data['message'] = '';
			// load view 



		} else if ($this->input->post('ReportingOfficer') == '' and $rows->step_status == 1) {
			$data['message'] = 			'<div class="cancel" align=left>please select reporitng to..</div>';
		} else {
			// save data

			if ($rows->step_status == 1) $this->advancemodel->update($id, $this->makeQuery());




			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}

		$data['list_particular'] = $this->advancemodel->list_particular($id);
		$data['title'] = 'Claim Advance';
		$data['action'] = site_url('bill/advance/updatebill/' . $id);
		$data['action2'] =  site_url('bill/advance/addDoc/' . $id);
		$data['action3'] =  site_url('bill/advance/submitSupervisor/' . $id);






		$data['action4'] =  site_url('bill/advance/addParticular/' . $id);
		$data['page'] = '/billView/advanceEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 1;
		$data['recommend'] = 0;



		if ($rows->step_status == 2) {
			$data['reportingOfficerList2'] = $this->billsupervisemodel->list_head($this->validation->amount, $this->validation->Company);
			$data['usercode'] = $this->billsupervisemodel->user_code();
			$data['action3'] =  site_url('bill/supervisebill/FinalSubmit/' . $id);
			$data['entry'] = 0;
			$data['recommend'] = 1;
		}


		if ($rows->step_status == 3) {

			$data['usercode'] = 'audit';
			$data['action3'] =  site_url('bill/auditbill/FinalSubmit/' . $id);
			$data['entry'] = 0;
			$data['recommend'] = 1;
		}

		if ($msg == 4) {
			$data['message'] = 			'<div class="cancel" align=left>please add amount..</div>';
		}


		$this->load->view('index', $data);
	}

	function addDoc($id)
	{



		if ($this->input->post('pid') == "") redirect('bill/advance/updatebill/' . $id . '/6', 'location');


		$ftp_server = "192.168.1.117";
		$conn_id = ftp_connect($ftp_server)
			or redirect('bill/advance/updatebill/' . $id . '/3', 'location');

		$login_result = ftp_login($conn_id, "bill", "bill007");
		if ((!$conn_id) || (!$login_result))
			redirect('bill/advance/updatebill/' . $id . '/4', 'location');




		$str = str_replace('\\', '/', $this->input->post('doc_file'));
		$str = trim($str, " ");
		$doubleFileChk = $this->advancemodel->doubleFileChk($str);
		if ($doubleFileChk > 0)
			redirect('bill/advance/updatebill/' . $id . '/11', 'location');

		$pizza  = $str;
		$pieces = explode("/", $pizza);
		$piecescount = count($pieces);

		if ($piecescount != 2) {
			ftp_close($conn_id);
			redirect('bill/advance/updatebill/' . $id . '/2', 'location');
		}


		$path = "/BILL/" . $pieces[0] . "/";
		$contents_on_server = ftp_nlist($conn_id, $path);
		ftp_close($conn_id);


		if (in_array($pieces[1], $contents_on_server) and $this->input->post('doc_file') != "") {

			$this->advancemodel->saveDoc(
				array(
					'advance_details_id' => $this->input->post('pid'),
					'doc_file' => $str,
					'advance_id' => $id
				)
			);
			redirect('bill/advance/updatebill/' . $id . '/1', 'location');
		} else redirect('bill/advance/updatebill/' . $id . '/2', 'location');
	}

	function addParticular($id)
	{

		if ($this->input->post('particular') != ""  and $this->input->post('particular_amount') != "") {

			$this->advancemodel->saveParticular(
				array(
					'partcular_details' => $this->input->post('particular'),
					'particular_amount' => $this->input->post('particular_amount'),
					'advance_id' => $id
				)
			);
			$this->advancemodel->updateSumParticular($id);
			redirect('bill/advance/updatebill/' . $id . '/1', 'location');
		} else redirect('bill/advance/updatebill/' . $id . '/7', 'location');
	}


	function updateParticular($id1, $id2)
	{

		if ($this->input->post('particular') != ""   and $this->input->post('particular_amount') != "") {
			$this->advancemodel->updateParticular(
				$id2,
				array(
					'partcular_details' => $this->input->post('particular'),
					'particular_amount' => $this->input->post('particular_amount'),
				)
			);
			$this->advancemodel->updateSumParticular($id1);
			redirect('bill/advance/updatebill/' . $id1 . '/1', 'location');
		} else redirect('bill/advance/updatebill/' . $id1 . '/7', 'location');
	}



	function deleteDoc($id1, $id2)
	{
		$this->advancemodel->deleteDoc($id1);


		redirect('bill/advance/updatebill/' . $id2, 'location');
	}





	function deleteParticular($id1, $id2)
	{
		$this->advancemodel->deleteParticular($id1);
		$this->advancemodel->updateSumParticular($id2);
		redirect('bill/advance/updatebill/' . $id2, 'location');
	}









	function advanceList($offset = 0, $message = '')
	{
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		//Search 
		$data['action'] = site_url('bill/advance/searchAdvance');
		$data['title'] = "Advance List";
		// set user message
		$data['message'] = $message;

		if ($message == 'submitSupervisor')
			$data['message'] = "<div class='success' align=left>Submission to Supervisor Successful..!!</div>";


		// load data
		$bill = $this->advancemodel->get_paged_list($this->limit, $offset)->result();

		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/advance/advanceList/');
		$number_of_rows = $this->advancemodel->count_all();
		$config['total_rows'] = $number_of_rows;
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;

		$data['table'] = $this->advance_table($bill);
		$data['page'] = '/billView/advanceList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}


	function searchAdvance()
	{

		//Search 
		$data['action'] = site_url('bill/advance/searchAdvance');;
		$data['title'] = "Advance List";
		// set user message
		$data['message'] = '';

		// load data
		$bill = $this->advancemodel->get_search_by_id($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->advancemodel->count_search($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')));

		$data['table'] = $this->advance_table($bill);
		$data['page'] = '/billView/advanceList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}




	function advance_table($bill)
	{
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('id', 'advance&nbsp;date', 'location', 'description', 'Net&nbsp;Pay', 'Supervised&nbsp;by', 'status', 'action', 'Documents');

		$status = "";

		//$i = 0 + $offset;
		$sl = 1;
		foreach ($bill as $row) {

			if ($row->step_status == 1)
				$status = "Still Not Final Submitted";
			else if ($row->step_status == 2) {
				$hold_by = $this->billmodel->get_holdby($row->hold_by);
				$status = "Processing</br>hold&nbsp;by:&nbsp;" . $hold_by;
			} else if ($row->step_status == 3)
				$status = "Submitted to Accounts";

			else if ($row->step_status == 4) {
				$status = "Accounts Clear";
			} else if ($row->step_status == 5)
				$status = "Payment Made";






			if ($row->step_status == 1 and $row->cancel_staus == 0)
				$view = anchor('bill/advance/updatebill/' . $row->id, 'update&nbsp;advance', array('class' => 'update'));

			else $view = "";


			$viewbill = anchor('reports/reports/view_advance/' . $row->id, 'view&nbsp;advance', array('class' => 'view', 'target' => 'about_blank'));
			//$viewhistory=anchor('reports/reports/view_details_history/'.$row->id,'view&nbsp;history',array('class'=>'view','target'=>'about_blank'));			






			$viewbilldoc = "";
			$docsl = 1;
			$pchk = "start";
			$pcount = 1;


			$billdoc = $this->advancemodel->get_special_documents($row->id);
			foreach ($billdoc->result() as $rows) {

				if (strlen($rows->doc_file) < 5)
					$report = base_url() . 'index.php/reports/reports/order_material_list/' . $rows->advance_id . '/' . $rows->advance_details_id . '/' . $rows->particular . '/' . $rows->doc_file;
				else $report = base_url() . 'index.php/bill/bill/url/' . $rows->doc_file; //'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;

				if ($pchk == "start") {
					$viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
				} else if ($pchk == $rows->advance_details_id) {
					$viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
				} else {
					$pcount = $pcount + 1;
					$viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
				}
				$docsl = $docsl + 1;

				$pchk = $rows->advance_details_id;
			}




			if ($row->check_ready == 1)
				$status = "&nbsp;Check&nbsp;is&nbsp;Ready&nbsp;";



			if ($row->cancel_staus == 1)
				$status = "&nbsp;Canceled&nbsp;";



			$loc = "";
			if ($row->loc == 1) $loc = "Chittagong Head Office";
			else if ($row->loc == 2) $loc = "Dhaka Office";
			else if ($row->loc == 3) $loc = "Mohakhali Office";
			$this->table->add_row(
				$row->id,
				$row->advance_date,
				$loc,
				$row->advance_description,
				$row->amount,
				$row->superviseName,
				$status,
				$viewbill . "</br>" . $view,
				$viewbilldoc
			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}

	function submitSupervisor($id)
	{


		$this->advancemodel->submitSupervisor($id);
		$rows = $this->advancemodel->get_by_id($id)->row();


		if ($rows->amount > 0) {
			redirect('bill/advance/advanceList/0/submitSupervisor');
		} else
			redirect('bill/advance/updatebill/' . $id . '/1/4', 'location');
	}
}
