<?php 
Class LoginModel extends ci_model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }
	function varify($username,$password){
		$this->db->select('user_id,username'); //Table Field
		$this->db->from('users'); //Table Name
		$this->db->where("username= '".$username."' and password = '".$password."' and enabled=1");
		//$this->db->where("password = '".$password."'");
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			return $query->result();
		}else{
			return false;
		}
	}
}
