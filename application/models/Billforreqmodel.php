<?php
class BillforreqModel extends ci_model
{

	function __construct()
	{
		parent::__construct();
	}

	function list_requisition()
	{
		$this->db->where('approve_status', 1);
		$this->db->where('procurement_pass', 1);
		$this->db->where('assigned_person', $this->session->userdata('username'));
		$this->db->where('bill_status', 0);
		return $this->db->get('mis_requisition_master');
	}



	function get_item_by_req($req)
	{
		return $this->db->query('select id,req_sap_id,master_id,item_name,qty_req,if(qty is null,0,qty) as qty from
(select a.*,(select sum(qty) from mis_requisition_order b where  b.item_id=a.id and b.master_id=' . $req . ') as qty
 from mis_requisition_details a where a.bill_status=0 and a.master_id=' . $req . ') as details ')->result();
	}



	function list_company()
	{
		return $this->db->get('tb_companylist');
	}

	function list_particular($doc_id)
	{
		return $this->db->query('select id from mis_document_details where doc_id=' . $doc_id);
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

	function saveOrder($data)
	{
		$this->db->insert('mis_requisition_order', $data);
	}

	function bill_item_complete($id)
	{
		$this->db->query('update mis_requisition_details set bill_status=1 where id=' . $id);
	}

	function bill_master_complete($id)
	{
		$item = $this->db->query('select count(id) as tot from mis_requisition_details where bill_status=0 and master_id=' . $id)->row();
		if ($item->tot == 0)
			$this->db->query('update mis_requisition_master set bill_status=1 where id=' . $id);
	}

	function saveMap($data)
	{
		$this->db->insert('mis_requisiton_map', $data);
	}


	function get_map_by_id($id)
	{
		return $this->db->query('select mis_requisiton_map.*,mis_document_details.particular from mis_requisiton_map
			inner join mis_document_details on mis_document_details.id=mis_requisiton_map.detail_id where mis_requisiton_map.doc_id=' . $id)->result();
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

		return $this->db->query('select mis_scan.*,mis_document_details.particular from mis_scan
		inner join mis_document_details on mis_document_details.id=mis_scan.detail_id
		where mis_document_details.doc_id=' . $billId . ' 

		
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


		$process = $this->db->query('select item_id,master_id from mis_requisition_order where detail_id=' . $id)->result();

		foreach ($process as $row) {
			$this->db->query('update mis_requisition_details set bill_status=0 where    id=' . $row->item_id);
			$this->db->query('update mis_requisition_master set bill_status=0 where id=' . $row->master_id);
		}


		$this->db->where('detail_id', $id);
		$this->db->delete('mis_requisiton_map');

		$this->db->where('detail_id', $id);
		$this->db->delete('mis_requisition_order');


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

	function deleteItem($id1, $id2, $id3, $id4, $id5)
	{

		$this->db->query('delete from mis_requisiton_map where id=' . $id1);
		$this->db->query('update mis_requisition_master set bill_status=0 where id=' . $id4);

		$process = $this->db->query('select item_id from mis_requisition_order where bill_by="' . $id5 . '"  
		  and doc_id=' . $id2 . ' and detail_id=' . $id3 . ' and  master_id=' . $id4)->result();

		foreach ($process as $row) {
			$this->db->query('update mis_requisition_details set bill_status=0 where    id=' . $row->item_id);
		}


		$this->db->query('delete from mis_requisition_order where bill_by="' . $id5 . '"   and doc_id=' . $id2 . ' and detail_id=' . $id3 . '  and  master_id=' . $id4);
	}
}
