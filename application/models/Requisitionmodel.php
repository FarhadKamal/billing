<?php
class RequisitionModel extends ci_model
{

	function __construct()
	{
		parent::__construct();
	}

	function list_material()
	{
		return $this->db->query('select * from material_list order by mat_name');
	}

	function list_company()
	{
		return $this->db->get('tb_companylist');
	}

	function list_head()
	{
		$this->db->order_by('Name', 'asc');
		return $this->db->get('reportingofficer');
	}

	function save($data)
	{
		$this->db->insert('mis_requisition_master', $data);
	}

	function get_boss_id($bossTag, $loc)
	{
		$username	= 	$this->session->userdata('username');
		return $this->db->query("select EmpId from mis_requisition_boss where bossTag='" . $bossTag . "' and loc=" . $loc)->row()->EmpId;
	}


	function saveAction($data)
	{
		$this->db->insert('mis_requisition_action', $data);
	}


	function saveItem($data)
	{
		$this->db->insert('mis_requisition_details', $data);
	}

	function update($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_requisition_master', $data);
	}

	function updateItem($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_requisition_details', $data);
	}





	function get_last_entry_id()
	{
		$sql = $this->db->query("select id from mis_requisition_master
		where request_by=" . $this->session->userdata('username') . " 
		order by id desc limit 1");
		return $sql->row()->id;
	}



	function get_items($Id)
	{

		$this->db->select('mis_requisition_details.*');
		$this->db->where('master_id', $Id);
		$this->db->order_by('id', 'asc');
		return $this->db->get('mis_requisition_details');
	}


	function get_search_by_id($from, $to)
	{
		//$dep=$this->get_dep();



		$this->db->select('mis_requisition_master.*,d.vEmpName as createdName,a.vEmpName as approveName,c.vEmpName as assignName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_requisition_master.request_by');
		$this->db->join('tb_empinfo a', 'a.vEmpId=mis_requisition_master.approve_by');
		$this->db->join('tb_empinfo c', 'c.vEmpId=mis_requisition_master.assigned_person', 'left');
		$this->db->where('(request_by=' . $this->session->userdata('username') . ' or ((approve_status=1 and procurement_pass=1 and assigned_person=' . $this->session->userdata('username') . ') or request_by="' . $this->session->userdata('username') . '" ))');
		$this->db->where('request_date >=', $from);
		$this->db->where('request_date <=', $to);
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_requisition_master');
	}


	function get_search_by_material_id($ID)
	{
		//$dep=$this->get_dep();



		$this->db->select('mis_requisition_master.*,d.vEmpName as createdName,a.vEmpName as approveName,c.vEmpName as assignName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_requisition_master.request_by');
		$this->db->join('tb_empinfo a', 'a.vEmpId=mis_requisition_master.approve_by');
		$this->db->join('tb_empinfo c', 'c.vEmpId=mis_requisition_master.assigned_person', 'left');
		$this->db->where('(request_by=' . $this->session->userdata('username') . ' or ((approve_status=1 and procurement_pass=1 and assigned_person=' . $this->session->userdata('username') . ') or request_by="' . $this->session->userdata('username') . '" ))');
		$this->db->where('id', $ID);

		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_requisition_master');
	}

	function get_paged_list($limit = 10, $offset = 0)
	{


		//$dep=$this->get_dep();


		$this->db->select('mis_requisition_master.*,d.vEmpName as createdName,a.vEmpName as approveName,c.vEmpName as assignName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_requisition_master.request_by', 'left');
		$this->db->join('tb_empinfo a', 'a.vEmpId=mis_requisition_master.approve_by', 'left');
		$this->db->join('tb_empinfo c', 'c.vEmpId=mis_requisition_master.assigned_person', 'left');
		$this->db->where('(request_by=' . $this->session->userdata('username') . ' or ((approve_status=1 and procurement_pass=1 and assigned_person=' . $this->session->userdata('username') . ') or request_by="' . $this->session->userdata('username') . '" ))');

		$this->db->order_by(' (case 
        when approve_status=1 and procurement_pass=1 and assigned_person="' . $this->session->userdata('username') . '"  and purchase_staus=0 then 0
        else 1 
		end)', 'asc');


		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_requisition_master', $limit, $offset);
	}


	function count_all()
	{
		//$dep=$this->get_dep();
		$this->db->from('mis_requisition_master');
		$this->db->where('(request_by=' . $this->session->userdata('username') . ' or ((approve_status=1 and procurement_pass=1 and assigned_person=' . $this->session->userdata('username') . ') or request_by="' . $this->session->userdata('username') . '" ))');
		return $this->db->count_all_results();
	}


	function count_search($from, $to)
	{
		//$dep=$this->get_dep();


		$this->db->from('mis_requisition_master');
		if ($dep != 10)
			$this->db->where('request_by', $this->session->userdata('username'));
		//$this->db->where('bill_status',0);
		if ($dep == 10)
			$this->db->where('((approve_status=1 and procurement_pass=1 and assigned_person=' . $this->session->userdata('username') . ') or request_by="' . $this->session->userdata('username') . '" )');
		$this->db->where('request_date >=', $from);
		$this->db->where('request_date <=', $to);
		return $this->db->count_all_results();
	}




	function get_dep()
	{


		$dep = 0;
		if ($this->session->userdata('username') == 1417 or $this->session->userdata('username') == 1013  or $this->session->userdata('username') == 1522  or $this->session->userdata('username') == 1607  or $this->session->userdata('username') == 1806  or $this->session->userdata('username') == 1867) {
			$dep = 10;
		} else {
			$sql = $this->db->query('select iDepartment from tb_empinfo where vEmpId="' . $this->session->userdata('username') . '"')->result();

			foreach ($sql as $row) {

				$dep = $row->iDepartment;
			}
		}

		return 	$dep;
	}



	function deleteItem($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('mis_requisition_details');
	}


	function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('mis_requisition_master');
	}


	function tot_item_by_id($id)
	{
		$sql = $this->db->query("select count(id) as tot from mis_requisition_details where master_id=" . $id);
		return $sql->row()->tot;
	}


	function FinalSubmit($id)
	{
		$this->db->query('update mis_requisition_master set submit_status=1 , request_date=date(now()),hold_by=approve_by where id=' . $id);
		$this->db->query('update mis_requisition_master set submit_status=1 , request_date=date(now()),hold_by=1085,approve_by=1085
		where company in (4,12) and id=' . $id);

		$this->db->query('update mis_requisition_master set submit_status=1 , request_date=date(now()),hold_by=2571,approve_by=2571
		where company in (22,23) and id=' . $id);
	}

	function countReq($id)
	{
		return $this->db->query('select count(id) as tot from mis_requisition_details where master_id=' . $id)->row()->tot;
	}


	function purchasedadd($data, $updateId)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_requisition_master', $data);
	}
}
