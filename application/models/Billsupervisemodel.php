<?php
class BillsuperviseModel extends ci_model
{

	function __construct()
	{
		parent::__construct();
	}

	function ReSubmitEmp($id)
	{

		$this->db->query('update mis_document set park_status=0,az_status =0,step_status=1 where id=' . $id);
	}


	function list_dep_head()
	{

		$depart_code = $this->depart_code($this->session->userdata('username'));
		if ($depart_code != 4 and $depart_code != 2)
			$this->db->where('id<>2405');
		$this->db->where('dep_head_status', 1);
		return $this->db->get('reportingofficer');
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

	function list_coo_head()
	{


		$this->db->where('id=1970');
		return $this->db->get('reportingofficer');
	}

	function list_director_head()
	{


		$this->db->where('id=3');
		return $this->db->get('reportingofficer');
	}


	function list_sel_audit()
	{


		return $this->db->query("select 'audit' as 'id', 'Audit' as 'Name', '' as  'Designation'");
	}



	function list_sel_account()
	{


		return $this->db->query("select 'account' as 'id', 'Account' as 'Name', '' as  'Designation'");
	}



	function list_audit_options($amt)
	{

		if ($amt > 10000) {
			return $this->db->query("
		select 'fin' as 'id', 'Mr. Asad' as 'Name', 'Finance Head' as  'Designation' union
		select 'claimer' as 'id', 'Claimer' as 'Name', '' as  'Designation' union
		select 'dep' as 'id', 'Department' as 'Name', '' as  'Designation' 
	
		");
		} else {
			return $this->db->query("
		select 'account' as 'id', 'Account' as 'Name', '' as  'Designation' union
		select 'claimer' as 'id', 'Claimer' as 'Name', '' as  'Designation' union
		select 'dep' as 'id', 'Department' as 'Name', '' as  'Designation' 
	
		");
		}
	}




	function checkDuplicatePass($id)
	{
		$user1 = strtolower($this->session->userdata('username'));

		$row = $this->db->query("select LOWER(user_id) as user_id,action from mis_document_action
			where doc_id=" . $id . " order by id desc limit 1")->row();

		if ($user1 == $row->user_id and $row->action == 'accepted')
			return 2;
		else return 1;
	}

	function list_company()
	{
		$this->db->where('for_bill', 1);
		return $this->db->get('tb_companylist');
	}

	function list_vendor()
	{
		$this->db->order_by('vendor_name', 'asc');
		return $this->db->get('mis_vendor');
	}

	function list_costcentre()
	{
		$this->db->order_by('vCostCentre', 'asc');
		return $this->db->get('tb_costcentrelist');
	}

	function list_head($amount, $company)
	{

		$usercode = $this->billsupervisemodel->user_code();




		if ($usercode == 4)
			$this->db->where('UserCode=', 3);

		else if ($this->session->userdata('username') == 1936) {

			if ($company == 14 or $company == 9) {
				$this->db->where('id=2228');
			} else if ($company == 18) {
				$this->db->where('id=2023');
			} else $this->db->where('id=3 or UserCode<3 or id=1111');
		} else if ($usercode == 3 and $amount > 10000) {
			$this->db->where('UserCode=', 2);
			if ($company == 9 or $company == 14) {
				$this->db->where('id=2228');
			} else if ($company == 18) {
				$this->db->where('id=2023');
			}
		} else if ($usercode == 2 and $this->session->userdata('username') != 1377)
			$this->db->where('id=3 or id=1377');

		else if ($usercode == 2)
			$this->db->where('id=3');

		return $this->db->get('reportingofficer');
	}



	function update($updateId, $data)
	{

		$newamount = ($data['amount']);
		$sql = $this->db->query("select amount from mis_document where id=" . $updateId);
		$oldamount = $sql->row()->amount;
		if ($newamount != $oldamount)
			$this->db->query("insert into mis_document_history(select id,bill_date,amount," . $newamount . " as oldamount,created_by,hold_by,now() from mis_document where id=" . $updateId . ")");

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





	function get_documents($billId)
	{

		$this->db->select('mis_scan.*');
		$this->db->where('doc_id', $billId);
		$this->db->order_by('detail_id', 'asc');
		$this->db->order_by('id', 'asc');
		return $this->db->get('mis_scan');
	}


	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->select('mis_document.*');
		if ($this->session->userdata('username') == '003' or $this->session->userdata('username') == 2430) {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . '  or ((company_id=21 or company_id=24) and ( hold_by=3 or hold_by=2430   ))     )');
		} else {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . ')');
		}



		$this->db->where('supervise_cancel', 0);
		$this->db->where('authority_cancel', 0);

		$this->db->where('step_status>=', 2);
		$this->db->order_by('step_status', 'asc');

		$this->db->order_by(' (case 
        when hold_by=' . $this->session->userdata('username') . ' then 0
        when hold_by!=' . $this->session->userdata('username') . ' then 1 
    end)', 'asc');

		$this->db->order_by('bill_date', 'asc');

		return $this->db->get('mis_document', $limit, $offset);
	}


	function get_search_by_id($from, $to)
	{
		$this->db->select('mis_document.*,d.vEmpName as empName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		if ($this->session->userdata('username') == '003' or $this->session->userdata('username') == 2430) {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . '  or ((company_id=21 or company_id=24)  and ( hold_by=3 or hold_by=2430   ))     )');
		} else {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . ')');
		}


		$this->db->where('supervise_cancel', 0);
		$this->db->where('authority_cancel', 0);
		$this->db->where('step_status>=', 2);
		$this->db->where('bill_date >=', $from);
		$this->db->where('bill_date <=', $to);
		$this->db->order_by('step_status', 'asc');
		$this->db->order_by(' (case 
        when hold_by=' . $this->session->userdata('username') . ' then 0
        when hold_by!=' . $this->session->userdata('username') . ' then 1 
    end)', 'asc');
		$this->db->order_by('bill_date', 'asc');

		return $this->db->get('mis_document');
	}



	function get_special_search_by_id($stype, $svalue)
	{
		$this->db->select('mis_document.*,d.vEmpName as empName');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		$this->db->join('tb_companylist c', 'c.iId=mis_document.company_id');
		if ($this->session->userdata('username') == '003' or $this->session->userdata('username') == 2430) {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . '  or ((company_id=21 or company_id=24)  and ( hold_by=3 or hold_by=2430   ))     )');
		} else {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . ')');
		}
		$this->db->where('supervise_cancel', 0);
		$this->db->where('authority_cancel', 0);
		$this->db->where('step_status>=', 2);
		$this->db->like($stype, $svalue);

		$this->db->order_by('step_status', 'asc');
		$this->db->order_by(' (case 
        when hold_by=' . $this->session->userdata('username') . ' then 0
        when hold_by!=' . $this->session->userdata('username') . ' then 1 
    end)', 'asc');
		$this->db->order_by('bill_date', 'asc');
		return $this->db->get('mis_document');
	}

	function count_search_special($stype, $svalue)
	{
		$this->db->from('mis_document');
		$this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
		$this->db->join('tb_companylist c', 'c.iId=mis_document.company_id');
		if ($this->session->userdata('username') == '003' or $this->session->userdata('username') == 2430) {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . '  or ((company_id=21 or company_id=24)
		 and ( hold_by=3 or hold_by=2430   ))     )');
		} else {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . ')');
		}
		$this->db->where('supervise_cancel', 0);
		$this->db->where('authority_cancel', 0);
		$this->db->where('step_status>=', 2);
		$this->db->like($stype, $svalue);
		return $this->db->count_all_results();
	}



	function count_all()
	{
		$this->db->from('mis_document');

		if ($this->session->userdata('username') == '003' or $this->session->userdata('username') == 2430) {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . '  or ((company_id=21 or company_id=24)
		 and ( hold_by=3 or hold_by=2430   ))     )');
		} else {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . ')');
		}


		$this->db->where('supervise_cancel', 0);
		$this->db->where('authority_cancel', 0);

		$this->db->where('step_status>=', 2);
		return $this->db->count_all_results();
	}

	function count_search($from, $to)
	{
		$this->db->from('mis_document');
		if ($this->session->userdata('username') == '003' or $this->session->userdata('username') == 2430) {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . '  or ((company_id=21 or company_id=24)
		 and ( hold_by=3 or hold_by=2430   ))     )');
		} else {
			$this->db->where('(supervise_by=' . $this->session->userdata('username') . ' or authority_by=' . $this->session->userdata('username') . ' 
		or high_authority_by=' . $this->session->userdata('username') . ' or super_authority_by=' . $this->session->userdata('username') . ' 
		or finance_head_by=' . $this->session->userdata('username') . ' or ceo_by=' . $this->session->userdata('username') . ')');
		}
		$this->db->where('supervise_cancel', 0);
		$this->db->where('authority_cancel', 0);
		$this->db->where('step_status>=', 2);
		$this->db->where('bill_date >=', $from);
		$this->db->where('bill_date <=', $to);
		return $this->db->count_all_results();
	}


	function chk_supervise_comment($id)
	{
		$sql = $this->db->query('select count(id) as tot from mis_document where supervise_by=hold_by and id=' . $id);
		return $sql->row()->tot;
	}

	function chk_authority_comment($id)
	{
		$sql = $this->db->query('select count(id) as tot from mis_document where authority_by=hold_by and id=' . $id);
		return $sql->row()->tot;
	}

	function chk_high_authority_comment($id)
	{
		$sql = $this->db->query('select count(id) as tot from mis_document where high_authority_by=hold_by and id=' . $id);
		return $sql->row()->tot;
	}

	function chk_super_authority_comment($id)
	{
		$sql = $this->db->query('select count(id) as tot from mis_document where super_authority_by=hold_by and id=' . $id);
		return $sql->row()->tot;
	}

	function chk_ceo_comment($id)
	{
		$sql = $this->db->query('select count(id) as tot from mis_document where ceo_by=hold_by and id=' . $id);
		return $sql->row()->tot;
	}

	function chk_finance_head_comment($id)
	{
		$sql = $this->db->query('select count(id) as tot from mis_document where finance_head_by=hold_by and id=' . $id);
		return $sql->row()->tot;
	}


	function user_code()
	{
		$sql = $this->db->query('select UserCode from reportingofficer where id=' . $this->session->userdata('username'));
		return $sql->row()->UserCode;
	}

	function user_code_by_id($user)
	{
		$sql = $this->db->query('select UserCode from reportingofficer where id="' . $user . '"');
		return $sql->row()->UserCode;
	}



	function deleteDoc($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('mis_scan');
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

	function FinalSubmit($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_document', $data);
	}

	function cancel($updateId, $data)
	{
		$this->db->where('id', $updateId);
		$this->db->update('mis_document', $data);
	}

	function saveDoc($data)
	{
		$this->db->insert('mis_scan', $data);
	}


	function action_doc($data)
	{
		$this->db->insert('mis_document_action', $data);
	}

	function save_mis_document_auth_deduction($id, $data)
	{
		$this->db->insert('mis_document_auth_deduction', $data);
		$this->db->query('update mis_document set tot_auth_deduction=(select sum(auth_deduct_amt) from  mis_document_auth_deduction where doc_id=' . $id . ' ) where id=' . $id);
	}

	function last_comment($id)
	{
		return	$this->db->query('select concat(comment," <b>",action," by:</b> ",displayname) as last_comment from mis_document_action
		inner join users on users.username=mis_document_action.user_id where doc_id=' . $id . ' order by id desc  limit 1');
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
			return	$rtn = $sql->row()->tot;
		} else 0;
	}


	function chkVendor($id)
	{
		$sql = $this->db->query("select bill_type,company_id from mis_document where id=" . $id);
		$bill_type = $sql->row()->bill_type;
		$Company = $sql->row()->company_id;
		if ($bill_type == "vendor" and  in_array($Company, array(4, 12, 22, 23)) == False) {
			$this->db->query('update mis_document set account_head_pass=1,step_status=5 where id=' . $id);
			$this->db->query("insert into mis_document_account(doc_id,company_id,vendor,remark)
			(select mis_document.id,company_id,concat(vendor_name,' ## ',mis_document.vendor_code),'Account Head Approved' as remark  
			from mis_document
			inner join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
			 where id='" . $id . "')");
		}
	}
}
