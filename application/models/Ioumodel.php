<?php
class IouModel extends ci_model
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

	function list_head()
	{

		$comp = 0;
		$user = $this->session->userdata('username');

		if ($this->session->userdata('authlevel') == 5 or $this->session->userdata('authlevel') == 6) {
			$comp = $this->db->query('select iCompanyId from tb_empinfo where vEmpId="' . $user . '"')->row()->iCompanyId;
		}


		if ($comp == 4 or $comp == 12) {

			$this->db->where('id in (1085)');
		}


		if ($comp == 22 or $comp == 23) {

			$this->db->where('id in (2527)');
		}

		$this->db->where('UserCode >1');
		$this->db->order_by('Name', 'asc');
		return $this->db->get('reportingofficer');
	}

	function save($data)
	{
		$this->db->insert('mis_iou', $data);
	}




	function saveItem($data)
	{
		$this->db->insert('mis_iou_details', $data);
	}

	function update($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_iou', $data);
	}

	function updateItem($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_iou_details', $data);
	}





	function get_last_entry_id()
	{
		$sql = $this->db->query("select id from mis_iou
		where req_by=" . $this->session->userdata('username') . " 
		order by id desc limit 1");
		return $sql->row()->id;
	}



	function get_items($Id)
	{

		$this->db->select('mis_iou_details.*');
		$this->db->where('iou_id', $Id);
		$this->db->order_by('id', 'asc');
		return $this->db->get('mis_iou_details');
	}


	function get_search_by_id($from, $to)
	{
		//$dep=$this->get_dep();



		$this->db->select('mis_iou.*,a.vEmpName as createdName,b.vEmpName as deptName,h.vEmpName as holdName');
		$this->db->join('tb_empinfo a', 'a.vEmpId=mis_iou.req_by', 'left');

		$this->db->join('tb_empinfo b', 'b.vEmpId=mis_iou.dep_accept_by', 'left');
		$this->db->join('tb_empinfo h', 'h.vEmpId=mis_iou.hold_by', 'left');
		$this->db->where('req_by', $this->session->userdata('username'));
		$this->db->where('req_date >=', $from);
		$this->db->where('req_date <=', $to);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_iou');
	}

	function get_paged_list($limit = 10, $offset = 0)
	{

		$this->db->select('mis_iou.*,a.vEmpName as createdName,b.vEmpName as deptName,h.vEmpName as holdName');
		$this->db->join('tb_empinfo a', 'a.vEmpId=mis_iou.req_by', 'left');

		$this->db->join('tb_empinfo b', 'b.vEmpId=mis_iou.dep_accept_by', 'left');
		$this->db->join('tb_empinfo h', 'h.vEmpId=mis_iou.hold_by', 'left');
		$this->db->where('req_by', $this->session->userdata('username'));




		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_iou', $limit, $offset);
	}


	function count_all()
	{

		$this->db->from('mis_iou');
		$this->db->where('req_by', $this->session->userdata('username'));
		return $this->db->count_all_results();
	}


	function count_search($from, $to)
	{
		$this->db->from('mis_iou');
		$this->db->where('req_by', $this->session->userdata('username'));
		$this->db->where('req_date >=', $from);
		$this->db->where('req_date <=', $to);
		return $this->db->count_all_results();
	}




	function get_dep()
	{
		$dep = 0;
		$sql = $this->db->query('select iDepartment from tb_empinfo where vEmpId="' . $this->session->userdata('username') . '"')->result();

		foreach ($sql as $row) {

			$dep = $row->iDepartment;
		}

		return 	$dep;
	}



	function deleteItem($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('mis_iou_details');
	}


	function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('mis_iou');
	}


	function tot_item_by_id($id)
	{
		$sql = $this->db->query("select count(id) as tot from mis_requisition_details where master_id=" . $id);
		return $sql->row()->tot;
	}


	function FinalSubmit($id)
	{
		$this->db->query('update mis_iou set step_status=2 , req_date=now() where id=' . $id);


		$this->db->query('update mis_iou set step_status=2 , req_date=now() , hold_by=1085 where company in (4,12) and  id=' . $id);
	}

	function countReq($id)
	{
		return $this->db->query('select count(id) as tot from mis_requisition_details where master_id=' . $id)->row()->tot;
	}

	function calculate_total($id)
	{

		$this->db->query('	update mis_iou set amount=(select sum(amount) from mis_iou_details where  iou_id=' . $id . ' ) where id=' . $id);
	}


	function purchasedadd($data, $updateId)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_requisition_master', $data);
	}
}
