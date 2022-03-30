<?php
class GeneralModel extends ci_model
{

	function General()
	{
		parent::__construct();
	}

	function list_company()
	{
		$this->db->where('for_bill', 1);
		return $this->db->get('tb_companylist');
	}

	function list_particular($doc_id)
	{
		return $this->db->query('select id from mis_document_details where doc_id=' . $doc_id);
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

	function saveParticular($data)
	{
		$this->db->insert('mis_document_details', $data);
	}


	function updateParticular($updateId, $data, $totnew)
	{

		$totold = $this->chk_amount($updateId);
		$bill_type = $this->bill_type($updateId);

		if ($bill_type == 'general' and $totnew != $totold) {
			$this->db->query('insert into mis_document_details_history(id,doc_id,new_total,old_total,update_by,update_date,status)
			( select id,doc_id,' . $totnew . ',total,"' . $this->session->userdata('username') . '",now(),"updated" as status from mis_document_details where id=' . $updateId . ')');
		}









		$this->db->where('id', $updateId);
		$this->db->update('mis_document_details', $data);
	}



	function chk_amount($id)
	{
		$sql = $this->db->query("select total from mis_document_details where id=" . $id);

		return $sql->row()->total;
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
	/*
	function get_documents($billId){

		return $this->db->query('select mis_scan.*,mis_document_details.particular from mis_scan
		inner join mis_document_details on mis_document_details.id=mis_scan.detail_id
		where mis_document_details.doc_id='.$billId.' order by detail_id,doc_id asc ');
	
	
	}
		*/
	function get_documents($billId)
	{

		return $this->db->query('select mis_scan.id,mis_scan.doc_file,mis_scan.doc_id,mis_scan.detail_id,mis_document_details.particular,mis_scan.doc_category from mis_scan
		inner join mis_document_details on mis_document_details.id=mis_scan.detail_id
		where mis_document_details.doc_id=' . $billId . ' 
		union

			select mis_requisition_master.id,0 as doc_file ,mis_requisition_master.doc_id,mis_requisition_master.detail_id,mis_document_details.particular,0 as doc_category  from mis_requisition_master
			inner join mis_document_details on mis_document_details.id=mis_requisition_master.detail_id
			where mis_requisition_master.doc_id=' . $billId . ' 
			order by detail_id,doc_id asc 
		
		');
	}


	function get_special_documents($billId)
	{

		return $this->db->query('select mis_scan.id,mis_scan.doc_file,mis_scan.doc_id,mis_scan.detail_id,mis_document_details.particular,mis_scan.doc_category from mis_scan
		inner join mis_document_details on mis_document_details.id=mis_scan.detail_id
		where mis_document_details.doc_id=' . $billId . ' 
		union

			select id,bill_by as doc_file,doc_id,detail_id,req_id as particular,0 as doc_category  from mis_requisiton_map
			where doc_id=' . $billId . ' 
			order by detail_id,doc_id asc 
		
		');
	}

	function get_particular($billId)
	{

		$this->db->select('mis_document_details.*');
		$this->db->where('doc_id', $billId);
		$this->db->order_by('id', 'asc');
		return $this->db->get('mis_document_details');
	}



	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_document.*,d.vEmpName as superviseName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.supervise_by');
		$this->db->where('created_by', $this->session->userdata('username'));
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_document', $limit, $offset);
	}


	function get_search_by_id($from, $to)
	{
		$this->db->select('mis_document.*,d.vEmpName as superviseName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.supervise_by');
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




	function deleteDoc($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('mis_scan');
	}


	function updateSumParticular($id)
	{
		$this->db->query('update mis_document set amount=(select sum(total) from mis_document_details where doc_id=' . $id . ') where id=' . $id);
	}



	function deleteParticular($id)
	{
		$bill_type = $this->bill_type($id);

		if ($bill_type == 'general') {
			$this->db->query('insert into mis_document_details_history(id,doc_id,new_total,old_total,update_by,update_date,status)
			( select id,doc_id,total,total,"' . $this->session->userdata('username') . '",now(),"deleted" as status from mis_document_details where id=' . $id . ')');
		}

		$this->db->where('detail_id', $id);
		$this->db->delete('mis_scan');

		$this->db->where('id', $id);
		$this->db->delete('mis_document_details');
	}

	function bill_type($id)
	{
		$sql = $this->db->query("select bill_type from mis_document where id=(select distinct doc_id from mis_document_details where id=" . $id . ")");

		return $sql->row()->bill_type;
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
	/*
	function doubleFileChk($id){
		$sql=$this->db->query('select count(id) as tot from mis_scan where  doc_file="'.$id.'"');
		return $sql->row()->tot;
	}
	
	
	*/
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


	function requisitionUpdate($bill_id, $pid, $id)
	{
		$sql = $this->db->query('update mis_requisition_master set bill_status=1,doc_id="' . $bill_id . '",detail_id="' . $pid . '" where id="' . $id . '"');
	}

	function requisitionDelete($id)
	{
		$sql = $this->db->query('update mis_requisition_master set bill_status=0,doc_id=0,detail_id=0 where id="' . $id . '"');
	}
}
