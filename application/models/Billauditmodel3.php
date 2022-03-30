<?php
class BillauditModel extends ci_model
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
		$this->db->order_by('vendor_name', 'asc');
		return $this->db->get('mis_vendor');
	}

	function list_costcentre()
	{
		$this->db->order_by('vCostCentre', 'asc');
		return $this->db->get('tb_costcentrelist');
	}

	function list_head()
	{
		return $this->db->get('reportingofficer');
	}


	function action_doc($data)
	{
		$this->db->insert('mis_document_action', $data);
	}

	function update($updateId, $data)
	{

		$newamount = ($data['amount']);
		$sql = $this->db->query("select amount from mis_document where id=" . $updateId);
		$oldamount = $sql->row()->amount;
		if ($newamount != $oldamount)
			$this->db->query("insert into mis_document_history(select id,bill_date,amount," . $newamount . " as oldamount,created_by,hold_by,now() from mis_document where id=" . $updateId . ")");

		$this->db->where('id', $updateId);
		$this->db->update('mis_document', $data);
	}


	function updatepark($updateId, $data)
	{

		$this->db->where('id', $updateId);
		$this->db->update('mis_document', $data);
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


	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_document.*');

		$this->db->where('step_status>=', 3);
		$this->db->where('park_status', 0);
		if (strtolower($this->session->userdata('username')) == 'faiz') {
			$this->db->where('company_id in(4,12,22,23)');
		} else $this->db->where('company_id not in(4,12,22,23)');


		$this->db->order_by('step_status', 'asc');
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document', $limit, $offset);
	}



	function get_park_list()
	{
		$this->db->select('mis_document.*,d.vEmpName as empName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		$this->db->where('step_status>=', 3);
		if (strtolower($this->session->userdata('username')) == 'faiz') {
			$this->db->where('company_id in(4,12,22,23)');
		} else $this->db->where('company_id not in(4,12,22,23)');
		$this->db->where('park_status', 1);
		$this->db->where('supervise_cancel', 0);
		$this->db->where('authority_cancel', 0);
		$this->db->where('audit_cancel', 0);
		$this->db->order_by('step_status', 'asc');
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}


	function get_search_by_id($from, $to, $loc)
	{
		$this->db->select('mis_document.*');

		$this->db->where('step_status>=', 3);
		$this->db->where('park_status', 0);
		if (strtolower($this->session->userdata('username')) == 'faiz') {
			$this->db->where('company_id in(4,12,22,23)');
		} else $this->db->where('company_id not in(4,12,22,23)');
		$this->db->where('bill_date >=', $from);
		$this->db->where('bill_date <=', $to);
		$this->db->where('loc=', $loc);
		$this->db->order_by('step_status', 'asc');
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}

	function get_special_search_by_id($stype, $svalue)
	{
		$this->db->select('mis_document.*,d.vEmpName as empName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		$this->db->where('step_status>=', 3);
		$this->db->where('park_status', 0);
		if (strtolower($this->session->userdata('username')) == 'faiz') {
			$this->db->where('company_id in(4,12,22,23)');
		} else $this->db->where('company_id not in(4,12,22,23)');
		$this->db->like($stype, $svalue);

		$this->db->order_by('step_status', 'asc');
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}

	function count_search_special($stype, $svalue)
	{
		$this->db->from('mis_document');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		$this->db->where('step_status>=', 3);
		$this->db->where('park_status', 0);
		if (strtolower($this->session->userdata('username')) == 'faiz') {
			$this->db->where('company_id in(4,12,22,23)');
		} else $this->db->where('company_id not in(4,12,22,23)');
		$this->db->like($stype, $svalue);
		return $this->db->count_all_results();
	}



	function count_all()
	{
		$this->db->from('mis_document');
		$this->db->where('step_status>=', 3);
		$this->db->where('park_status', 0);
		if (strtolower($this->session->userdata('username')) == 'faiz') {
			$this->db->where('company_id in(4,12,22,23)');
		} else $this->db->where('company_id not in(4,12,22,23)');
		return $this->db->count_all_results();
	}

	function count_search($from, $to, $loc)
	{
		$this->db->from('mis_document');
		$this->db->where('step_status>=', 3);
		$this->db->where('park_status', 0);
		if (strtolower($this->session->userdata('username')) == 'faiz') {
			$this->db->where('company_id in(4,12,22,23)');
		} else $this->db->where('company_id not in(4,12,22,23)');
		$this->db->where('bill_date >=', $from);
		$this->db->where('bill_date <=', $to);
		$this->db->where('loc=', $loc);
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
		$this->db->query("update mis_document set step_status=5,park_status=0,authority_by='" . $this->session->userdata('username') . "'  where id=" . $id);
	}

	function cancel($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_document', $data);
	}

	function saveDoc($data)
	{
		$this->db->insert('mis_scan', $data);
	}

	function FinalSubmit($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_document', $data);
	}

	function ReSubmitEmp($id)
	{

		$this->db->query('update mis_document set hold_by=supervise_by,az_status =0,park_status=0,step_status=1 where id=' . $id);
	}

	function ReSubmitDirector($id)
	{

		$this->db->query('update mis_document set hold_by=3,super_authority_by=3,park_status=0,step_status=2 where id=' . $id);
	}

	function SubmitFin($id)
	{

		$this->db->query('update mis_document set hold_by=2346,finance_head_by=2346,park_status=0,step_status=2,az_status=1,hill_status=1 where id=' . $id);
	}


	function SubmitFinNew($id)
	{

		$this->db->query('update mis_document set hold_by=2405,finance_head_by=2405,park_status=0,step_status=2,az_status=1 where id=' . $id);
	}



	function ReSubmitDep($id)
	{

		$this->db->query('update mis_document set hold_by=supervise_by,park_status=0,az_status =0,step_status=2 where id=' . $id);
	}

	function doubleFileChk($id)
	{

		$rtn = 0;
		$cancel_chk = $this->db->query('
		select count(mis_document.id) as tot  from mis_document 
		inner join mis_scan on mis_scan.doc_id=mis_document.id
		where (audit_cancel=1 or authority_cancel=1 or supervise_cancel=1) 
		and doc_file="' . $id . '"')->row();
		$rtn = $cancel_chk->tot;

		if ($rtn == 0) {
			$sql = $this->db->query('select count(id) as tot from mis_scan where  doc_file="' . $id . '"');
			return	$rtn = $sql->row()->tot;
		} else 0;
	}


	function last_comment($id)
	{
		return	$this->db->query('select concat(comment," <b>",action," by:</b> ",displayname) as last_comment from mis_document_action
		inner join users on users.username=mis_document_action.user_id where doc_id=' . $id . ' order by id desc  limit 1');
	}
}
