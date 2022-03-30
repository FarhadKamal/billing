<?php 
Class MessageModel extends ci_model {


	
	function __construct()
    {
        parent::__construct();
    }
	
		
		function request_bill_for_supervise(){
			
			$tot=$this->db->query("select count(id) as tot from mis_document  where  supervise_cancel=0 and authority_cancel=0 and audit_cancel=0 and step_status=2 and hold_by=".$this->session->userdata('username'));
			return $tot;
		}
		
		

	
		function request_bill_for_audit_notpark(){
			
			$tot=$this->db->query("select count(id) as tot from mis_document  where supervise_cancel=0 and authority_cancel=0 and audit_cancel=0 and  step_status=3 and park_status=0");
			return $tot;
		}
		
		
		function request_bill_for_audit_park(){
			
			$tot=$this->db->query("select count(id) as tot from mis_document  where  step_status>=3 and park_status=1 and supervise_cancel=0 and authority_cancel=0 and audit_cancel=0 ");
			return $tot;
		}
		
	 function req_pending(){
		$this->db->from('mis_requisition_master');
		$this->db->where('hold_by',$this->session->userdata('username'));
		$this->db->where('cancel_status',0);
		$this->db->where('submit_status',1);
		$this->db->where('procurement_pass',0);
		
	
		return $this->db->count_all_results();
	}
		
	
}
