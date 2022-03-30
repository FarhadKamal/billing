<?php
class ReqsupModel extends ci_model
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
		return $this->db->get('reportingofficer');
	}

	function assigned_personList()
	{
		return $this->db->query('select vEmpId,vEmpName from tb_empinfo where depV="Procurement"
		 or vEmpId in(1728,1417,2022,2494,1522,1607,1806,1867,1083,1008,1171,2019,
		 1104,2410, 1120, 2163, 2330, 2367)');
	}

	function save($data)
	{
		$this->db->insert('mis_requisition_master', $data);
	}

	function approve($id, $assigned_person)
	{
		$this->db->query('update mis_requisition_master set procurement_pass=1, approve_status=1,assigned_person=' . $assigned_person . ' where id=' . $id);
		$this->db->query('update mis_requisition_master set approved_date=date(now()) where approved_date is null and id=' . $id);
	}

	function cancel($id)
	{
		$this->db->query('update mis_requisition_master set cancel_status=1 where id=' . $id);
	}

	function returnmade($id)
	{
		$this->db->query('update mis_requisition_master set 
		approve_status=0,submit_status=0,procurement_pass=0,assigned_person=""
		where id=' . $id);
	}

	function assignee_returnmade($id)
	{
		$this->db->query('update mis_requisition_master set 
		procurement_pass=0,assigned_person=""
		where id=' . $id);
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


	function count_req_boss()
	{
		$username	= 	$this->session->userdata('username');
		return $this->db->query("select count(EmpId) as tot from mis_requisition_boss where bossTag='Proc' and EmpId='" . $username . "'")->row()->tot;
	}

	function get_proc_boss_loc()
	{
		$username	= 	$this->session->userdata('username');
		return $this->db->query("select loc from mis_requisition_boss where bossTag='Proc' and EmpId='" . $username . "'")->row()->loc;
	}





	function get_paged_list($limit = 10, $offset = 0)
	{
		$proc_boss_chk = $this->count_req_boss();

		$this->db->select(' mis_requisition_master.*,d.displayname as createdName,a.displayname as approveName,if(mis_requisition_master.assigned_person=0,"", c.displayname )as assignName');
		$this->db->join('users d', 'd.username=mis_requisition_master.request_by');
		$this->db->join('users a', 'a.username =mis_requisition_master.approve_by');
		$this->db->join('users c', 'c.username=mis_requisition_master.assigned_person', 'left');

		if ($proc_boss_chk == 0) {
			$this->db->where('(hold_by=' . $this->session->userdata('username') . ' or approve_by=' . $this->session->userdata('username') . ')');
			$this->db->where('submit_status', 1);
		} else {
			$loc = $this->get_proc_boss_loc();
			$this->db->where('submit_status', 1);
			$this->db->where('((approve_status=1 and procurement_pass=0 and hold_by="' . $this->session->userdata('username') . '") or (procurement_pass=1 and req_loc=' . $loc . ' ) or  approve_by="' . $this->session->userdata('username') . '" )');
		}
		//$this->db->where('bill_status',0);
		$this->db->order_by(' (case 
        when hold_by="' . $this->session->userdata('username') . '" and  procurement_pass=0 and cancel_status=0 then 0
        else 1 
		end)', 'asc');
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_requisition_master', $limit, $offset);
	}

	function count_all()
	{
		$proc_boss_chk = $this->count_req_boss();
		$this->db->from('mis_requisition_master');
		if ($proc_boss_chk == 0)
			$this->db->where('(hold_by=' . $this->session->userdata('username') . ' or approve_by=' . $this->session->userdata('username') . ')');
		else {
			$loc = $this->get_proc_boss_loc();
			$this->db->where('submit_status', 1);
			$this->db->where('((approve_status=1 and procurement_pass=0 and hold_by="' . $this->session->userdata('username') . '") or (procurement_pass=1 and req_loc=' . $loc . ' ) or  approve_by="' . $this->session->userdata('username') . '" )');
		}
		return $this->db->count_all_results();
	}

	function get_search_by_id($from, $to)
	{

		$proc_boss_chk = $this->count_req_boss();

		$this->db->select('mis_requisition_master.*,d.vEmpName as createdName,a.vEmpName as approveName,c.vEmpName as assignName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_requisition_master.request_by');
		$this->db->join('tb_empinfo a', 'a.vEmpId=mis_requisition_master.approve_by', 'left');
		$this->db->join('tb_empinfo c', 'c.vEmpId=mis_requisition_master.assigned_person', 'left');
		if ($this->input->post('condition') == 1) {
			$this->db->where('request_date >=', $from);
			$this->db->where('request_date <=', $to);
		} else if ($this->input->post('condition') == 2) {
			$this->db->where('mis_requisition_master.id', $this->input->post('reqid'));
		}
		if ($proc_boss_chk == 0)
			$this->db->where('(hold_by=' . $this->session->userdata('username') . ' or approve_by=' . $this->session->userdata('username') . ')');
		else {
			$loc = $this->get_proc_boss_loc();
			$this->db->where('((approve_status=1 and procurement_pass=0 and hold_by="' . $this->session->userdata('username') . '") or (procurement_pass=1 and req_loc=' . $loc . ' ) or  approve_by="' . $this->session->userdata('username') . '" )');
		}
		//$this->db->where('bill_status',0);

		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_requisition_master');
	}

	function count_search($from, $to)
	{


		$proc_boss_chk = $this->count_req_boss();
		$this->db->from('mis_requisition_master');
		if ($this->input->post('condition') == 1) {
			$this->db->where('request_date >=', $from);
			$this->db->where('request_date <=', $to);
		} else if ($this->input->post('condition') == 2) {
			$this->db->where('mis_requisition_master.id', $this->input->post('reqid'));
		}
		if ($proc_boss_chk == 0)
			$this->db->where('(hold_by=' . $this->session->userdata('username') . ' or approve_by=' . $this->session->userdata('username') . ')');
		else {
			$loc = $this->get_proc_boss_loc();
			$this->db->where('((approve_status=1 and procurement_pass=0 and hold_by="' . $this->session->userdata('username') . '") or (procurement_pass=1 and req_loc=' . $loc . ' ) or  approve_by="' . $this->session->userdata('username') . '" )');
		}
		//$this->db->where('bill_status',0);

		$this->db->order_by('id', 'desc');
		return $this->db->count_all_results();
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



	function get_boss_id($bossTag, $loc)
	{
		$username	= 	$this->session->userdata('username');
		return $this->db->query("select EmpId from mis_requisition_boss where bossTag='" . $bossTag . "' and loc=" . $loc)->row()->EmpId;
	}


	function FinalSubmit($id, $hold_by)
	{

		$IT				= $this->get_boss_id("IT", 0);
		$Director		= $this->get_boss_id("Director", 0);
		$HR				= $this->get_boss_id("HR", 0);
		$Proc1			= $this->get_boss_id("Proc", 1);
		$Proc2			= $this->get_boss_id("Proc", 2);



		$approve_by = $this->get_by_id($id)->row()->approve_by;
		if ($approve_by == $this->session->userdata('username') and $hold_by == $Proc1)
			$this->db->query('update mis_requisition_master set approve_status=1 , approved_date=date(now()),hold_by=' . $hold_by . ' where id=' . $id);
		else if ($approve_by == $this->session->userdata('username') and $hold_by == $Proc2)
			$this->db->query('update mis_requisition_master set approve_status=1 , approved_date=date(now()),hold_by=' . $hold_by . ' where id=' . $id);
		else if ($approve_by == $this->session->userdata('username') and $hold_by == $HR)
			$this->db->query('update mis_requisition_master set approve_status=1 , approved_date=date(now()),hold_by=' . $hold_by . ',hr=' . $hold_by . ' where id=' . $id);
		else if ($approve_by == $this->session->userdata('username') and $hold_by == $IT)
			$this->db->query('update mis_requisition_master set approve_status=1 , approved_date=date(now()),hold_by=' . $hold_by . ',head_of_is=' . $hold_by . ' where id=' . $id);
		else if ($approve_by == $this->session->userdata('username') and $hold_by == $Director)
			$this->db->query('update mis_requisition_master set approve_status=1 , approved_date=date(now()),hold_by=' . $hold_by . ',director=' . $hold_by . ' where id=' . $id);
		else if ($hold_by == $HR)
			$this->db->query('update mis_requisition_master set  approve_status=1 , hold_by=' . $hold_by . ',hr=' . $hold_by . ' where id=' . $id);
		else if ($hold_by == $IT)
			$this->db->query('update mis_requisition_master set  approve_status=1 , hold_by=' . $hold_by . ',head_of_is=' . $hold_by . ' where id=' . $id);
		else if ($hold_by == $Director)
			$this->db->query('update mis_requisition_master set  approve_status=1 , hold_by=' . $hold_by . ',director=' . $hold_by . ' where id=' . $id);
		else $this->db->query('update mis_requisition_master set  approve_status=1 ,  hold_by=' . $hold_by . ' where id=' . $id);
	}

	function saveAction($data)
	{
		$this->db->insert('mis_requisition_action', $data);
	}


	function getDocId($id)
	{
		return $this->db->query('select distinct doc_id,bill_type from mis_requisiton_map
		inner join mis_document on mis_requisiton_map.doc_id=mis_document.id
		where req_id=' . $id);
	}
}
