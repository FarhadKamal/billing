<?php
class RequestModel extends ci_model
{
	private $tb_empinfo = 'tb_empinfo';
	private $mis_leavemaster = 'mis_leavemaster';
	private $mis_leavedetails = 'mis_leavedetails';
	private $mis_leaverequest = 'mis_leaverequest';
	private $mis_conveyance = 'mis_conveyance';
	private $tb_costcentrelist = 'tb_costcentrelist';
	private $tb_companylist = 'tb_companylist';
	private $mis_internal_order = 'mis_internal_order';
	private $mis_area = 'mis_area';
	private $mis_city_movement = 'mis_city_movement';








	function pending_conveyance_cost($lot_no, $company)
	{



		$loc = $this->getloc();
		$query = "select journey_date,purpose,vfrom,vto,trans_mode,amount,updown,tb_empinfo.vEmpId,
		tb_empinfo.vEmpName,mis_conveyance.created_date,
		tb_empinfo.vDesignation,reportingofficer.depV as ddes,mis_area.area,tb_costcentrelist.vCostCentre,
		reportingofficer.vEmpName as dname
		,tb_companylist.vCompany,internal_order,tb_empinfo.depV as DeptName
		from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join mis_area on mis_area.id=mis_conveyance.business_area
		inner join tb_costcentrelist on tb_costcentrelist.iId=mis_conveyance.costcenter_id
		inner join tb_empinfo  reportingofficer on reportingofficer.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
	
		
		
		
		where status=2 and paid=0 and loc=" . $loc . "  and tb_companylist.iId=" . $company . " and lot_no='" . $lot_no . "' order by mis_conveyance.EmpId";


		return $this->db->query($query);
	}




























	function Request()
	{
		parent::Model();
	}

	function list_costcentre()
	{
		$this->db->order_by('vCostCentre', 'asc');
		return $this->db->get($this->tb_costcentrelist);
	}

	function list_company()
	{
		return $this->db->get($this->tb_companylist);
	}


	function list_boss($reportTo)
	{
		return $this->db->query('select EmpId,emp_name from mis_other_boss
								union 
								select vEmpId,vEmpName from tb_empinfo where vEmpId="' . $reportTo . '"');
	}






	function list_internal_order()
	{
		return $this->db->get($this->mis_internal_order);
	}

	function list_area()
	{
		return $this->db->get($this->mis_area);
	}






	function get_req_conveyance_IS_list($usercode)
	{
		$query = $this->db->query("select vEmpName,mis_conveyance.*,tb_companylist.vCompany ,mis_conveyance.*,tb_companylist.vCompany from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where status=0 and mis_conveyance.reporting_to='" . $usercode . "' order by mis_conveyance.SL desc");
		return $query;
	}

	function get_req_conveyance_by_id($SL)
	{
		return $this->db->query("select vEmpName,mis_conveyance.* from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		where  mis_conveyance.SL='" . $SL . "' order by mis_conveyance.SL desc");
	}



	function getloc()
	{
		return $this->db->query("select loc from users where username='" . $this->session->userdata('username') . "'")->row()->loc;
	}




	function get_req_conveyance_audit_list()
	{

		$loc = $this->getloc();

		if (strtolower($this->session->userdata('username')) == 'pdhlac' or strtolower($this->session->userdata('username')) == 'pdhlac2') {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(14,9)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else if (strtolower($this->session->userdata('username')) == 'gep') {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(6)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else if (strtolower($this->session->userdata('username')) == 'pnlaccount') {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(7)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else if (strtolower($this->session->userdata('username')) == 'dipankar') {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(2,19)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else if (strtolower($this->session->userdata('username')) == 'bablu') {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(1)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else if (strtolower($this->session->userdata('username')) == 'nizam') {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(3,7)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else if (strtolower($this->session->userdata('username')) == 'azaccount') {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(18)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else if (strtolower($this->session->userdata('username')) == 'relacc') {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(21)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else if (
			strtolower($this->session->userdata('username')) == 'hillac1' or
			strtolower($this->session->userdata('username')) == 'hillac2' or
			strtolower($this->session->userdata('username')) == 'hillac3'
		) {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company in(4,12,22,23)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		} else {

			$query = $this->db->query("select a.vEmpName,b.vEmpName as reporting,mis_conveyance.*,tb_companylist.vCompany  from mis_conveyance
		inner join tb_empinfo a on a.vEmpId=mis_conveyance.EmpId
		inner join tb_empinfo b on b.vEmpId=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where  mis_conveyance.company not in(4,14,12,9,22,23)  and status=1 and loc=" . $loc . " order by mis_conveyance.SL asc ");
		}

		return $query;
	}






	function get_req_conveyance_status($usercode)
	{
		$query = $this->db->query("select vEmpName,mis_conveyance.*,tb_companylist.vCompany from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where mis_conveyance.EmpId='" . $usercode . "' order by mis_conveyance.SL desc Limit 100");
		return $query;
	}


	function saveRequestConveyance($request)
	{
		$this->db->insert($this->mis_conveyance, $request);
	}

	function updateConveyance($updateId, $data)
	{
		//echo $this->session->userdata('username');
		$this->db->query("insert into mis_conveyance_history
				(SL, 
				journey_date, 
				purpose, 
				vfrom, 
				vto, 
				trans_mode, 
				amount, 
				EmpId, 
				reporting_to, 
				status, 
				created_date, 
				paid, 
				costcenter_id, 
				business_area,
				paid_date,
				company,
				internal_order,
				updown,
				lot_no,
				lot_date,
				loc,
				update_by
				)
				
				(
					select *,'" . $this->session->userdata('username') . "' as upby from  mis_conveyance where SL=" . $updateId . "
				)
			");





		$this->db->where('SL', $updateId);
		$this->db->update($this->mis_conveyance, $data);
	}





	function reportTo($empid)
	{

		$reportTo = $this->db->query("select repto as ReportingOfficer from tb_empinfo where vEmpId='" . $empid . "'");


		return $reportTo;
	}




	function duplicate_chk_conveyance($eid, $journey_date, $vfrom, $vto, $amount)
	{



		$vfrom =	str_replace("'", " ", $vfrom);
		$vfrom =	str_replace("\"", " ", $vfrom);
		$vfrom =	str_replace("�", " ", $vfrom);


		$vto =	str_replace("'", " ", $vto);
		$vto =	str_replace("\"", " ", $vto);
		$vto =	str_replace("�", " ", $vto);

		$tot = $this->db->query("select count(SL) as tot  from mis_conveyance
		where EmpId='" . $eid . "' and journey_date='" . $journey_date . "' and vfrom='" . $vfrom . "' and vto='" . $vto . "' and amount='" . $amount . "'")->row()->tot;

		return $tot;
	}









	function accept_conveyance_by_is($updateId)
	{
		$this->db->query("update mis_conveyance set status=1 where SL=" . $updateId);
	}


	function cancel_conveyance_by_is($updateId)
	{
		$this->db->query("update mis_conveyance set status=3 where SL=" . $updateId);
	}


	function accept_conveyance_by_audit($updateId)
	{
		$this->db->query("update mis_conveyance set status=2 where SL=" . $updateId);
	}


	function cancel_conveyance_by_audit($updateId)
	{
		$this->db->query("update mis_conveyance set status=3 where SL=" . $updateId);
	}














	function saveAcceptConveyance($updateId, $data)
	{
		$this->db->where('SL', $updateId);
		$this->db->update($this->mis_conveyance, $data);
	}


	function get_accept_conveyance_audit_list()
	{

		$loc = $this->getloc();

		if (strtolower($this->session->userdata('username')) == 'pdhlcost') {

			$query = $this->db->query("select vEmpName,mis_conveyance.*,tb_companylist.vCompany from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where mis_conveyance.company in(14,9) and status=2 and paid=0  and loc=" . $loc . "    order by mis_conveyance.SL desc ");
		} else if (strtolower($this->session->userdata('username')) == 'mkcost') {

			$query = $this->db->query("select  vEmpName,mis_conveyance.*,tb_companylist.vCompany from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where mis_conveyance.company not in(4,14,12,9,22,23) and status=2 and paid=0  and loc=" . $loc . "    order by mis_conveyance.SL desc ");
		} else if (strtolower($this->session->userdata('username')) == 'scost') {

			$query = $this->db->query("select  vEmpName,mis_conveyance.*,tb_companylist.vCompany from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where mis_conveyance.company in(21) and status=2 and paid=0   order by mis_conveyance.SL desc ");
		} else if (strtolower($this->session->userdata('username')) == 'hillcost') {

			$query = $this->db->query("select  vEmpName,mis_conveyance.*,tb_companylist.vCompany from mis_conveyance
	inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where mis_conveyance.company in(4,12,22,23) and status=2 and paid=0   order by mis_conveyance.SL desc ");
		} else {

			$query = $this->db->query("select  vEmpName,mis_conveyance.*,tb_companylist.vCompany from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where mis_conveyance.company not in(4,14,7,12,9,22,23) and   status=2 and paid=0   and loc=" . $loc . "  order by mis_conveyance.SL desc ");
		}
		return $query;
	}


	function payment_made($updateId)
	{
		$this->db->query("update mis_conveyance set paid=1,paid_date=date(now())  where SL=" . $updateId);
	}


	function view_conveyance_bill($sl)
	{
		$query = $this->db->query("
		select journey_date,purpose,vfrom,vto,trans_mode,amount,updown,mis_conveyance.EmpId,
		vEmpName,mis_conveyance.created_date,
		tb_empinfo.vDesignation as Designation,tb_empinfo.depV as DeptName,mis_area.area,tb_costcentrelist.vCostCentre,
		reportingofficer.Name as dname,reportingofficer.Designation as ddes
		,tb_companylist.vCompany,internal_order
		from mis_conveyance
		inner join tb_empinfo on tb_empinfo.vEmpId=mis_conveyance.EmpId
		inner join mis_area on mis_area.id=mis_conveyance.business_area
		inner join tb_costcentrelist on tb_costcentrelist.iId=mis_conveyance.costcenter_id
		inner join reportingofficer on reportingofficer.id=mis_conveyance.reporting_to
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		 where SL =" . $sl);
		return $query;
	}


	function get_lot_no()
	{

		$sql = $this->db->query('select distinct lot_no from mis_conveyance where lot_date= date(now()) and lot_date is not null');
		$lotcount = $this->db->query('select count(lot_no) as tot from mis_conveyance where lot_date= date(now())');
		if ($lotcount->row()->tot > 0) {
			return $sql->row()->lot_no;
		} else {
			//$lastlot=$this->db->query('select lot_no from mis_conveyance order by lot_date desc limit 1');	
			$lastlot = $this->db->query('select max(lot_no) as lot_no from mis_conveyance');
			$last = explode("/", $lastlot->row()->lot_no);

			$year = $this->db->query('select  substring(year(now()),3) as year');
			return ($last[0] + 1) . "/" . $year->row()->year;
		}
	}


	function list_conveyance_employeeid()
	{
		$this->db->select('EmpId');
		$this->db->where('paid', 0);
		$this->db->where('status', 2);
		$this->db->group_by("EmpId");
		return $this->db->get($this->mis_conveyance);
	}

	function get_quick_conveyance_info($lot_no, $companyid, $employeeid)
	{
		return $this->db->query("select * from mis_conveyance inner join tb_companylist on tb_companylist.iId=mis_conveyance.company where paid=0 and status=2
		and EmpId=" . $employeeid . " and company=" . $companyid . " and lot_no='" . $lot_no . "'");
	}


	function process_quick_conveyance($lot_no, $companyid, $employeeid)
	{
		$loc = $this->getloc();
		return $this->db->query("update mis_conveyance set paid=1 where paid=0 and status=2
		and EmpId=" . $employeeid . " and company=" . $companyid . "  and loc=" . $loc . "  and lot_no='" . $lot_no . "'");
	}

	/*
	function lot_list(){
		return $this->db->query("select distinct lot_no  
								from mis_conveyance where status=2 and paid=0");
	} */

	function lot_list()
	{

		if (strtolower($this->session->userdata('username')) == 'pdhlcost') {

			return $this->db->query("select distinct lot_no  
								from mis_conveyance where status=2 and paid=0
									and  mis_conveyance.company in(4,14,12,9,22,23) ");
		} else if (strtolower($this->session->userdata('username')) == 'gepcost') {

			return $this->db->query("select distinct lot_no  
								from mis_conveyance where status=2 and paid=0
									and  mis_conveyance.company in(6) ");
		} else if (strtolower($this->session->userdata('username')) == 'scost') {

			return $this->db->query("select distinct lot_no  
								from mis_conveyance where status=2 and paid=0
									and  mis_conveyance.company in(21) ");
		} else if (strtolower($this->session->userdata('username')) == 'pnlaccount') {

			return $this->db->query("select distinct lot_no  
								from mis_conveyance where status=2 and paid=0
									and  mis_conveyance.company in(7) ");
		} else if (strtolower($this->session->userdata('username')) == 'mkcost') {

			return $this->db->query("select distinct lot_no  from mis_conveyance where status=2 and loc=3 and paid=0 and  mis_conveyance.company not in(4,14,12,9,22,23) ");
		} else return $this->db->query("select distinct lot_no  from mis_conveyance where status=2 and paid=0 and  mis_conveyance.company not in(4,14,7,12,9,22,23) ");
	}

	function get_company_by_lot($lot)
	{
		return $this->db->query("select distinct tb_companylist.iId,  tb_companylist.vCompany
		from mis_conveyance 
		inner join tb_companylist on tb_companylist.iId=mis_conveyance.company
		where status=2 and paid=0 and lot_no='" . $lot . "'");
	}


	function get_employee_by_lot($lot, $company)
	{
		return $this->db->query("select distinct EmpId,displayname
		from mis_conveyance 
		inner join users on users.username=mis_conveyance.EmpId
		where status=2 and paid=0 
		and  lot_no='" . $lot . "' and company=" . $company);
	}






	function emp_request_by($id)
	{
		return $this->db->query('select vEmpName from tb_empinfo where vEmpId="' . $id . '"')->row()->vEmpName;
	}


	function save_mis_conveyance_action($action)
	{
		$this->db->insert('mis_conveyance_action', $action);
	}

	function list_employeeid()
	{
		$this->db->select('vEmpId,concat(vEmpId,' . "'#'" . ',vEmpName) as vEmpName');
		return $this->db->get($this->tb_empinfo);
	}
}
