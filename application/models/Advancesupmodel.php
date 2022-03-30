<?php
class AdvancesupModel extends ci_model
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


	function depart_code($user)
	{

		$sql = $this->db->query('select depV from tb_empinfo where vEmpId="' . $user . '"');
		if ($sql->row()->depV == "Accounts")
			return 4;
		else return 25;
	}

	function dep_code_by_id($user)
	{
		$sql = $this->db->query('select dep_head_status from reportingofficer where id="' . $user . '"');
		return $sql->row()->dep_head_status;
	}


	function list_dep_head()
	{

		$depart_code = $this->depart_code($this->session->userdata('username'));
		if ($depart_code != 4 and $depart_code != 2)
			$this->db->where('id<>2405');
		$this->db->where('dep_head_status', 1);
		return $this->db->get('reportingofficer');
	}


	function list_fin_head()
	{


		$this->db->where('id=2405');
		return $this->db->get('reportingofficer');
	}

	function list_fin_head_second()
	{


		$this->db->where('id=2346');
		return $this->db->get('reportingofficer');
	}


	function list_audit_head()
	{


		$this->db->where('id=1775');
		return $this->db->get('reportingofficer');
	}

	function list_dir_head()
	{


		$this->db->where('id=3');
		return $this->db->get('reportingofficer');
	}

	function list_dir_ceo()
	{


		$this->db->where('id=1970');
		return $this->db->get('reportingofficer');
	}




	function list_particular($advanceId)
	{
		return $this->db->query('select id from mis_advance_details where advance_id=' . $advanceId);
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
		return $this->db->get('reportingofficer');
	}

	function save($data)
	{
		$this->db->insert('mis_advance', $data);
	}



	function saveDoc($data)
	{
		$this->db->insert('mis_advance_scan', $data);
	}

	function saveParticular($data)
	{
		$this->db->insert('mis_advance_details', $data);
	}

	function user_code()
	{

		$step_status = $this->validation->step_status;

		$pcomp = array(7, 6, 21, 24);

		$zcomp = array(2, 8, 14, 9, 4, 12, 5, 10, 1, 3);
		$ncomp = array(22, 23);

		$rtnv = 3;
		if ($this->session->userdata('username') == '003')
			$rtnv = 1;
		else if ($this->session->userdata('username') == '2405' and in_array($this->validation->Company, $pcomp))
			$rtnv = 2;
		else if ($this->session->userdata('username') == '2346'  and in_array($this->validation->Company, $zcomp))
			$rtnv = 2;

		else if ($this->session->userdata('username') == '2346'  and in_array($this->validation->Company, $ncomp))
			$rtnv = 1;

		$comp = array(18);
		if (($this->session->userdata('username') == '2405'    or   $this->session->userdata('username') == '2023')  and in_array($this->validation->Company, $comp)) {
			$rtnv = 2;

			/*
			if($this->validation->fin_by==2405 && $this->session->userdata('username')==2023)
			$rtnv=2;
			else if($this->validation->ceo_by==2023 && $this->session->userdata('username')==2405)
			$rtnv=2;
			
			*/
		}




		//$sql=$this->db->query('select UserCode from reportingofficer where id='.$this->session->userdata('username'));
		//return $sql->row()->UserCode;
		return $rtnv;
	}

	function list_head_usercode($amount, $advance_type)
	{

		// 18 9
		$usercode = $this->user_code();

		$step_status = $this->validation->step_status;

		$pcomp = array(7, 6, 21, 24);
		$asadcomp = array(1, 3);

		$comp = array(2, 8, 14, 9, 5, 10);
		$hcomp = array(4, 12, 22, 23);




		if (in_array($this->validation->Company, $asadcomp)) {

			if ($usercode == 3)
				$this->db->where('id=2346');
			else if ($this->session->userdata('username') == '2346' and $amount > 50000)
				$this->db->where('UserCode=', 1);
			else
				$this->db->where('id=2346');
		} else if (in_array($this->validation->Company, $pcomp)) {

			if ($usercode == 3)
				$this->db->where('id=2405');
			else if ($this->session->userdata('username') == '2405' and $amount > 50000)
				$this->db->where('UserCode=', 1);
			else
				$this->db->where('id=2405');
		} else if (in_array($this->validation->Company, $hcomp) && $step_status == 2) {

			if ($usercode == 2)
				$this->db->where('UserCode=', 1);
			else
				$this->db->where('UserCode=', 0);
		} else if (in_array($this->validation->Company, $comp)) {



			if ($usercode == 3)
				$this->db->where('id=2346');
			else if ($this->session->userdata('username') == '2346' and $amount > 50000)
				$this->db->where('UserCode=', 1);
			else
				$this->db->where('id=2346');
		} else if ($this->validation->Company == 18) {




			//if($usercode==3 &&  $this->session->userdata('username')==2405 && $this->validation->ceo_by<>2023)
			//$this->db->where('id=2023');

			//else if($usercode==2 &&  $this->session->userdata('username')==2023 and $amount>50000)
			//$this->db->where('UserCode=', 1);

			if ($this->session->userdata('username') == 2405  and $amount > 50000)
				$this->db->where('UserCode=', 1);

			else if ($this->session->userdata('username') <> 2405)
				$this->db->where('id=2405');
		}



		return $this->db->get('reportingofficer');
	}

	function updateParticular($updateId, $data, $totnew)
	{

		$this->db->where('id', $updateId);
		$this->db->update('mis_advance_details', $data);
	}



	function chk_amount($id)
	{
		$sql = $this->db->query("select total from mis_advance_details where id=" . $id);

		return $sql->row()->total;
	}





	function update($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_advance', $data);
	}


	function list_district()
	{
		return $this->db->get('listdistrict');
	}

	function list_model()
	{
		return $this->db->get('pump_model');
	}

	function get_last_entry_id($advance_date)
	{
		$sql = $this->db->query("select id from mis_advance
		where advance_date='" . $advance_date . "' and req_by=" . $this->session->userdata('username') . " 
		order by id desc limit 1");
		return $sql->row()->id;
	}

	function get_documents($advanceId)
	{

		return $this->db->query('select mis_advance_scan.*,mis_advance_details.partcular_details from mis_advance_scan
		inner join mis_advance_details on mis_advance_details.id=mis_advance_scan.advance_details_id
		where mis_advance_details.advance_id=' . $advanceId . ' 
	
			order by mis_advance_details.id asc  
		
		');
	}


	function get_special_documents($advanceId)
	{

		return $this->db->query('select mis_advance_scan.*,mis_advance_details.partcular_details from mis_advance_scan
		inner join mis_advance_details on mis_advance_details.id=mis_advance_scan.advance_details_id
		where mis_advance_details.advance_id=' . $advanceId . ' 
	
			order by mis_advance_details.id asc 
		
		');
	}

	function get_particular($advance_id)
	{

		$this->db->select('mis_advance_details.*');
		$this->db->where('advance_id', $advance_id);
		$this->db->order_by('id', 'asc');
		return $this->db->get('mis_advance_details');
	}



	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_advance.*,d.vEmpName as superviseName,e.vEmpName as createdName');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_advance.req_by', 'left');
		$this->db->where('(supervised_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' or fin_by=' . $this->session->userdata('username') . ' or high_authority_by=' . $this->session->userdata('username') . ')');
		$this->db->where('step_status>=', 2);
		$this->db->order_by(' (case 
        when hold_by="' . $this->session->userdata('username') . '" and step_status<4 then 0
        else 1 
		end)', 'asc');
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance', $limit, $offset);
	}


	function get_search_by_id($from, $to)
	{
		$this->db->select('mis_advance.*,d.vEmpName as superviseName,e.vEmpName as createdName');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_advance.req_by', 'left');
		$this->db->where('(supervised_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' or fin_by=' . $this->session->userdata('username') . '  or high_authority_by=' . $this->session->userdata('username') . ')');
		$this->db->where('advance_date >=', $from);
		$this->db->where('advance_date <=', $to);
		$this->db->like($this->input->post('so'), $this->input->post('sv'));
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance');
	}



	function count_all()
	{
		$this->db->from('mis_advance');
		$this->db->where('(supervised_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' or fin_by=' . $this->session->userdata('username') . '  or high_authority_by=' . $this->session->userdata('username') . ')');
		return $this->db->count_all_results();
	}

	function count_search($from, $to)
	{
		$this->db->from('mis_advance');
		$this->db->where('(supervised_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' or fin_by=' . $this->session->userdata('username') . '  or high_authority_by=' . $this->session->userdata('username') . ')');
		$this->db->where('advance_date >=', $from);
		$this->db->where('advance_date <=', $to);
		$this->db->like($this->input->post('so'), $this->input->post('sv'));
		return $this->db->count_all_results();
	}




	function deleteDoc($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('mis_advance_scan');
	}


	function updateSumParticular($id)
	{
		$this->db->query('update mis_advance set amount=(select sum(particular_amount) from mis_advance_details where advance_id=' . $id . ') where id=' . $id);
	}



	function deleteParticular($id)
	{

		$this->db->where('advance_details_id', $id);
		$this->db->delete('mis_advance_scan');

		$this->db->where('id', $id);
		$this->db->delete('mis_advance_details');
	}




	function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('mis_advance');
	}


	function tot_document_by_bill_id($id)
	{
		$sql = $this->db->query("select count(id) as tot from mis_advance_scan where advance_id=" . $id);
		return $sql->row()->tot;
	}

	function submitSupervisor($id)
	{
		$this->db->query('update mis_advance set step_status=2,hold_by=supervised_by where id=' . $id);
	}

	function doubleFileChk($id)
	{

		$rtn = 0;
		$cancel_chk = $this->db->query('
		select count(mis_advance.id) as tot  from mis_advance 
		inner join mis_advance_scan on mis_advance_scan.advance_id=mis_advance.id
		where cancel_staus=1
		and doc_file="' . $id . '"')->row();
		$rtn = $cancel_chk->tot;




		if ($rtn == 0) {
			$sql = $this->db->query('select count(id) as tot from mis_advance_scan where  doc_file="' . $id . '"');
			return	$sql->row()->tot;
		} else {
			$another_chk = $this->db->query('
			select count(mis_advance.id) as tot  from mis_advance 
			inner join mis_advance_scan on mis_advance_scan.advance_id=mis_advance.id
			where cancel_staus=1
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
		$sql = $this->db->query('update mis_requisition_master set bill_status=1,advance_id="' . $bill_id . '",detail_id="' . $pid . '" where id="' . $id . '"');
	}

	function requisitionDelete($id)
	{
		$sql = $this->db->query('update mis_requisition_master set bill_status=0,advance_id=0,detail_id=0 where id="' . $id . '"');
	}


	function action_doc($data)
	{
		$this->db->insert('mis_advance_remarks', $data);
	}

	function FinalSubmit($updateId, $data)
	{

		$user =	$this->session->userdata('username');
		$this->db->where('id', $updateId);
		if ($user == '3' or $user == '003') {
			//Do Nothing

		} else $this->db->where('hold_by', $user);


		$this->db->update('mis_advance', $data);
	}






















	function getloc()
	{
		return $this->db->query("select loc from users where username='" . $this->session->userdata('username') . "'")->row()->loc;
	}









	function get_paged_list_account($limit = 10, $offset = 0)
	{
		$loc = $this->getloc();
		$this->db->select('mis_advance.*,d.vEmpName as superviseName,e.vEmpName as createdName');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_advance.req_by', 'left');
		$this->db->where('step_status>=', 3);
		$this->db->where('loc', $loc);

		if (strtolower($this->session->userdata('username')) == 'account')
			$this->db->where('mis_advance.company<>3 and mis_advance.company<>2  and mis_advance.company<>6  and mis_advance.company<>10 and mis_advance.company<>5 and mis_advance.company<>9  and mis_advance.company<>14');
		if (strtolower($this->session->userdata('username')) == 'biru')
			$this->db->where('mis_advance.company<>3 and mis_advance.company<>2  and mis_advance.company<>6   and mis_advance.company<>10 and mis_advance.company<>5 and mis_advance.company<>9  and mis_advance.company<>14');
		if (strtolower($this->session->userdata('username')) == 'pdhlac'  or strtolower($this->session->userdata('username')) == 'pdhlac2')
			$this->db->where('(mis_advance.company=9 or mis_advance.company=14)');
		if (strtolower($this->session->userdata('username')) == 'sudhir')
			$this->db->where('mis_advance.company=', 3);
		if (strtolower($this->session->userdata('username')) == 'sanjit')
			$this->db->where('mis_advance.company=', 3);
		if (strtolower($this->session->userdata('username')) == 'pnlaccount')
			$this->db->where('mis_advance.company=', 7);
		if (strtolower($this->session->userdata('username')) == 'gep')
			$this->db->where('mis_advance.company=', 6);
		if (strtolower($this->session->userdata('username')) == 'azaccount')
			$this->db->where('mis_advance.company=', 18);

		if (strtolower($this->session->userdata('username')) == 'relacc')
			$this->db->where('mis_advance.company in (21,24 )');

		if (strtolower($this->session->userdata('username')) == 'hillac1')
			$this->db->where('mis_advance.company in (4,12,22,23)');
		if (strtolower($this->session->userdata('username')) == 'hillac2')
			$this->db->where('mis_advance.company in (4,12,22,23)');
		if (strtolower($this->session->userdata('username')) == 'hillac3')
			$this->db->where('mis_advance.company in (4,12,22,23)');

		if (strtolower($this->session->userdata('username')) == 'pdhlac3')
			$this->db->where('(mis_advance.company=9 or mis_advance.company=14 or mis_advance.company=18)');

		if (strtolower($this->session->userdata('username')) == 'bablu')
			$this->db->where('mis_advance.company=', 1);
		if (strtolower($this->session->userdata('username')) == 'nizam')
			$this->db->where('mis_advance.company in (3,7)');

		if (strtolower($this->session->userdata('username')) == 'accounthead')
			$this->db->where('mis_advance.company in (0)');

		if (strtolower($this->session->userdata('username')) == 'dipankar')
			$this->db->where('(mis_advance.company=2 or mis_advance.company=10)');
		if (strtolower($this->session->userdata('username')) == 'feroz')
			$this->db->where('mis_advance.company=5');


		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance', $limit, $offset);
	}


	function get_search_by_id_account($from, $to)
	{
		$loc = $this->getloc();
		$this->db->select('mis_advance.*,d.vEmpName as superviseName,e.vEmpName as createdName');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_advance.req_by', 'left');
		$this->db->where('step_status>=', 3);
		$this->db->where('loc', $loc);

		if (strtolower($this->session->userdata('username')) == 'account')
			$this->db->where('mis_advance.company<>3 and mis_advance.company<>2 and mis_advance.company<>6  and mis_advance.company<>10 and mis_advance.company<>5 and mis_advance.company<>9  and mis_advance.company<>14');
		if (strtolower($this->session->userdata('username')) == 'biru')
			$this->db->where('mis_advance.company<>3 and mis_advance.company<>2 and mis_advance.company<>6  and mis_advance.company<>10 and mis_advance.company<>5 and mis_advance.company<>9  and mis_advance.company<>14');
		if (strtolower($this->session->userdata('username')) == 'pdhlac'   or strtolower($this->session->userdata('username')) == 'pdhlac2')
			$this->db->where('(mis_advance.company=9 or mis_advance.company=14)');
		if (strtolower($this->session->userdata('username')) == 'sudhir')
			$this->db->where('mis_advance.company=', 3);
		if (strtolower($this->session->userdata('username')) == 'sanjit')
			$this->db->where('mis_advance.company=', 3);
		if (strtolower($this->session->userdata('username')) == 'pnlaccount')
			$this->db->where('mis_advance.company=', 7);
		if (strtolower($this->session->userdata('username')) == 'gep')
			$this->db->where('mis_advance.company=', 6);
		if (strtolower($this->session->userdata('username')) == 'azaccount')
			$this->db->where('mis_advance.company=', 18);

		if (strtolower($this->session->userdata('username')) == 'relacc')
			$this->db->where('mis_advance.company in (21,24 )');

		if (strtolower($this->session->userdata('username')) == 'hillac1')
			$this->db->where('mis_advance.company in (4,12,22,23)');
		if (strtolower($this->session->userdata('username')) == 'hillac2')
			$this->db->where('mis_advance.company in (4,12,22,23)');
		if (strtolower($this->session->userdata('username')) == 'hillac3')
			$this->db->where('mis_advance.company in (4,12,22,23)');


		if (strtolower($this->session->userdata('username')) == 'pdhlac3')
			$this->db->where('(mis_advance.company=9 or mis_advance.company=14 or mis_advance.company=18)');

		if (strtolower($this->session->userdata('username')) == 'bablu')
			$this->db->where('mis_advance.company=', 1);
		if (strtolower($this->session->userdata('username')) == 'nizam')
			$this->db->where('mis_advance.company in (3,7)');



		if (strtolower($this->session->userdata('username')) == 'dipankar')
			$this->db->where('(mis_advance.company=2 or mis_advance.company=10)');
		if (strtolower($this->session->userdata('username')) == 'feroz')
			$this->db->where('mis_advance.company=5');


		if (strtolower($this->session->userdata('username')) == 'accounthead')
			$this->db->where('mis_advance.company in (0)');

		$this->db->where('advance_date >=', $from);
		$this->db->where('advance_date <=', $to);
		$this->db->like($this->input->post('so'), $this->input->post('sv'));
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance');
	}



	function count_all_account()
	{
		$loc = $this->getloc();
		$this->db->from('mis_advance');
		$this->db->where('step_status>=', 3);
		$this->db->where('loc', $loc);

		if (strtolower($this->session->userdata('username')) == 'account')
			$this->db->where('mis_advance.company<>3 and mis_advance.company<>2  and mis_advance.company<>6   and mis_advance.company<>10 and mis_advance.company<>5 and mis_advance.company<>9  and mis_advance.company<>14');
		if (strtolower($this->session->userdata('username')) == 'biru')
			$this->db->where('mis_advance.company<>3 and mis_advance.company<>2  and mis_advance.company<>6  and mis_advance.company<>10 and mis_advance.company<>5 and mis_advance.company<>9  and mis_advance.company<>14');
		if (strtolower($this->session->userdata('username')) == 'pdhlac'   or strtolower($this->session->userdata('username')) == 'pdhlac2')
			$this->db->where('(mis_advance.company=9 or mis_advance.company=14)');
		if (strtolower($this->session->userdata('username')) == 'sudhir')
			$this->db->where('mis_advance.company=', 3);
		if (strtolower($this->session->userdata('username')) == 'sanjit')
			$this->db->where('mis_advance.company=', 3);
		if (strtolower($this->session->userdata('username')) == 'pnlaccount')
			$this->db->where('mis_advance.company=', 7);
		if (strtolower($this->session->userdata('username')) == 'gep')
			$this->db->where('mis_advance.company=', 6);

		if (strtolower($this->session->userdata('username')) == 'bablu')
			$this->db->where('mis_advance.company=', 1);
		if (strtolower($this->session->userdata('username')) == 'nizam')
			$this->db->where('mis_advance.company in (3,7)');


		if (strtolower($this->session->userdata('username')) == 'azaccount')
			$this->db->where('mis_advance.company=', 18);
		if (strtolower($this->session->userdata('username')) == 'relacc')
			$this->db->where('mis_advance.company in (21,24 )');

		if (strtolower($this->session->userdata('username')) == 'hillac1')
			$this->db->where('mis_advance.company in (4,12,22,23)');
		if (strtolower($this->session->userdata('username')) == 'hillac2')
			$this->db->where('mis_advance.company in (4,12,22,23)');
		if (strtolower($this->session->userdata('username')) == 'hillac3')
			$this->db->where('mis_advance.company in (4,12,22,23)');


		if (strtolower($this->session->userdata('username')) == 'dipankar')
			$this->db->where('(mis_advance.company=2 or mis_advance.company=10)');
		if (strtolower($this->session->userdata('username')) == 'feroz')
			$this->db->where('mis_advance.company=5');


		if (strtolower($this->session->userdata('username')) == 'accounthead')
			$this->db->where('mis_advance.company in (0)');

		return $this->db->count_all_results();
	}

	function count_search_account($from, $to)
	{
		$loc = $this->getloc();
		$this->db->from('mis_advance');
		$this->db->where('step_status>=', 3);
		$this->db->where('loc', $loc);

		if (strtolower($this->session->userdata('username')) == 'account')
			$this->db->where('mis_advance.company<>3 and mis_advance.company<>2  and mis_advance.company<>6  and mis_advance.company<>10 and mis_advance.company<>5 and mis_advance.company<>9  and mis_advance.company<>14');
		if (strtolower($this->session->userdata('username')) == 'biru')
			$this->db->where('mis_advance.company<>3 and mis_advance.company<>2   and mis_advance.company<>6  and mis_advance.company<>10 and mis_advance.company<>5 and mis_advance.company<>9  and mis_advance.company<>14');
		if (strtolower($this->session->userdata('username')) == 'pdhlac'   or strtolower($this->session->userdata('username')) == 'pdhlac2')
			$this->db->where('(mis_advance.company=9 or mis_advance.company=14)');
		if (strtolower($this->session->userdata('username')) == 'sudhir')
			$this->db->where('mis_advance.company=', 3);
		if (strtolower($this->session->userdata('username')) == 'sanjit')
			$this->db->where('mis_advance.company=', 3);
		if (strtolower($this->session->userdata('username')) == 'pnlaccount')
			$this->db->where('mis_advance.company=', 7);
		if (strtolower($this->session->userdata('username')) == 'gep')
			$this->db->where('mis_advance.company=', 6);
		if (strtolower($this->session->userdata('username')) == 'azaccount')
			$this->db->where('mis_advance.company=', 18);

		if (strtolower($this->session->userdata('username')) == 'relacc')
			$this->db->where('mis_advance.company in (21,24 )');

		if (strtolower($this->session->userdata('username')) == 'hillac1')
			$this->db->where('mis_advance.company in (4,12,22,23)');
		if (strtolower($this->session->userdata('username')) == 'hillac2')
			$this->db->where('mis_advance.company in (4,12,22,23)');
		if (strtolower($this->session->userdata('username')) == 'hillac3')
			$this->db->where('mis_advance.company in (4,12,22,23)');


		if (strtolower($this->session->userdata('username')) == 'pdhlac3')
			$this->db->where('(mis_advance.company=9 or mis_advance.company=14 or mis_advance.company=18)');

		if (strtolower($this->session->userdata('username')) == 'dipankar')
			$this->db->where('(mis_advance.company=2 or mis_advance.company=10)');
		if (strtolower($this->session->userdata('username')) == 'feroz')
			$this->db->where('mis_advance.company=5');

		$this->db->where('advance_date >=', $from);
		$this->db->where('advance_date <=', $to);
		$this->db->like($this->input->post('so'), $this->input->post('sv'));
		return $this->db->count_all_results();
	}



	function get_paged_list_cost($payment)
	{
		$loc = $this->getloc();
		$this->db->select('mis_advance.*,d.vEmpName as superviseName,e.vEmpName as createdName,vCompany');
		$this->db->join('tb_empinfo d', 'CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by', 'left');
		$this->db->join('tb_empinfo e', 'e.vEmpId=mis_advance.req_by', 'left');
		$this->db->join('tb_companylist p', 'p.iId=mis_advance.company', 'left');
		$this->db->where('step_status', 4);
		if ($payment == 'Cash')
			$this->db->where('loc', $loc);
		$this->db->where('advance_type', $payment);
		if (strtolower($this->session->userdata('username')) == 'pdhlcost') {
			$this->db->where('mis_advance.company in(14,9)');
		}
		if (strtolower($this->session->userdata('username')) == 'hillcost') {
			$this->db->where('mis_advance.company in(4,12,22,23)');
		} else if (strtolower($this->session->userdata('username')) == 'scost') {
			$this->db->where('mis_advance.company in (21,24 )');
		} else 	$this->db->where('mis_advance.company not in(4,14,12,9,22,23)');
		$this->db->order_by('id', 'desc');
		return $this->db->get('mis_advance');
	}
}
