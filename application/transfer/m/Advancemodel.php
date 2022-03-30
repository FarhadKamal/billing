<?php
class AdvanceModel extends ci_model
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

	function list_particular($advanceId)
	{
		return $this->db->query('select id from mis_advance_details where advance_id=' . $advanceId);
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
		$comp = 0;
		$user = $this->session->userdata('username');

		$this->db->order_by('Name', 'asc');
		$this->db->where('id<>3');

		if ($this->session->userdata('authlevel') == 5 or $this->session->userdata('authlevel') == 6) {
			$comp = $this->db->query('select iCompanyId from tb_empinfo where vEmpId="' . $user . '"')->row()->iCompanyId;
		}

		if ($comp == 4 or $comp == 12) {

			$this->db->where('id in (1085)');
		}

		if ($comp == 22  or $comp == 23) {

			$this->db->where('id in (2527)');
		}

		return $this->db->get('reportingofficer');
	}

	function save($data)
	{
		$this->db->insert('mis_advance', $data);
	}



	function saveDoc($data)
	{
		$this->db->insert('mis_advance_scan', $data);
	}

	function saveParticular($data)
	{
		$this->db->insert('mis_advance_details', $data);
	}


	function updateParticular($updateId, $data)
	{

		$this->db->where('id', $updateId);
		$this->db->update('mis_advance_details', $data);
	}



	function chk_amount($id)
	{
		$sql = $this->db->query("select total from mis_advance_details where id=" . $id);

		return $sql->row()->total;
	}





	function update($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_advance', $data);
	}


	function list_district()
	{
		return $this->db->get('listdistrict');
	}

	function list_model()
	{
		return $this->db->get('pump_model');
	}

	function get_last_entry_id($advance_date)
	{
		$sql = $this->db->query("select id from mis_advance
		where advance_date='" . $advance_date . "' and req_by=" . $this->session->userdata('username') . " 
		order by id desc limit 1");
		return $sql->row()->id;
	}

	function get_documents($advanceId)
	{

		return $this->db->query('select mis_advance_scan.*,mis_advance_details.partcular_details from mis_advance_scan
		inner join mis_advance_details on mis_advance_details.id=mis_advance_scan.advance_details_id
		where mis_advance_details.advance_id=' . $advanceId . ' 
	
			order by mis_advance_details.id asc  
		
		');
	}


	function get_special_documents($advanceId)
	{

		return $this->db->query('select mis_advance_scan.*,mis_advance_details.partcular_details from mis_advance_scan
		inner join mis_advance_details on mis_advance_details.id=mis_advance_scan.advance_details_id
		where mis_advance_details.advance_id=' . $advanceId . ' 
	
			order by mis_advance_details.id asc 
		
		');
	}

	function get_particular($advance_id)
	{

		$this->db->select('mis_advance_details.*');
		$this->db->where('advance_id', $advance_id);
		$this->db->order_by('id', 'asc');
		return $this->db->get('mis_advance_details');
	}



	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_advance.*,d.vEmpName as superviseName');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->where('req_by', $this->session->userdata('username'));
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance', $limit, $offset);
	}


	function get_search_by_id($from, $to)
	{
		$this->db->select('mis_advance.*,d.vEmpName as superviseName');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->where('req_by', $this->session->userdata('username'));
		$this->db->where('advance_date >=', $from);
		$this->db->where('advance_date <=', $to);
		$this->db->like($this->input->post('so'), $this->input->post('sv'));
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance');
	}



	function count_all()
	{
		$this->db->from('mis_advance');
		$this->db->where('req_by', $this->session->userdata('username'));
		return $this->db->count_all_results();
	}

	function count_search($from, $to)
	{
		$this->db->from('mis_advance');
		$this->db->where('req_by', $this->session->userdata('username'));
		$this->db->where('advance_date >=', $from);
		$this->db->where('advance_date <=', $to);
		$this->db->like($this->input->post('so'), $this->input->post('sv'));
		return $this->db->count_all_results();
	}




	function deleteDoc($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('mis_advance_scan');
	}


	function updateSumParticular($id)
	{
		$this->db->query('update mis_advance set amount=(select sum(particular_amount) from mis_advance_details where advance_id=' . $id . ') where id=' . $id);
	}



	function deleteParticular($id)
	{

		$this->db->where('advance_details_id', $id);
		$this->db->delete('mis_advance_scan');

		$this->db->where('id', $id);
		$this->db->delete('mis_advance_details');
	}




	function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('mis_advance');
	}


	function tot_document_by_bill_id($id)
	{
		$sql = $this->db->query("select count(id) as tot from mis_advance_scan where advance_id=" . $id);
		return $sql->row()->tot;
	}

	function submitSupervisor($id)
	{
		$this->db->query('update mis_advance set step_status=2,hold_by=supervised_by where step_status = 1 and amount>0 and id=' . $id);
		$this->db->query('update mis_advance set step_status=2,hold_by=1085,supervised_by=1085 where step_status = 2 and amount>0
		and company in (4,12) and id=' . $id);
	}

	function doubleFileChk($id)
	{

		$rtn = 0;
		$cancel_chk = $this->db->query('
		select count(mis_advance.id) as tot  from mis_advance 
		inner join mis_advance_scan on mis_advance_scan.advance_id=mis_advance.id
		where cancel_staus=1
		and doc_file="' . $id . '"')->row();
		$rtn = $cancel_chk->tot;




		if ($rtn == 0) {
			$sql = $this->db->query('select count(id) as tot from mis_advance_scan where  doc_file="' . $id . '"');
			return	$sql->row()->tot;
		} else {
			$another_chk = $this->db->query('
			select count(mis_advance.id) as tot  from mis_advance 
			inner join mis_advance_scan on mis_advance_scan.advance_id=mis_advance.id
			where cancel_staus=1
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


	function requisitionUpdate($bill_id, $pid, $id)
	{
		$sql = $this->db->query('update mis_requisition_master set bill_status=1,advance_id="' . $bill_id . '",detail_id="' . $pid . '" where id="' . $id . '"');
	}

	function requisitionDelete($id)
	{
		$sql = $this->db->query('update mis_requisition_master set bill_status=0,advance_id=0,detail_id=0 where id="' . $id . '"');
	}
}
