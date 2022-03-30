<?php 
Class IousupModel extends ci_model {
	
	function __construct()
    {
        parent::__construct();
    }

	function list_company(){
		return $this->db->get('tb_companylist');
	}
		
	function list_head(){
		return $this->db->get('reportingofficer');
	}

	function save($data){
		$this->db->insert('mis_iou', $data);
	}
	
	function updateaction($id,$newamount){
	
		$mis_iou_details=$this->db->query("select * from mis_iou_details where id=".$id)->row();
		$item=array(
			'iou_details_id' => $mis_iou_details->id,
			'old_amount' =>  $mis_iou_details->amount,
			'new_amount' =>  $newamount,
			'user_id' => $this->session->userdata('username')
			);
		if($mis_iou_details->amount!=$newamount)
		
		$this->db->insert('mis_iou_details_audit', $item);	
	}
	

	
	function saveItem($data){
		$this->db->insert('mis_requisition_details', $data);
	}
	
	function update($updateId, $data){
		$this->db->where('id', $updateId);
		$this->db->update('mis_iou', $data);
	}
	
	function updateItem($updateId, $data){
		$this->db->where('id', $updateId);
		$this->db->update('mis_requisition_details', $data);
	}
	
	


	
	function get_last_entry_id(){
		$sql=$this->db->query("select id from mis_iou
		where req_by=".$this->session->userdata('username')." 
		order by id desc limit 1");
		return $sql->row()->id;
	}


		
function get_items($Id){

		$this->db->select('mis_iou_details.*');
		$this->db->where('iou_id', $Id);
		$this->db->order_by('id','asc');
		return $this->db->get('mis_iou_details');	
	
	}

	
	function get_search_by_id($from,$to){
		$dep=$this->get_dep();
	
	
	
		$this->db->select('mis_iou.*,a.vEmpName as createdName,b.vEmpName as deptName');
		$this->db->join('tb_empinfo a','a.vEmpId=mis_iou.req_by','left');

		$this->db->join('tb_empinfo b','b.vEmpId=mis_iou.dep_accept_by','left');
		$this->db->where('step_status >', 1);
		$this->db->where('(dep_accept_by="'.$this->session->userdata('username').'" or hold_by="'.$this->session->userdata('username').'")' );		
		$this->db->where('req_date >=', $from);
		$this->db->where('req_date <=', $to);
		$this->db->order_by('id','desc');
		return $this->db->get('mis_iou');
	}

	function get_paged_list($limit = 10, $offset = 0){
		
		$this->db->select('mis_iou.*,a.vEmpName as createdName,b.vEmpName as deptName');
		$this->db->join('tb_empinfo a','a.vEmpId=mis_iou.req_by','left');

		$this->db->join('tb_empinfo b','b.vEmpId=mis_iou.dep_accept_by','left');
		$this->db->where('step_status >', 1);
		$this->db->where('(dep_accept_by="'.$this->session->userdata('username').'" or hold_by="'.$this->session->userdata('username').'")' );	
	
		$this->db->order_by('id','desc');
		return $this->db->get('mis_iou', $limit, $offset);
	}
	
	
	function count_all(){
	
		$this->db->from('mis_iou');
		$this->db->where('step_status >', 1);
		$this->db->where('(dep_accept_by="'.$this->session->userdata('username').'" or hold_by="'.$this->session->userdata('username').'")' );	
		return $this->db->count_all_results();
	}
	
	
	function count_search($from,$to){
		$this->db->from('mis_iou');
		$this->db->where('step_status >', 1);
		$this->db->where('(dep_accept_by="'.$this->session->userdata('username').'" or hold_by="'.$this->session->userdata('username').'")' );	
		$this->db->where('req_date >=', $from);
		$this->db->where('req_date <=', $to);
		return $this->db->count_all_results();
	}
	
	
	
	
	function get_dep()
	{
		$dep=0;
		$sql=$this->db->query('select iDepartment from tb_empinfo where vEmpId="'.$this->session->userdata('username').'"' )->result();
			
			foreach ($sql as $row){	
			
				$dep=$row->iDepartment;
			}
			
		return 	$dep;
	
	}


	
	function deleteItem($id){
		$this->db->where('id', $id);
		$this->db->delete('mis_requisition_details');
	}

	
	function get_by_id($id){
		$this->db->where('id', $id);
		return $this->db->get('mis_iou');
	}

	
	function tot_item_by_id($id){
		$sql=$this->db->query("select count(id) as tot from mis_requisition_details where master_id=".$id);
		return $sql->row()->tot;
	}
	

	function FinalSubmit($id,$data){
	/*
		if($this->session->userdata('username')=="1570")
		$this->db->query('update mis_iou set step_status=4 , dgm_accept_date=now() where id='.$id);
		else 
		$this->db->query('update mis_iou set step_status=3 , hold_by=1570, dep_accept_date=now() where id='.$id);
		*/
		$this->db->where('id', $id);
		$this->db->update('mis_iou', $data);

	}
	
	function countReq($id){
		return $this->db->query('select count(id) as tot from mis_requisition_details where master_id='.$id)->row()->tot;

	}
	
	
	function purchasedadd($data,$updateId){
		$this->db->where('id', $updateId);
		$this->db->update('mis_requisition_master', $data);
	}
	
	
	function cancel($id){

		$this->db->query('update mis_iou set cancel_status=1 , cancel_date=now() where id='.$id);

	}
	
}
