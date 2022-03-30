<?php
Class Message extends MY_Controller {
	
	// num of records per page
	private $limit = 10;
	private $data;
	function __construct(){
		parent::__construct();
		// load library
		$this->load->library(array('table','validation'));
		
		// load helper
		$this->load->helper('url');
		
		//load file
		$this->load->helper('file');
		
		// load model
		$this->load->model('/messagemodel','',TRUE);

		/*if ($this->userauth->is_logged_in() AND $this->session->userdata('authlevel') == 2) {
		
			show_404();
		}*/
	}

	function Index(){
	
	
	
		$userlevel=$this->session->userdata('authlevel');
		$data['page']='/messageView/messageView'; //add page name as a parameter
		
	
		
		if($userlevel==6){
			$data['request_bill_for_supervise']=$this->messagemodel->request_bill_for_supervise()->row()->tot;
			$data['req_pending']=$this->messagemodel->req_pending();
		}

		if($userlevel==9){
			$data['request_bill_for_audit_notpark']=$this->messagemodel->request_bill_for_audit_notpark()->row()->tot;
			$data['request_bill_for_audit_park']=$this->messagemodel->request_bill_for_audit_park()->row()->tot;
		}
		
		
		
		$data['userlevel']=$userlevel;
		$this->load->view('/messageView/messageView',$data);
	}

}
