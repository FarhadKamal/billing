<?php
class Iousup extends MY_Controller
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
		$this->load->model('/iousupmodel', '', TRUE);
		$this->load->model('/ioumodel', '', TRUE);

		if ($this->userauth->is_logged_in() and $this->session->userdata('authlevel') != 5 and $this->session->userdata('authlevel') != 6) {

			show_401();
		}
	}

	function Index()
	{
	}

	function _set_fields()
	{

		$fields['Company'] = 'Company';
		$fields['ReportingOfficer'] = 'Reporting Officer';
		$fields['amount'] = 'Amount';
		$fields['purpose'] = 'Purpose';


		$this->validation->set_fields($fields);
	}

	function _set_rules()
	{
		$rules['Company'] = 'trim|required';

		$rules['amount'] = 'trim|required';
		$rules['purpose'] = 'trim|required';

		$this->validation->set_rules($rules);
		$this->validation->set_message('required', '* required');
		$this->validation->set_error_delimiters('<p class="error">', '</p>');
	}



	function makeQuery()
	{
		return $member = array(
			'company' => $this->input->post('Company'),


			'amount' => $this->input->post('amount'),
			'purpose' => $this->input->post('purpose')

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
		$data['company'] = $this->iousupmodel->list_company();
		$data['reportingOfficerList'] = $this->iousupmodel->list_head();
		$data['depOfficerList'] = $this->iousupmodel->list_dep_head();
		return $data;
	}


	function searchIou()
	{

		//Search 
		$data['action'] = site_url('bill/iousup/searchIou');;
		$data['title'] = "IOU List";
		// set user message
		$data['message'] = '';

		// load data
		$requisition = $this->iousupmodel->get_search_by_id($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')))->result();

		// generate pagination
		$this->load->library('pagination');
		$data['pagination'] = '';
		$data['totalrecord'] = $this->iousupmodel->count_search($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')));

		$data['table'] = $this->iou_table($requisition);
		$data['page'] = '/billView/iouList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}


	function iouList($message = '', $offset = 0)
	{
		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		$data['action'] = site_url('bill/iousup/searchIou');
		$data['title'] = "Pending IOU List";
		// set user message
		$data['message'] = $message;

		if ($message == 'submit')
			$data['message'] = "<div class='success' align=left>Approved Successful..!!</div>";
		if ($message == 'cancel')
			$data['message'] = "<div class='cancel' align=left>Cancel Successful..!!</div>";



		// load data
		$iou = $this->iousupmodel->get_paged_list($this->limit, $offset)->result();

		// generate pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('bill/iousup/iouList/');
		$number_of_rows = $this->iousupmodel->count_all();
		$config['total_rows'] = $number_of_rows;
		$config['per_page'] = $this->limit;
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['totalrecord'] = $number_of_rows;


		$data['table'] = $this->iou_table($iou);
		$data['page'] = '/billView/iouList'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$this->load->view('index', $data);
	}






	function iou_table($requisition)
	{

		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");
		$this->table->set_heading('ID', 'request&nbsp;by', 'request&nbsp;date', 'Requested&nbsp;to', 'department&nbsp;approved&nbsp;date', 'Finance Approved Date', 'amount', 'purpose', 'hold by ', 'status', 'action');

		$status = "";



		//$i = 0 + $offset;
		$sl = 1;
		foreach ($requisition as $row) {

			if ($row->step_status == 1)	$status = "Still Not Final Submitted";
			else if ($row->step_status == 2)	$status = "Submitted to Department Head";

			else if ($row->step_status == 3 and ($row->hold_by == 2405 or $row->hold_by == 2346))	$status = "Submitted to Finance";

			else if ($row->step_status == 4)	$status = "Submitted to Cash Counter";
			else if ($row->step_status == 5)	$status = "Payment made";

			if ($row->cancel_status == 1 and $row->step_status == 2)
				$status = "Canceled";
			else if ($row->cancel_status == 1 and $row->step_status == 3)
				$status = "Canceled";

			if ($row->step_status > 1 and $row->cancel_status == 0 and $row->step_status < 4 and $row->hold_by == $this->session->userdata('username')) $view = anchor('bill/iousup/updateIou/' . $row->id, 'process&nbsp;', array('class' => 'update'));
			else $view = "";

			$report = anchor('reports/reports/view_iou/' . $row->id, 'view', array('class' => 'view', 'target' => 'about_blank'));
			$viewhistory = anchor('reports/reports/view_history_iou/' . $row->id, 'view&nbsp;history', array('class' => 'view', 'target' => 'about_blank'));

			if ($row->step_status > 1 and $row->cancel_status == 0 and $row->step_status < 4 and $row->hold_by == $this->session->userdata('username'))
				$cancel = '&nbsp;' . anchor('bill/iousup/cancel/' . $row->id, 'cancel', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to cancel?')"));
			else $cancel = "";

			$this->table->add_row(

				$row->id,
				$row->req_by . " " . $row->createdName,
				$row->req_date,
				$row->deptName,
				$row->dep_accept_date,

				$row->dgm_accept_date,
				$row->amount,
				$row->purpose,
				$row->holdName,
				$status,
				$view . "<br/><br/>" . $cancel . "<br/><br/>" . $report . "<br/><br/>" . $viewhistory

			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}




	function updateIou($id, $filechk = 1)
	{

		// set common properties	
		// set validation properties
		$this->_set_fields();
		$this->_set_rules();



		$data = $this->getListData();

		$data['openmodel'] = 		'yes';
		//$data['billId'] = 		$id;


		// run validation
		if ($this->validation->run() == FALSE) {


			$data['message'] = '';
			// load view 
			if ($filechk == 7)
				$data['message'] = '<div class="cancel" align="left">Please input reason  first, then you can click add.</div>';
			else if ($filechk == 8)
				$data['message'] = '<div class="cancel" align="left">Please input reason  first, then you can click update.</div>';
		} else {
			// save data

			$this->iousupmodel->update($id, $this->makeQuery());

			$data['message'] = '<div class="success" align="left">update successful..</div>';
		}
		$data['action3'] =  site_url('bill/iousup/AddItem/' . $id);
		$items = $this->ioumodel->get_items($id)->result();
		$data['table'] =			$this->item_table($items);


		$data['dep_code'] = $this->iousupmodel->dep_code_by_id($this->session->userdata('username'));

		$data['reportboss'] = $this->iousupmodel->getReportingOfficer();





		$this->ioumodel->calculate_total($id);
		$rows = $this->ioumodel->get_by_id($id)->row();

		$this->validation->Company = 						$rows->company;


		$this->validation->ReportingOfficer = 		$rows->dep_accept_by;
		$this->validation->purpose = 				$rows->purpose;
		$this->validation->amount = 				$rows->amount;

		$data['title'] = 'Claim IOU';
		$data['action'] = site_url('bill/iousup/updateIou/' . $id);

		$data['action2'] =  site_url('bill/iousup/FinalSubmit/' . $id);
		$data['page'] = '/billView/iouEdit'; //add page name as a parameter
		$data['userlevel'] = $this->session->userdata('authlevel');
		$data['entry'] = 0;
		$data['recommend'] = 1;
		$this->load->view('index', $data);
	}



	function FinalSubmit($id)
	{


		//AZ-NEO Businesss
		//$comp = array(2,6,8,9,14,18);
		$comp = array(2, 8, 9, 14, 5, 10, 4, 12, 22, 23);

		$compfaz = array(7, 18);


		$compAsad = array(1, 3);

		//$UserCode= $this->iousupmodel->user_code_by_id($this->session->userdata('username'));
		//dep_code_by_id
		//echo $UserCode;
		$dep_code = $this->iousupmodel->dep_code_by_id($this->session->userdata('username'));

		$rows = $this->ioumodel->get_by_id($id)->row();
		$emprow = $this->iousupmodel->get_emp_det($rows->req_by)->row();

		if (in_array($rows->company, $comp)) {

			if ($this->session->userdata('username') == 2346) {


				if (in_array($rows->company, array(4, 12, 22, 23)) and $rows->amount > 50000) {
					$this->iousupmodel->FinalSubmit(
						$id,
						array(
							'agm_remarks' => $this->input->post('comment'),
							'step_status' => 3,
							'dgm_accept_date' => date('Y-m-d H:i:s'),
							'dgm_accept_by' => 2346,
							'hold_by' => '003'

						)
					);
				} else if ($rows->company == 18 and $rows->dep_accept_by != 2023) {

					$this->iousupmodel->FinalSubmit(
						$id,
						array(
							'agm_remarks' => $this->input->post('comment'),
							'step_status' => 3,
							'dgm_accept_date' => date('Y-m-d H:i:s'),
							'dgm_accept_by' => 2346,
							'hold_by' => 2023

						)
					);
				} else {

					$this->iousupmodel->FinalSubmit(
						$id,
						array(
							'agm_remarks' => $this->input->post('comment'),
							'step_status' => 4,
							'dgm_accept_by' => 2346,
							'hold_by' => '',
							'dgm_accept_date' => date('Y-m-d H:i:s')

						)
					);
				}
			} else if ($this->session->userdata('username') == 2405 and $rows->company == 18) {


				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'agm_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'dgm_accept_by' => 2405,
						'hold_by' => '',
						'dgm_accept_date' => date('Y-m-d H:i:s')

					)
				);
			} else if ($this->session->userdata('username') == '003' and in_array($rows->company, array(4, 12, 22, 23))  and $rows->dgm_accept_by == 2346) {

				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'ceo_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'ceo_accept_by' => '003',
						'hold_by' => '',
						'ceo_accept_date' => date('Y-m-d H:i:s')
					)
				);
			} else if ($this->session->userdata('username') == 2023 and $rows->company == 18 and $rows->dgm_accept_by == 2405) {

				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'ceo_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'ceo_accept_by' => 2023,
						'hold_by' => '',
						'ceo_accept_date' => date('Y-m-d H:i:s')
					)
				);
			} else if ($rows->company == 18) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'dep_remarks' => $this->input->post('comment'),
						'step_status' => 3,
						'dep_accept_date' => date('Y-m-d H:i:s'),
						'hold_by' => 2405

					)
				);
			} else {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'dep_remarks' => $this->input->post('comment'),
						'step_status' => 3,
						'dep_accept_date' => date('Y-m-d H:i:s'),
						'hold_by' => 2346

					)
				);
			}
		}








		if (in_array($rows->company, array(6, 21))) {

			if ($this->session->userdata('username') == 2405) {


				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'agm_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'dgm_accept_by' => 2405,
						'hold_by' => '',
						'dgm_accept_date' => date('Y-m-d H:i:s')

					)
				);
			} else {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'dep_remarks' => $this->input->post('comment'),
						'step_status' => 3,
						'dep_accept_date' => date('Y-m-d H:i:s'),
						'hold_by' => 2405

					)
				);
			}
		}





		/*		
			
			else if ($emprow->vLocations=='Dhaka H/O')  {  //Dhaka Location
			
				if($this->session->userdata('username')==1017){
					$this->iousupmodel->FinalSubmit($id,			
					array(
					'agm_remarks' => $this->input->post('comment'),
					'step_status' => 4,
					'dgm_accept_by' => 1017,
					'hold_by' => '',
					'dgm_accept_date' => date('Y-m-d H:i:s')
					
					)
					);
				}else{
					$this->iousupmodel->FinalSubmit(	$id	,	
					array(
					'dep_remarks' => $this->input->post('comment'),
					'step_status' => 3,
					'dep_accept_date' => date('Y-m-d H:i:s'),
					'hold_by' => 1017
					
					
					)
					);
				}
			
			}
			
			*/ else if (in_array($rows->company, $compfaz)) {  //Other Businesss
			/*
				if($this->session->userdata('username')==2405 and $rows->amount>50000  ){
						$this->iousupmodel->FinalSubmit($id,			
							array(
							'agm_remarks' => $this->input->post('comment'),
							'step_status' => 3,
							'dgm_accept_date' => date('Y-m-d H:i:s'),
							'dgm_accept_by' => 2405,
							'hold_by' => '003'
							
							)
						);
					}
								
				else and $rows->amount<=50000 */

			if ($this->session->userdata('username') == 2405) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'agm_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'dgm_accept_by' => 2405,
						'hold_by' => '',
						'dgm_accept_date' => date('Y-m-d H:i:s')

					)
				);
			}


			/*
				else if($this->session->userdata('username')=='003'){
				$this->iousupmodel->FinalSubmit($id,			
						array(
						'dir_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'dir_accept_by' => '003',
						'hold_by' => '',
						'dir_accept_date' => date('Y-m-d H:i:s')					
						)
						);
				}
				*/ else if ($dep_code == 0) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'dep_remarks' => $this->input->post('comment'),
						'step_status' => 3,
						'dep_accept_date' => date('Y-m-d H:i:s'),
						'hold_by' => $this->input->post('forward')


					)
				);
			} else if ($dep_code == 1) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(


						'ceo_remarks' => $this->input->post('comment'),
						'step_status' => 3,
						'ceo_accept_by' => $this->session->userdata('username'),
						'ceo_accept_date' => date('Y-m-d H:i:s'),
						'hold_by' => 2405

					)
				);
			}
		} else if (in_array($rows->company, $compAsad)) {  //Other Businesss

			if (
				$this->session->userdata('username') == 2346 and $rows->amount > 50000 and  $rows->dir_accept_by != 1970
				and  $rows->dep_accept_by != 1970 	 and  $rows->dgm_accept_by != 1970  and  $rows->ceo_accept_by != 1970

			) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'agm_remarks' => $this->input->post('comment'),
						'step_status' => 3,
						'dgm_accept_date' => date('Y-m-d H:i:s'),
						'dgm_accept_by' => 2346,
						'hold_by' => 1970

					)
				);
			} else if ($this->session->userdata('username') == 2346 and $rows->amount > 50000) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'agm_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'dgm_accept_by' => 2346,
						'hold_by' => '',
						'dgm_accept_date' => date('Y-m-d H:i:s')

					)
				);
			} else if ($this->session->userdata('username') == 2346  and $rows->amount <= 50000) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'agm_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'dgm_accept_by' => 2346,
						'hold_by' => '',
						'dgm_accept_date' => date('Y-m-d H:i:s')

					)
				);
			} else if ($this->session->userdata('username') == 1970  and ($rows->dgm_accept_by == 2346
				or  $rows->ceo_accept_by == 2346
				or  $rows->dep_accept_by == 2346)) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'dir_remarks' => $this->input->post('comment'),
						'step_status' => 4,
						'dir_accept_by' => 1970,
						'hold_by' => '',
						'dir_accept_date' => date('Y-m-d H:i:s')
					)
				);
			} else if ($dep_code == 0) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(
						'dep_remarks' => $this->input->post('comment'),
						'step_status' => 3,
						'dep_accept_date' => date('Y-m-d H:i:s'),
						'hold_by' => $this->input->post('forward')


					)
				);
			} else if ($dep_code == 1) {
				$this->iousupmodel->FinalSubmit(
					$id,
					array(


						'ceo_remarks' => $this->input->post('comment'),
						'step_status' => 3,
						'ceo_accept_by' => $this->session->userdata('username'),
						'ceo_accept_date' => date('Y-m-d H:i:s'),
						'hold_by' => 2346

					)
				);
			}
		}

		redirect('bill/iousup/iouList/submit', 'location');
	}


	function Cancel($id)
	{


		$this->iousupmodel->Cancel($id);
		redirect('bill/iousup/iouList/cancel', 'location');
	}

	function item_table($items)
	{
		// generate table data
		$this->load->library('table');
		$this->table->set_empty("&nbsp;");

		$this->table->set_heading('Ref ID', 'Reasons and Amounts&nbsp;');

		//$i = 0 + $offset;
		$sl = 1;
		foreach ($items as $row) {

			$del = '&nbsp;' . anchor('bill/iousup/deleteItem/' . $row->id . '/' . $row->iou_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));

			$ac = site_url('bill/iousup/updateItem/' . $row->iou_id . '/' . $row->id);
			$this->table->add_row(

				$row->id,
				'<form method="post" action="' . $ac . '"><table style="font: 0.90em arial;width:690;"><tr>' .

					'<td>Reason&nbsp;For&nbsp;Request&nbsp;<br/><textarea rows=4  name="reason_des"  cols=30>' . $row->purpose . '</textarea></td>
				<td>Amount&nbsp;<br/><input type="text" name="reason_amount" class="text" value="' . $row->amount . '"/></td>
				<td><input type="submit" value="update"/><br/><br/>' . $del . '</td></tr></table></form>'
			);
			$sl = $sl + 1;
		}
		return $this->table->generate();
	}


	function addItem($id)
	{


		if ($this->input->post('reason_des') != "") {
			$this->iousupmodel->updateaction($id, $this->input->post('reason_amount'));
			$this->ioumodel->saveItem(
				array(
					'purpose' => $this->input->post('reason_des'),
					'amount' => $this->input->post('reason_amount'),
					'iou_id' => $id
				)
			);

			redirect('bill/iousup/updateIou/' . $id . '/1', 'location');
		} else redirect('bill/iousup/updateIou/' . $id . '/7', 'location');
	}

	function updateItem($id1, $id2)
	{

		if ($this->input->post('reason_des') != "") {
			$this->iousupmodel->updateaction($id2, $this->input->post('reason_amount'));
			$this->ioumodel->updateItem(
				$id2,
				array(
					'purpose' => $this->input->post('reason_des'),
					'amount' => $this->input->post('reason_amount'),
					'iou_id' => $id1
				)
			);

			redirect('bill/iousup/updateIou/' . $id1 . '/1', 'location');
		} else redirect('bill/iousup/updateIou/' . $id1 . '/8', 'location');
	}


	function deleteItem($id1, $id2)
	{

		$this->ioumodel->deleteItem($id1);
		$this->ioumodel->calculate_total($id1);
		redirect('bill/iousup/updateIou/' . $id2, 'location');
	}
}
