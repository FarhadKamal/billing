<?php
class BillModel extends ci_model
{

	function __construct()
	{
		parent::__construct();
	}

	function list_company()
	{
		$this->db->where('for_bill', 1);
		return $this->db->get('tb_companylist');
	}

	function list_vendor()
	{
		$this->db->order_by('vendor_name', 'asc');
		$this->db->where('(vendor_code not like "HL%" and vendor_code not like "HD%")');
		return $this->db->get('mis_vendor');
	}

	function list_costcentre()
	{
		$this->db->order_by('vCostCentre', 'asc');
		return $this->db->get('tb_costcentrelist');
	}

	function list_head()
	{

		$comp = 0;
		$user = $this->session->userdata('username');

		if ($this->session->userdata('authlevel') == 5 or $this->session->userdata('authlevel') == 6) {
			$comp = $this->db->query('select iCompanyId from tb_empinfo where vEmpId="' . $user . '"')->row()->iCompanyId;
		}
		if ($comp == 21 or $comp == 24) {

			$this->db->where('id in (2430,3)');
		}

		if ($comp == 4 or $comp == 12) {

			$this->db->where('id in (1085)');
		}

		if ($comp == 22  or $comp == 23) {

			$this->db->where('id in (2571)');
		}


		$this->db->order_by('Name', 'asc');
		return $this->db->get('reportingofficer');
	}

	function save($data)
	{
		$this->db->insert('mis_document', $data);
	}

	function id()
	{
		$sql = $this->db->query('select count(id) as tot,year(now()) as years,month(now()) as months from mis_document');
		$tot = ($sql->row()->tot) + 1;
		$years = $sql->row()->years;
		$months = $sql->row()->months;
		return $months . $years . $tot;
	}


	function saveDoc($data)
	{
		$this->db->insert('mis_scan', $data);
	}

	function update($updateId, $data)
	{

		$user = $this->session->userdata('username');
		$sct = $this->db->query('select created_by,step_status,hold_by from mis_document where id=' . $updateId)->row();
		if ($sct->step_status > 2 and ($this->session->userdata('authlevel') == 5 or $this->session->userdata('authlevel') == 6))
			show_401();
		else if ($sct->step_status == 2 and $user != '003' and $user != '3'  and ($this->session->userdata('authlevel') == 5 or $this->session->userdata('authlevel') == 6)) {

			if ($sct->hold_by <> $user)
				show_401();
		} else if ($sct->step_status == 1  and  $user <> $sct->created_by) {
			show_401();
		}

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

	function get_last_entry_id($bill_date)
	{
		$sql = $this->db->query("select id from mis_document
		where bill_date='" . $bill_date . "' and created_by=" . $this->session->userdata('username') . " 
		order by id desc limit 1");
		return $sql->row()->id;
	}



	function get_documents($billId)
	{

		$this->db->select('mis_scan.*');
		$this->db->where('doc_id', $billId);
		$this->db->order_by('detail_id', 'asc');
		$this->db->order_by('id', 'asc');
		return $this->db->get('mis_scan');
	}

	function get_documents_requisition($billId)
	{

		return $this->db->query('select mis_scan.id,mis_scan.doc_file,mis_scan.doc_id,mis_scan.detail_id,mis_scan.doc_category from mis_scan
		where doc_id=' . $billId . ' 
		union

			select id,0 as doc_file ,doc_id,detail_id,0 as doc_category from mis_requisition_master
			where doc_id=' . $billId . ' 
			order by detail_id,doc_id asc 
		
		');
	}


	function get_special_documents($billId)
	{

		return $this->db->query('select mis_scan.id,mis_scan.doc_file,mis_scan.doc_id,mis_scan.detail_id,mis_scan.doc_category from mis_scan

		where mis_scan.doc_id=' . $billId . ' 
		union

			select id,bill_by as doc_file,doc_id,req_id as detail_id,0 as doc_category  from mis_requisiton_map
			where doc_id=' . $billId . ' 
			order by detail_id,doc_id asc 
		
		');
	}









	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_document.*');

		$this->db->where('created_by', $this->session->userdata('username'));
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document', $limit, $offset);
	}


	function get_search_by_id($from, $to)
	{
		$this->db->select('mis_document.*');

		$this->db->where('created_by', $this->session->userdata('username'));
		$this->db->where('bill_date >=', $from);
		$this->db->where('bill_date <=', $to);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}



	function count_all()
	{
		$this->db->from('mis_document');
		$this->db->where('created_by', $this->session->userdata('username'));
		return $this->db->count_all_results();
	}

	function count_search($from, $to)
	{
		$this->db->from('mis_document');
		$this->db->where('created_by', $this->session->userdata('username'));
		$this->db->where('bill_date >=', $from);
		$this->db->where('bill_date <=', $to);
		return $this->db->count_all_results();
	}



	function get_special_search_by_id($stype, $svalue)
	{
		$this->db->select('mis_document.*');
		$this->db->where('created_by', $this->session->userdata('username'));
		$this->db->like($stype, $svalue);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}


	function count_search_special($stype, $svalue)
	{
		$this->db->from('mis_document');
		$this->db->where('created_by', $this->session->userdata('username'));
		$this->db->like($stype, $svalue);
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


	function get_holdby($holdby)
	{
		if ($holdby == "3")
			$holdby = "003";

		if ($holdby == "audit")
			return "Audit";

		if ($holdby == "account")
			return "Account";

		if ($holdby == "finance")
			return "Finance";

		if ($holdby == "fin")
			return "Finance";

		if ($holdby == "claimer")
			return "";


		$sql = $this->db->query("select vEmpName from tb_empinfo where vEmpId='" . $holdby . "'");
		return $sql->row()->vEmpName;
	}



	function action_doc($data)
	{
		$this->db->insert('mis_document_action', $data);
	}

	function submitSupervisor($id)
	{

		$user = $this->session->userdata('username');
		$sct = $this->db->query('select created_by,step_status,hold_by from mis_document where id=' . $id)->row();
		if ($sct->step_status > 1)
			show_401();
		else if ($sct->step_status == 1  and  $user <> $sct->created_by) {
			show_401();
		}


		$this->db->query('update mis_document set step_status=2,hold_by=supervise_by where id=' . $id);
		$IPTrack = $_SERVER['REMOTE_ADDR'];
		$this->action_doc(
			array(
				'doc_id' => $id, 'action_ip' => $IPTrack, 'action' => 'Submit', 'user_id' => $this->session->userdata('username')
			)
		);
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
			return	$sql->row()->tot;
		} else {
			$another_chk = $this->db->query('
			select count(mis_document.id) as tot  from mis_document 
			inner join mis_scan on mis_scan.doc_id=mis_document.id
			where (audit_cancel=0 and authority_cancel=0 and supervise_cancel=0) 
			and doc_file="' . $id . '"')->row();
			$chkrtn = $another_chk->tot;

			if ($chkrtn == 0) return	$chkrtn;
			else return	2;
		}
	}

	function requisitionChk($id)
	{
		$sql = $this->db->query('select count(id) as tot from mis_requisition_master where id="' . $id . '" and request_by="' . $this->session->userdata('username') . '"
		and bill_status=0');
		return $sql->row()->tot;
	}

	function requisitionUpdate($bill_id, $id)
	{
		$sql = $this->db->query('update mis_requisition_master set bill_status=1,doc_id="' . $bill_id . '",detail_id=0 where id="' . $id . '"');
	}

	function requisitionDelete($id)
	{
		$sql = $this->db->query('update mis_requisition_master set bill_status=0,doc_id=0,detail_id=0 where id="' . $id . '"');
	}
}
