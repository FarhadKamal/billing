<?php
class BillcostModel extends ci_model
{

	function __construct()
	{
		parent::__construct();
	}

	function list_company()
	{
		return $this->db->get('tb_companylist');
	}

	function list_vendor()
	{
		return $this->db->get('mis_vendor');
	}

	function list_costcentre()
	{
		$this->db->order_by('vCostCentre', 'asc');
		return $this->db->get('tb_costcentrelist');
	}

	function action_doc($data)
	{
		$this->db->insert('mis_document_action', $data);
	}

	function list_head()
	{
		return $this->db->get('reportingofficer');
	}

	function Cheque_ready($id)
	{
		$this->db->query('update mis_document set check_ready=1 where id=' . $id);
	}


	function chk_duplicate_bill_payment($id)
	{

		$user = $this->session->userdata('username');
		$sql = $this->db->query("select count(id) as tot from mis_document_action  where user_id='" . $user . "' and action='sap entry' and doc_id=" . $id);
		return $sql->row()->tot;
	}

	function chk_duplicate_advance_payment($id)
	{

		$user = $this->session->userdata('username');
		$sql = $this->db->query("select count(id) as tot from mis_advance_remarks  where user_id='" . $user . "' and str_action='Payment Made'  and advance_id=" . $id);
		return $sql->row()->tot;
	}






	function cost_center($id)
	{
		$query = "select remark,sap_id,vendor,cost_id,account_head,cost_text,mis_internal_order.Order,area,Description,divide_amount,vCompany,profit_center from mis_document_account 
		left join tb_companylist on tb_companylist.iId=mis_document_account.company_id
		left join mis_area on mis_area.id=mis_document_account.business_area
		left join mis_internal_order on mis_internal_order.Order=mis_document_account.internal_order
		left join mis_document_cost on mis_document_cost.iId=mis_document_account.costcenter_id
		where doc_id='" . $id . "'";
		return $this->db->query($query);
	}

	function update($updateId, $data)
	{

		$this->db->where('id', $updateId);
		$this->db->update('mis_document', $data);
	}


	function updatePaymentDate($updateId)
	{

		$this->db->query("update mis_document set payment_made_date=now() where id='" . $updateId . "'");
	}


	function list_district()
	{
		return $this->db->get('listdistrict');
	}

	function list_model()
	{
		return $this->db->get('pump_model');
	}








	function get_documents($billId)
	{

		$this->db->select('mis_scan.*');
		$this->db->where('doc_id', $billId);
		$this->db->order_by('detail_id', 'asc');
		$this->db->order_by('id', 'asc');
		return $this->db->get('mis_scan');
	}



	function get_paged_list($type)
	{
		$this->db->select(' mis_document.*,d.displayname as empName');
		$this->db->join('users d', 'd.username=mis_document.created_by');
		$this->db->where('step_status=', 5);
		$this->db->where('payment_made_status=', 0);
		$this->db->where('payment_type=', $type);
		if ($type == 'Cash' && strtolower($this->session->userdata('username')) != 'scost') {
			$this->db->where('mis_document.loc=', $this->getloc());
		}
		if (strtolower($this->session->userdata('username')) == 'pdhlcost') {
			$this->db->where('company_id in(14,9)');
		}

		if (strtolower($this->session->userdata('username')) == 'azcost') {
			$this->db->where('company_id in(18)');
		}

		if (strtolower($this->session->userdata('username')) == 'gepcost') {
			$this->db->where('company_id in(6)');
		}

		if (strtolower($this->session->userdata('username')) == 'scost') {
			$this->db->where('company_id in(21,24)');
		}

		if (strtolower($this->session->userdata('username')) == 'hillcost') {
			$this->db->where('company_id in(4,12,22,23)');
		}

		if ($type == 'Cheque')
			$this->db->where('(cheque_date is not null or bill_date<="2013-06-15")');
		if (strtolower($this->session->userdata('username')) != 'pdhlcost' && strtolower($this->session->userdata('username')) != 'gepcost' && strtolower($this->session->userdata('username')) != 'scost' && strtolower($this->session->userdata('username')) != 'hillcost'  && strtolower($this->session->userdata('username')) != 'azcost')
			$this->db->where('company_id not in(4,6,14,12,9,18,21,22,23,24)');
		$this->db->order_by('company_id', 'asc');


		return $this->db->get('mis_document');
	}




	function getloc()
	{
		return $this->db->query("select loc from users where username='" . $this->session->userdata('username') . "'")->row()->loc;
	}

	function pending_date_list()
	{
		$user = $this->session->userdata('username');

		$this->db->select('mis_document.*,d.vEmpName as empName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		$this->db->where('step_status=', 5);
		$this->db->where('agm_park=', 0);
		$this->db->where('payment_type=', 'Cheque');
		$this->db->where('cheque_date is null and bill_date>"2013-06-15"');

		if ($user == 2346)
			$this->db->where('company_id in (2,8,14,9,5,10,4,12,1,3,22,23)');

		if ($user == 2405)
			$this->db->where('company_id in (7,6,21,18,24)');
		//else if($user==2034)
		//$this->db->where('company_id in (1,3,5,7,10)');

		$this->db->order_by('company_id', 'asc');
		return $this->db->get('mis_document');
	}


	function get_agm_park_list()
	{
		$this->db->select('mis_document.*,d.vEmpName as empName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		$this->db->where('step_status=', 5);
		$this->db->where('agm_park=', 1);
		$this->db->where('payment_type=', 'Cheque');
		$this->db->where('cheque_date is null and bill_date>"2013-06-15"');
		$this->db->order_by('company_id', 'asc');
		return $this->db->get('mis_document');
	}


	function get_search_by_id($from, $to)
	{
		$this->db->select('mis_document.*,d.vEmpName as empName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		$this->db->where('step_status=', 5);
		$this->db->where('bill_date >=', $from);
		$this->db->where('bill_date <=', $to);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}



	function count_all()
	{
		$this->db->from('mis_document');
		$this->db->where('step_status=', 5);
		return $this->db->count_all_results();
	}




	function deleteDoc($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('mis_scan');
	}


	function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('mis_document');
	}


	function tot_document_by_bill_id($id)
	{
		$sql = $this->db->query("select count(id) as tot from mis_scan where doc_id=" . $id);
		return $sql->row()->tot;
	}

	function submitAccounts($id)
	{
		$this->db->query('update mis_document set step_status=5 where id=' . $id);
	}

	function cancel($id)
	{
		$this->db->query('update mis_document set audit_cancel=1 where id=' . $id);
	}

	function saveDoc($data)
	{
		$this->db->insert('mis_scan', $data);
	}


	function iou_list()
	{

		$this->db->select('mis_iou.*,a.vEmpName as createdName,b.vEmpName as deptName,c.vEmpName as agmName');
		$this->db->join('tb_empinfo a', 'a.vEmpId=mis_iou.req_by', 'left');

		$this->db->join('tb_empinfo b', 'b.vEmpId=mis_iou.dep_accept_by', 'left');
		$this->db->join('tb_empinfo c', 'c.vEmpId=mis_iou.hold_by', 'left');
		$this->db->where('step_status', 4);
		if (strtolower($this->session->userdata('username')) == 'pdhlcost') {
			$this->db->where('company in(4,14,12,9,22,23)');
		} else if (strtolower($this->session->userdata('username')) == 'azcost') {
			$this->db->where('company in(18)');
		} else if (strtolower($this->session->userdata('username')) == 'scost') {
			$this->db->where('company in(21,24)');
		} else if (strtolower($this->session->userdata('username')) == 'hillcost') {
			$this->db->where('company in(4,12,22,23)');
		} else 	$this->db->where('company  not in(4,14,12,9,18,22,23)');


		if (strtolower($this->session->userdata('username')) == 'dhkcost') {
			$this->db->where('a.vLocations', 'Dhaka H/O');
		}

		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_iou');
	}

	function completeIou($id)
	{
		$this->db->query('update mis_iou set step_status=5 , payment_date=now() where id=' . $id);
	}


	function iou_by_emp($emp)
	{
		return $this->db->query('select * from 
		(select a.id,a.amount,a.purpose,
		if((select sum(b.amount) from mis_iou_settlement b where b.iou_id=a.id)is null,0,
		(select sum(b.amount) from mis_iou_settlement b where b.iou_id=a.id)) as setamount
		 from mis_iou a where req_by=' . $emp . ') as details where setamount<>amount')->result();
	}

	function iou_set_entry($iou, $amount)
	{
		$this->db->query('insert into mis_iou_settlement (iou_id,amount) values(' . $iou . ',' . $amount . ')');
	}
}
