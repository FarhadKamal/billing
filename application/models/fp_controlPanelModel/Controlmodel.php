<?php
class ControlModel extends ci_model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }
	function getSavings($id) //For getting last login of users
	{
		
	   $query = "update users set lastlogin =now() where user_id='".$id."'";
	   $this->db->query($query);
	}
	
	function check_password_strength() 
	{
			
			$rtn=0;
			if(sha1($this->session->userdata('username'))==$this->session->userdata('password'))
			{
				$rtn=1;
			}
			else if(!preg_match("#[0-9]+#",$this->session->userdata('tempPass'))) {
						$rtn=1;
					}
			elseif(!preg_match("#[A-Z]+#",$this->session->userdata('tempPass'))) {
					$rtn=1;
				}
			elseif(!preg_match("#[a-z]+#",$this->session->userdata('tempPass'))) {
				$rtn=1;
			}	
			
			
			return $rtn;
	}
	
}
