<?php
class AdminModel extends ci_model
{

	function __construct()
	{
		parent::__construct();
	}

	function list_employeeid()
	{
		$this->db->select('vEmpId,concat(vEmpId,' . "'#'" . ',vEmpName) as vEmpName');
		return $this->db->get('tb_empinfo');
	}

	function list_company()
	{
		return $this->db->get('tb_companylist');
	}

	function get_company_by_id($id)
	{

		$this->db->where('iId', $id);
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

	function list_head()
	{
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


	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_document.*,tb_companylist.vCompany');
		$this->db->join('tb_companylist', 'tb_companylist.iId=mis_document.company_id', 'join');
		$this->db->where('step_status >', 1);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document', $limit, $offset);
	}




	function get_search_by_id($from, $to, $status, $loc, $company)
	{
		$this->db->select('mis_document.*,d.vEmpName as superviseName,e.vEmpName as empName,tb_companylist.vCompany');
		$this->db->join('tb_companylist', 'tb_companylist.iId=mis_document.company_id', 'join');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.supervise_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_document.created_by', 'left');
		if ($status == 1) {
			$this->db->where('audit_cancel=', 0);
			$this->db->where('supervise_cancel=', 0);
			$this->db->where('authority_cancel=', 0);
			//$this->db->where('payment_made_status=', 0);
			$this->db->where('(if(payment_type<>"Adjustment",payment_made_status=0,step_status<6))');
			$this->db->where('step_status>=', 2);
		} else if ($status == 2) {
			$this->db->where('audit_cancel=', 0);
			$this->db->where('supervise_cancel=', 0);
			$this->db->where('authority_cancel=', 0);
			$this->db->where('step_status=', 3);
		} else if ($status == 3) {
			$this->db->where('audit_cancel=', 0);
			$this->db->where('supervise_cancel=', 0);
			$this->db->where('authority_cancel=', 0);
			$this->db->where('step_status=', 4);
			$this->db->where('account_head_pass=', 0);
		} else if ($status == 4) {
			$this->db->where('audit_cancel=', 0);
			$this->db->where('supervise_cancel=', 0);
			$this->db->where('authority_cancel=', 0);
			$this->db->where('step_status=', 4);
			$this->db->where('account_head_pass=', 0);
		} else if ($status == 5) {

			$this->db->where('step_status=', 5);
		} else if ($status == 6) {

			$this->db->where('step_status=', 6);
		} else if ($status == 13) {

			$this->db->where('supervise_cancel=1 or authority_cancel=1 or audit_cancel=1');
		} else if ($status == 14) {

			$this->db->where('step_status>', 1);
		}





		$this->db->where('date(bill_date) >=', $from);
		$this->db->where('date(bill_date) <=', $to);
		$this->db->where('step_status >', 1);
		if ($company > 0)
			$this->db->where('company_id=', $company);
		if ($loc > 0)
			$this->db->where('loc=', $loc);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}

	function get_search_by_id_by_emp($empid)
	{
		$this->db->select('mis_document.*,tb_companylist.vCompany');
		$this->db->join('tb_companylist', 'tb_companylist.iId=mis_document.company_id', 'join');
		$this->db->where('step_status >', 1);
		$this->db->like('created_by', $empid);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}

	function get_search_by_amount($amtfrom, $amtto)
	{
		$this->db->select('mis_document.*,tb_companylist.vCompany');
		$this->db->join('tb_companylist', 'tb_companylist.iId=mis_document.company_id', 'join');
		$this->db->where('(amount >=' . $amtfrom . ' and amount <=' . $amtto . ')');
		$this->db->where('step_status >', 1);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}

	function count_all()
	{
		$this->db->from('mis_document');
		$this->db->where('step_status >', 1);
		return $this->db->count_all_results();
	}


	function count_search($from, $to, $status, $loc, $company)
	{
		$this->db->from('mis_document');
		if ($status == 1) {
			$this->db->where('audit_cancel=', 0);
			$this->db->where('supervise_cancel=', 0);
			$this->db->where('authority_cancel=', 0);
			$this->db->where('step_status=', 2);
		} else if ($status == 2) {
			$this->db->where('audit_cancel=', 0);
			$this->db->where('supervise_cancel=', 0);
			$this->db->where('authority_cancel=', 0);
			$this->db->where('step_status=', 3);
		} else if ($status == 3) {
			$this->db->where('audit_cancel=', 0);
			$this->db->where('supervise_cancel=', 0);
			$this->db->where('authority_cancel=', 0);
			$this->db->where('step_status=', 4);
			$this->db->where('account_head_pass=', 0);
		} else if ($status == 4) {
			$this->db->where('audit_cancel=', 0);
			$this->db->where('supervise_cancel=', 0);
			$this->db->where('authority_cancel=', 0);
			$this->db->where('step_status=', 4);
			$this->db->where('account_head_pass=', 0);
		} else if ($status == 5) {

			$this->db->where('step_status=', 5);
		} else if ($status == 6) {

			$this->db->where('step_status=', 6);
		} else if ($status == 13) {

			$this->db->where('supervise_cancel=1 or authority_cancel=1 or audit_cancel=1');
		}





		$this->db->where('date(bill_date) >=', $from);
		$this->db->where('date(bill_date) <=', $to);
		$this->db->where('step_status >', 1);
		$this->db->where('company_id=', $company);
		$this->db->where('loc=', $loc);
		return $this->db->count_all_results();
	}


	function count_search_by_emp($empid)
	{
		$this->db->from('mis_document');

		$this->db->where('step_status >', 1);
		$this->db->like('created_by', $empid);
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

	function submitSupervisor($id)
	{
		$this->db->query('update mis_document set step_status=2,hold_by=supervise_by where id=' . $id);
	}

	function get_special_search_by_id($stype, $svalue)
	{

		$svalue = strtoupper($svalue);

		$pieces = explode("**", $svalue);

		$x = count($pieces);



		$item = explode("***", $svalue);

		$y = count($item);




		$this->db->select('mis_document.*,d.vEmpName as empName,e.vEmpName as superviseName,tb_companylist.vCompany');
		$this->db->join('tb_companylist', 'tb_companylist.iId=mis_document.company_id', 'join');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_document.supervise_by', 'left');
		$this->db->where('step_status>=', 2);
		if ($y == 1) {
			for ($i = 0; $i < $x; $i++) {
				if ($i == 0)
					$this->db->like('UPPER(' . $stype . ')', $pieces[$i]);
				else
					$this->db->or_like('UPPER(' . $stype . ')', $pieces[$i]);
			}
		} else {

			for ($i = 0; $i < $y; $i++) {
				$this->db->like('UPPER(' . $stype . ')', $item[$i]);
			}
		}
		$this->db->order_by('step_status', 'asc');
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}

	function count_search_special($stype, $svalue)
	{
		$svalue = strtoupper($svalue);

		$pieces = explode("**", $svalue);

		$x = count($pieces);



		$item = explode("***", $svalue);

		$y = count($item);
		$this->db->from('mis_document');

		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_document.supervise_by', 'left');
		$this->db->where('step_status>', 1);
		//$this->db->like('UPPER('.$stype.')', $svalue);
		if ($y == 1) {
			for ($i = 0; $i < $x; $i++) {
				if ($i == 0)
					$this->db->like('UPPER(' . $stype . ')', $pieces[$i]);
				else
					$this->db->or_like('UPPER(' . $stype . ')', $pieces[$i]);
			}
		} else {

			for ($i = 0; $i < $y; $i++) {
				$this->db->like('UPPER(' . $stype . ')', $item[$i]);
			}
		}
		return $this->db->count_all_results();
	}


	function all_material_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_requisition_master.*,d.vEmpName as createdName,a.vEmpName as approveName,c.vEmpName as assignName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_requisition_master.request_by');
		$this->db->join('tb_empinfo a', 'a.vEmpId=mis_requisition_master.approve_by');
		$this->db->join('tb_empinfo c', 'c.vEmpId=mis_requisition_master.assigned_person', 'left');
		$this->db->where('submit_status', 1);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_requisition_master', $limit, $offset);
	}


	function get_requisition_search($stype, $svalue)
	{
		$this->db->select('mis_requisition_master.*,d.vEmpName as createdName,a.vEmpName as approveName,c.vEmpName as assignName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_requisition_master.request_by');
		$this->db->join('tb_empinfo a', 'CAST(a.vEmpId as SIGNED)=mis_requisition_master.approve_by');
		$this->db->join('tb_empinfo c', 'c.vEmpId=mis_requisition_master.assigned_person', 'left');
		$this->db->where('submit_status', 1);
		$this->db->like($stype, $svalue);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_requisition_master');
	}

	function count_material()
	{
		$this->db->from('mis_requisition_master');
		$this->db->where('submit_status', 1);
		return $this->db->count_all_results();
	}


	function get_paged_payment_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_document.*,e.vEmpName as empName,a.date as paydate');

		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_document.created_by', 'left');
		$this->db->join('mis_document_action a', '(a.doc_id=mis_document.id and a.action="sap entry")', 'inner');
		$this->db->where('step_status', 6);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document', $limit, $offset);
	}

	function count_payment_all()
	{
		$this->db->from('mis_document');
		$this->db->where('step_status', 6);
		return $this->db->count_all_results();
	}


	function count_payment_search($from, $to, $loc, $payment_type)
	{
		$this->db->select('mis_document.*,e.vEmpName as empName,a.date as paydate');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_document.created_by', 'left');
		$this->db->join('mis_document_action a', '(a.doc_id=mis_document.id and a.action="sap entry")', 'inner');
		$this->db->where('step_status', 6);
		$this->db->where('loc=', $loc);
		$this->db->where('payment_type', $payment_type);
		$this->db->where('date(a.date) >=', $from);
		$this->db->where('date(a.date) <=', $to);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document');
	}


	function count_search_payment_all($from, $to, $loc, $payment_type)
	{
		$this->db->from('mis_document');
		$this->db->join('mis_document_action a', '(a.doc_id=mis_document.id and a.action="sap entry")', 'inner');
		$this->db->where('step_status', 6);
		$this->db->where('loc=', $loc);
		$this->db->where('payment_type', $payment_type);
		$this->db->where('date(a.date) >=', $from);
		$this->db->where('date(a.date) <=', $to);
		return $this->db->count_all_results();
	}












	function get_paged_list_advance_payment($limit = 10, $offset = 0)
	{

		$this->db->select('mis_advance.*,d.vEmpName as superviseName,e.vEmpName as createdName,r.action_date as payment_date');
		$this->db->join('mis_advance_remarks r', '(r.advance_id=mis_advance.id and r.str_action="Payment Made") ', 'left');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_advance.req_by', 'left');
		$this->db->where('step_status', 5);

		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance', $limit, $offset);
	}


	function get_search_by_id_advance_payment($from, $to)
	{

		$this->db->select('mis_advance.*,d.vEmpName as superviseName,e.vEmpName as createdName,r.action_date as payment_date');
		$this->db->join('mis_advance_remarks r', '(r.advance_id=mis_advance.id and r.str_action="Payment Made") ', 'left');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_advance.req_by', 'left');
		$this->db->where('step_status', 5);

		$this->db->where('date(r.action_date) >=', $from);
		$this->db->where('date(r.action_date) <=', $to);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance');
	}



	function count_all_advance_payment()
	{

		$this->db->from('mis_advance');
		$this->db->where('step_status', 5);

		return $this->db->count_all_results();
	}

	function count_search_advance_payment($from, $to)
	{

		$this->db->from('mis_advance');
		$this->db->join('mis_advance_remarks r', '(r.advance_id=mis_advance.id and r.str_action="Payment Made") ', 'left');
		$this->db->where('step_status', 5);

		$this->db->where('date(r.action_date) >=', $from);
		$this->db->where('date(r.action_date) <=', $to);
		return $this->db->count_all_results();
	}
}
