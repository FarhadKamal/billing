<?php 
Class ReportsModel extends ci_model {


	
	function __construct()
    {
        parent::__construct();
    }
	
	function list_company(){
		$this->db->select('tb_companylist.*');

		$this->db->order_by('vCompany','asc');
		return $this->db->get('tb_companylist');
	}
	
	
	
	function investigation($id ){
		
		$data=$this->db->query("select * from mis_document where id=".$id)->row();
		
		
		
		
		$query="select id,bill_date,tb_companylist.vCompany,bill_description,amount,advance,tds,vds,c.displayname as claimer,
		s.displayname as supervisor
		 from mis_document 
		inner join tb_companylist on mis_document.company_id=tb_companylist.iId
		inner join users c on c.username=mis_document.created_by
		inner join users s on s.username=mis_document.supervise_by
		where amount=".$data->amount."
		and advance=".$data->advance." and tds=".$data->tds." and vds=".$data->vds."
		and(created_by=".$data->created_by." or supervise_by=".$data->supervise_by.")
		and bill_date>=date(now()-INTERVAL 93 day)
		and step_status>1 and supervise_cancel=0 and authority_cancel=0 and audit_cancel=0
		and id not in($id)" ;
		return $this->db->query($query);
	}
	
	
	
	function get_company_by_id($id){

		$this->db->where('iId', $id);
		return $this->db->get('tb_companylist');
	}
	
	function get_bill_id($id){
		$query="select distinct id from mis_document where sap_id='".$id."'";
		$sql= $this->db->query($query);
		return $sql->row()->id;
	}
	
	function get_cheque_assigned_name($id){
		$query="select displayname from mis_document_action
		inner join users on username=mis_document_action.user_id
		 where action='Cheque Date Assigned' and doc_id='".$id."'";
		$sql= $this->db->query($query);
		return $sql->row()->displayname;
	}
	
	
	
	function view_docs_by_scan($id){
		$query="select distinct doc_id,doc_file from mis_scan where doc_file like '%".$id."%' order by doc_file";
		return $this->db->query($query);

	}
	
	function view_bill_Report_by_sap_year($id,$year){
		$query="select distinct id,bill_date from mis_document where sap_id like '%".$id."%' and year(bill_date)='".$year."' order by bill_date";
		return $this->db->query($query);

	}
	
	
	function get_bill_id_for_without_id($id){
		$query="select distinct doc_id from mis_document_account where sap_id='".$id."'";
		$sql= $this->db->query($query);
		return $sql->row()->doc_id;
	}
	
	
	function my_requisition_by_date_wise($from,$to ){
		$supid =$this->session->userdata('username');
		
		$query="select mis_requisition_master.id,request_date,mis_requisition_details.* from mis_requisition_master
		inner join mis_requisition_details on mis_requisition_master.id=mis_requisition_details.master_id
		where   request_by='".$supid."'    and request_date>='".$from."' and request_date<='".$to."'  
		order by mis_requisition_master.id,mis_requisition_details.id " ;
		return $this->db->query($query);
	}
	
	
	function pending_payment_date_unit_bill($from,$to,$type ){
		
		
		$query="select vCompany,sum(amount) as total from mis_document 
		inner join  (select max(mis_document_action.date) as chkdate,doc_id from mis_document_action group by doc_id) det
		on det.doc_id=mis_document.id
		inner join tb_companylist on mis_document.company_id=tb_companylist.iId
		where step_status=5 and payment_made_status=0
		and company_id in(1,3,4,5,8,9,10,12,14) and payment_type = ('$type') and supervise_cancel=0 
		and authority_cancel=0 and audit_cancel=0 and date(chkdate)>='".$from."'  and date(chkdate)<='".$to."' 
		group by vCompany " ;
		return $this->db->query($query);
	}
	
	
	
	
	function payment_date_wise_bill($from,$to ){
		
		
		$query="select id,sap_id,bill_description,created_by,supervise_by,tb_companylist.vCompany,bill_date,amount,advance,vendor_code,tds,vds,po_no,po_date,gr_no,gr_date,iv_no,iv_date,asset_no,bill_type,payment_type,loc,payment_made_date,suggested_cheque from mis_document
			inner join tb_companylist on tb_companylist.iId=mis_document.company_id
			where payment_made_status=1 and  date(payment_made_date)>='".$from."' and date(payment_made_date)<='".$to."' " ;
		return $this->db->query($query);
	}
	
	
	
	
	function count_date_wise_bill_doc($from,$to,$com,$loc ){
		
		
		$query="select mis_document.id,bill_description,bill_date,amount,
		advance,count(mis_scan.doc_id) as tot,
		GROUP_CONCAT(concat(doc_category,':',doc_file)  ORDER BY doc_file SEPARATOR '<br/>') as scans
		from mis_document
		inner join mis_scan on mis_scan.doc_id=mis_document.id

		where step_status>1 and company_id='".$com."' and mis_document.loc='".$loc."'
		and supervise_cancel=0 and authority_cancel=0 and audit_cancel=0
		 and  date(bill_date)>='".$from."' and date(bill_date)<='".$to."'

		group by mis_document.id" ;
		return $this->db->query($query);
	}
	
	
	function under_my_supervision_bill_payment($from,$to ){
		
		$supid =$this->session->userdata('username');
		
		$query="select mis_document.*,vCompany,users.displayname as creator from mis_document
			inner join tb_companylist on tb_companylist.iId=mis_document.company_id
			inner join users on users.username=mis_document.created_by
			where 
			audit_cancel=0
			and authority_cancel=0
			and supervise_cancel=0 		
		    and  bill_date>='".$from."' and bill_date<='".$to."' and supervise_by='".$supid."' and step_status=6" ;
		return $this->db->query($query);
	}
	
	function view_bill($id){
		$query="select mis_document.*,a.vEmpName as empName,a.vDesignation,a.vGrade,b.vEmpName as superviseName,c.displayname as c,d.displayname as d,e.displayname as e,f.displayname as f,g.displayname as g,h.displayname as h,vCompany,vendor_name
		from mis_document
		inner join tb_empinfo a on mis_document.created_by=a.vEmpId
		left join tb_empinfo b on mis_document.supervise_by=b.vEmpId
		left join users c on c.username=mis_document.authority_by
		left join users d on d.username=mis_document.high_authority_by
		left join users e on e.username=mis_document.super_authority_by
		left join users f on f.username=mis_document.ceo_by
		left join users g on g.username=mis_document.finance_head_by
		left join users h on h.username=mis_document.audit_by
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		inner join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		 where mis_document.id='".$id."'";
		return $this->db->query($query);
	}
	
	
	function view_general($id){
		$query="select mis_document.*,a.vEmpName as empName,a.vDesignation,a.vGrade,b.vEmpName as superviseName,c.displayname as c,d.displayname as d,e.displayname as e,f.displayname as f,g.displayname as g,h.displayname as h,vCompany
		from mis_document
		inner join tb_empinfo a on mis_document.created_by=a.vEmpId
		left join tb_empinfo b on mis_document.supervise_by=b.vEmpId
		left join users c on c.username=mis_document.authority_by
		left join users d on d.username=mis_document.high_authority_by
		left join users e on e.username=mis_document.super_authority_by
		left join users f on f.username=mis_document.ceo_by
		left join users g on g.username=mis_document.finance_head_by
		left join users h on h.username=mis_document.audit_by
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		 where mis_document.id='".$id."'";
		return $this->db->query($query);
	}
	
	
	
	
	function view_history($id){
		$query="select distinct mis_document_history.*,users.displayname,tb_empinfo.vDesignation,audit_by,
		supervise_by,authority_by,high_authority_by,super_authority_by,ceo_by,finance_head_by,username,
		supervise_comment,auth_comment,high_auth_comment,super_auth_comment,ceo_comment,finance_head_comment,audit_comment
		from mis_document_history
		inner join mis_document on mis_document.id=mis_document_history.id
		inner join users on users.username=mis_document_history.hold_by
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document_history.hold_by
		 where mis_document_history.id='".$id."'";
		return $this->db->query($query);
	}
	
	
	
	function view_history_iou($id){
		$query="select a.*,b.vEmpName,b.vDesignation from mis_iou_details_audit a 
		left join tb_empinfo b on b.vEmpId=a.user_id 
		left join mis_iou_details c on c.id=a.iou_details_id
		where c.iou_id='".$id."' order by a.id";
		return $this->db->query($query);
	}
	
	
	function view_details_history($id){
		$query="select distinct mis_document_details_history.*,users.username,tb_empinfo.vDesignation,displayname,
		audit_by,
				supervise_by,authority_by,high_authority_by,super_authority_by,ceo_by,finance_head_by,username,
				supervise_comment,auth_comment,high_auth_comment,super_auth_comment,ceo_comment,finance_head_comment,audit_comment
		 from mis_document_details_history
		inner join mis_document on mis_document.id=mis_document_details_history.doc_id
		inner join users on users.username=mis_document_details_history.update_by
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document_details_history.update_by
		 where doc_id='".$id."' order by mis_document_details_history.update_date asc";
		return $this->db->query($query);
	}
	
	
	
	function view_pumps($id){
		$query="select * from mis_document_pump_details where doc_id='".$id."' order by id asc";
		return $this->db->query($query);
	}
	
	
	function view_detials($id){
		$query="select * from mis_document_details where doc_id='".$id."' order by id asc";
		return $this->db->query($query);
	}
	
	function bill_type($id){
		$sql=$this->db->query("select bill_type from mis_document where id='".$id."'");
		// var_dump($sql->row());
		return $sql->row()->bill_type;
	}
	
	function get_vendor_documents($id){
		$query="select doc_file,doc_category from mis_scan where doc_id='".$id."' order by id asc ";
		return $this->db->query($query);
	}
	
	function get_vendor_requisition($id){
		$query="select * from mis_requisiton_map where doc_id='".$id."' order by id asc ";
		return $this->db->query($query);
	}
	
	
	function get_documents($id){
		$query="select doc_file,doc_category from mis_scan where detail_id='".$id."' order by id asc ";
		return $this->db->query($query);
	}
	
		function get_referance($id){
		$query="select referance_no from mis_document_pump_ref where detail_id='".$id."' order by id asc ";
		return $this->db->query($query);
	}
	
	function get_requisition($id){
		$query="select * from mis_requisiton_map where detail_id='".$id."' order by id asc ";
		return $this->db->query($query);
	}
	
	
	function action_bill($id){
		$query="select distinct mis_document_action.*,users.displayname,tb_empinfo.vDesignation,
		
		CASE
    WHEN users.authlevel = 7 THEN 'Accounts'
    WHEN users.authlevel = 9 THEN 'Audit'
    ELSE depV END as vDepartment

		from mis_document_action
		inner join users on users.username=mis_document_action.user_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document_action.user_id
		
		 where doc_id='".$id."' order by mis_document_action.id asc";
		return $this->db->query($query);
	}

	
	function bill_print($id){
		$query="select mis_document.*,a.vEmpName,b.vCompany,now() as cursdate from mis_document
		inner join tb_empinfo a on mis_document.created_by=a.vEmpId 
		inner join tb_companylist b on b.iId=mis_document.company_id where id='".$id."'";
		return $this->db->query($query);
	}
	
	function iou_print($id){
		$query="select mis_iou.*,a.vEmpName,b.vCompany,depV as vDepartment,a.vDesignation from mis_iou
		inner join tb_empinfo a on mis_iou.req_by=a.vEmpId 
	
		inner join tb_companylist b on b.iId=mis_iou.company where id='".$id."'";
		return $this->db->query($query);
	}
	
	
		function advance_print($id){
		$query="select mis_advance.*,a.vEmpName,b.vCompany, depV as  vDepartment,a.vDesignation,now() as cursdate from mis_advance
		inner join tb_empinfo a on mis_advance.req_by=a.vEmpId 
		
		inner join tb_companylist b on b.iId=mis_advance.company where id='".$id."'";
		return $this->db->query($query);
	}
	
	
	function get_advance_id_by_sap_id($sap){
		$query="select id from mis_advance where sap_id='".$sap."'";
		return $this->db->query($query);
	}

	
	function cost_center($id){
		$query="select remark,sap_id,vendor,cost_id,cost_id,account_head,cost_text,mis_internal_order.Order,area,Description,divide_amount,vCompany,profit_center from mis_document_account 
		left join tb_companylist on tb_companylist.iId=mis_document_account.company_id
		left join mis_area on mis_area.id=mis_document_account.business_area
		left join mis_internal_order on mis_internal_order.Order=mis_document_account.internal_order
		left join mis_document_cost on mis_document_cost.iId=mis_document_account.costcenter_id
		where doc_id='".$id."'";
		return $this->db->query($query);
	}
	
	function cost_center_by_sapid($id){
		$query="select doc_id,remark,sap_id,vendor,cost_id,cost_id,account_head,cost_text,mis_internal_order.Order,area,Description,divide_amount,vCompany,profit_center from mis_document_account 
		left join tb_companylist on tb_companylist.iId=mis_document_account.company_id
		left join mis_area on mis_area.id=mis_document_account.business_area
		left join mis_internal_order on mis_internal_order.Order=mis_document_account.internal_order
		left join mis_document_cost on mis_document_cost.iId=mis_document_account.costcenter_id
		where sap_id='".$id."'";
		return $this->db->query($query);
	}
	
	function get_mis_requisition_action_list($id)
	{
		return $this->db->query("select mis_requisition_action.*,
 vEmpName,vDesignation from mis_requisition_action inner join tb_empinfo on 
mis_requisition_action.user_id=tb_empinfo.vEmpId where master_id=".$id." order by mis_requisition_action.id asc");
	}
	
		function get_material_list($id){
		$query="select a.id,item_unit, req_sap_id,approve_status,approved_date,request_date,item_name,qty_hand,qty_req,delivery_date,contact_details,reason_req,b.remarks,
		c.vEmpName as  reqname,c.vDesignation as reqDesignation,e.vCompany,'' as reqdep,
		d.vEmpName as  appname,d.vDesignation as appDesignation,'' as appdep,
		(select sum(qty) from mis_requisition_order where  master_id=".$id." 
		  and item_id=b.id) as alOrder
		 from mis_requisition_master a
		inner join mis_requisition_details b on a.id=b.master_id
		left join tb_empinfo c on a.request_by=c.vEmpId
		left join tb_empinfo d on a.approve_by=d.vEmpId
		
		
		
		left join tb_companylist e on a.company=e.iId
	
		where a.id='".$id."'";
		return $this->db->query($query);
	}
	
	
	function get_order_list($doc_id,$detail_id,$master_id,$bill_by){
	
		
		
		$query='select * from 
		(select approve_status,approved_date,request_date,item_name,item_unit,
		 if(od.qtyOrder is null,0,od.qtyOrder ) as qtyOrder,
		 if(ad.alOrder is null,0,ad.alOrder ) as alOrder,
		 qty_req,qty_hand,delivery_date,reason_req,b.remarks,contact_details,req_sap_id,
		c.vEmpName as reqname,c.vDesignation as reqDesignation,e.vCompany,c.depV as reqdep,
		d.vEmpName as appname,d.vDesignation as appDesignation,d.depV  as appdep,
		a.id as reqid,b.master_id
		from mis_requisition_master a
		inner join mis_requisition_details b on a.id=b.master_id
		inner join tb_empinfo c on a.request_by=c.vEmpId
		inner join tb_empinfo d on a.approve_by=d.vEmpId
		inner join tb_companylist e on a.company=e.iId
		
		
		left join (select sum(qty) as qtyOrder,item_id from mis_requisition_order where doc_id='.$doc_id.' and detail_id='.$detail_id.' and master_id='.$master_id.' and bill_by="'.$bill_by.'" group by item_id)  od on od.item_id=b.id
		left join (select sum(qty) as alOrder,item_id from mis_requisition_order where master_id='.$master_id.'  group by item_id)  ad on ad.item_id=b.id
		) as details where reqid='.$master_id.' order by qtyOrder desc';
		
		
		return $this->db->query($query);
	}
	
	function vendor_order_list($doc_id,$master_id,$bill_by){
		$query='select * from 
		(select approve_status,approved_date,request_date,item_name,item_unit,
		 if(od.qtyOrder is null,0,od.qtyOrder ) as qtyOrder,
		 if(ad.alOrder is null,0,ad.alOrder ) as alOrder,
		 qty_req,qty_hand,delivery_date,reason_req,b.remarks,contact_details,req_sap_id,
		c.vEmpName as reqname,c.vDesignation as reqDesignation,e.vCompany,c.depV as reqdep,
		d.vEmpName as appname,d.vDesignation as appDesignation,d.depV  as appdep,
		a.id as reqid,b.master_id
		from mis_requisition_master a
		inner join mis_requisition_details b on a.id=b.master_id
		inner join tb_empinfo c on a.request_by=c.vEmpId
		inner join tb_empinfo d on a.approve_by=d.vEmpId
		inner join tb_companylist e on a.company=e.iId
		left join (select sum(qty) as qtyOrder,item_id from mis_requisition_order where doc_id='.$doc_id.' and master_id='.$master_id.' and bill_by="'.$bill_by.'" group by item_id)  od on od.item_id=b.id
		left join (select sum(qty) as alOrder,item_id from mis_requisition_order where master_id='.$master_id.'  group by item_id)  ad on ad.item_id=b.id
		) as details where reqid='.$master_id.' order by qtyOrder desc';
		return $this->db->query($query);
	}
	
	function pending_cheque(){
		if(strtolower($this->session->userdata('username'))=='pdhlcost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(4,14,12,9)
		order by company_id";
		
		}
		
		else if(strtolower($this->session->userdata('username'))=='gepcost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(6)
		order by company_id";
		
		}
		
		else if(strtolower($this->session->userdata('username'))=='scost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(21)
		order by company_id";
		
		}
		
		
		else if(strtolower($this->session->userdata('username'))=='azcost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(18)
		order by company_id";
		
		}
		else if(strtolower($this->session->userdata('username'))=='hillcost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(4,12)
		order by company_id";
		
		}
		
		
		else {
		//$this->db->where('company in(1,2,3,5,7,10,11)');
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id  not in(6,4,14,12,9,18)
		order by company_id";
		}	

		return $this->db->query($query);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	function pending_cheque_com($cmp){
		if(strtolower($this->session->userdata('username'))=='pdhlcost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(4,14,12,9)
		order by company_id";
		
		}
		
		else if(strtolower($this->session->userdata('username'))=='gepcost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(6)
		order by company_id";
		
		}
		
		else if(strtolower($this->session->userdata('username'))=='scost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(21)
		order by company_id";
		
		}
		
		
		else if(strtolower($this->session->userdata('username'))=='azcost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in(18)
		order by company_id";
		
		}
		else if(strtolower($this->session->userdata('username'))=='hillcost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id in($cmp)
		order by company_id";
		
		}
		
		else if(strtolower($this->session->userdata('username'))=='cost'){
		//$this->db->where('company in(4,14,12,9)');
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null 
		or bill_date<='2013-06-15') and company_id in($cmp)
		order by suggested_cheque";
		
		}
		
		
		else {
		//$this->db->where('company in(1,2,3,5,7,10,11)');
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='cheque' and (cheque_date is not null  or bill_date<='2013-06-15') and company_id  not in(6,4,14,12,9,18)
		order by company_id";
		}	

		return $this->db->query($query);
	}
	
	
	function pending_cash($cmp){
		
	
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status=5 and payment_made_status=0 and check_ready=0 and payment_type='Cash' and  company_id in($cmp)
		order by company_id";
		
		

		return $this->db->query($query);
	}
	
	
	function pending_adjust($cmp){
		
	
		
		$query="select mis_document.*,mis_vendor.vendor_code,mis_vendor.vendor_name,vCompany,vEmpName from mis_document 
		left join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
		inner join tb_companylist on tb_companylist.iId=mis_document.company_id
		left join tb_empinfo on tb_empinfo.vEmpId=mis_document.created_by
		where step_status>=5 and step_status<6 and payment_made_status=0 and check_ready=0 and payment_type='Adjustment' and  company_id in($cmp)
		order by company_id";
		
		

		return $this->db->query($query);
	}
	
	function accepted_history($id){
		$query="select distinct a.comment, a.date,u.displayname,e.vDesignation from mis_document_action a
		inner join users u on a.user_id=u.username
		left join tb_empinfo e on a.user_id=e.vEmpId
		 where action='accepted' and a.doc_id=".$id;
		return $this->db->query($query);
	}
	
	
	function account_vendor($id){
		$query=$this->db->query("select vendor from mis_document_account where doc_id=".$id." union select '' ")->row();
		return $query->vendor;
	}
	
	function contractual_status($id){
		$query=$this->db->query("select contractual_status from mis_document where id=".$id)->row();
		return $query->contractual_status;
	}
	
	
	function get_iou_list($id){
		$this->db->select('mis_iou.*,a.vEmpName as createdName,b.vEmpName as deptName,b.vDesignation as deptDesignation,c.vCompany,d.vEmpName as AGMName,d.vDesignation as AGMDesignation,
		ceo.vEmpName as ceoName,ceo.vDesignation as ceoDesignation,dgm_accept_by,company,ceo_remarks,ceo_accept_date,
		dir.vEmpName as dirName,dir.vDesignation as dirDesignation');
		$this->db->join('tb_empinfo a','a.vEmpId=mis_iou.req_by','left');

		$this->db->join('tb_empinfo b','b.vEmpId=mis_iou.dep_accept_by','left');
		$this->db->join('tb_empinfo d','d.vEmpId=mis_iou.dgm_accept_by','left');
		
		$this->db->join('tb_empinfo ceo','ceo.vEmpId=mis_iou.ceo_accept_by','left');
		
		$this->db->join('tb_empinfo dir','dir.vEmpId=mis_iou.dir_accept_by','left');
		
		$this->db->join('tb_companylist c','c.iId=mis_iou.company','left');
		$this->db->where('mis_iou.id', $id);
		return $this->db->get('mis_iou');
	}
	
	
	function get_iou_details_list($id){
		$this->db->select('mis_iou_details.*');
		$this->db->where('mis_iou_details.iou_id', $id);
		return $this->db->get('mis_iou_details');
	}
	
	
	function bill_Report_For_Req($from,$to,$complete_status ){
		$query="select mis_requisition_master.id as Requisiotion_ID ,request_date as Request_Date,a.vEmpName as Assigned_Person,
		b.vEmpName as Requested_By,b.depV as Requested_Department,c.vEmpName as Supervised_By,
		date(mis_requisition_action.action_date) as Procurement_Aprroved_date,bill_status
		 from mis_requisition_master
		left join tb_empinfo a on a.vEmpId=mis_requisition_master.assigned_person
		left join tb_empinfo b on b.vEmpId=mis_requisition_master.request_by
		left join tb_empinfo c on c.vEmpId=mis_requisition_master.approve_by
	
		inner join mis_requisition_action on (mis_requisition_action.master_id=mis_requisition_master.id and mis_requisition_action.user_id in (1091,1017,2284,1087))
		 where cancel_status=0
		and approve_status=1 and submit_status=1 and procurement_pass=1 and (date(request_date)>='".$from."' and date(request_date)<='".$to."') and bill_status=".$complete_status  ;
		
		//echo "<pre>";
		//var_dump($query);
		//echo "</pre>";
		
		return $this->db->query($query);
	}
	
	function get_bill_id_by_req_id($id){
		 return $this->db->query("select * from mis_requisiton_map where  req_id=".$id)->result();
	
	}
	
	
		function get_iou_for_audit_by_emp($emp_id){
		$this->db->where('req_by', $emp_id);
		$this->db->where('step_status', 5);
		return $this->db->get('mis_iou');
	}
	
	
	function view_advance($id){
		$query="select mis_advance.*,a.vEmpName as empName,vCompany
		from mis_advance
		inner join tb_empinfo a on mis_advance.req_by=a.vEmpId
		inner join tb_companylist on tb_companylist.iId=mis_advance.company
		 where mis_advance.id='".$id."'";
		return $this->db->query($query);
	}
	
	function view_advance_detials($id){
		$query="select * from mis_advance_details where advance_id='".$id."' order by id asc";
		return $this->db->query($query);
	}
	
	function get_advance_documents($id){
		$query="select doc_file from mis_advance_scan where advance_details_id='".$id."' order by id asc ";
		return $this->db->query($query);
	}
	
	function get_advance_action($id){
		$query="select distinct mis_advance_remarks.*,displayname from mis_advance_remarks inner join  users on  users.username=mis_advance_remarks.user_id where advance_id='".$id."' order by id asc ";
		return $this->db->query($query);
	}
	
	function cost_center_advance($id){
		$query="select * from mis_advance_account where advance_id=".$id;
		return $this->db->query($query);
	}
	
	function get_advance_paged_list_cost($payment,$cmp){
		$this->db->select('mis_advance.*,d.vEmpName as superviseName,e.vEmpName as createdName,vCompany');
		$this->db->join('tb_empinfo d','CAST(d.vEmpId as SIGNED)=mis_advance.supervised_by','left');
		$this->db->join('tb_empinfo e','e.vEmpId=mis_advance.req_by','left');
		$this->db->join('tb_companylist p','p.iId=mis_advance.company','left');
		$this->db->where('step_status', 4);
	
		$this->db->where('advance_type', $payment);
	
		
		$this->db->where('mis_advance.company', $cmp);
		$this->db->order_by('id','desc');
		return $this->db->get('mis_advance');
		}
	
	function download_advance($from,$to ){
		
		$query="select ma.id,c.vCompany,advance_date,advance_description,te.vEmpId as claimerID,
		te.vEmpName as claimerName,ts.vEmpId as supervisorID,
		ts.vEmpName as supervisorrName,ma.amount,
		group_concat(madt.partcular_details,' ### ',madt.particular_amount SEPARATOR '<br/>') as parts,
		step_status,payment_date,cancel_staus, 
		advance_type,
		if(str_action='Cheque is Ready',mr.action_date,null) as cheque_payment_date,
		if(str_action='Payment Made',mr.action_date,null) as cash_payment_date
		 from  mis_advance ma
		left join mis_advance_details madt on madt.advance_id=ma.id
		inner join tb_empinfo te on te.vEmpId=ma.req_by
		inner join tb_empinfo ts on ts.vEmpId=ma.supervised_by
		inner join tb_companylist c on c.iId=ma.company
		left join mis_advance_remarks  mr on(mr.advance_id=ma.id
		and (str_action='Cheque is Ready' or str_action='Payment Made')
		)

		where 
		date(ma.advance_date)>='".$from."' and date(ma.advance_date)<='".$to."'
		and step_status>1 group by ma.id" ;
		return $this->db->query($query);
	}
	
	
	
	function download_iou($from,$to ){
		
		$query="select iu.id, c.vCompany,req_date,
		te.vEmpId as claimerID,
		te.vEmpName as claimerName,ts.vEmpId as supervisorID,
		ts.vEmpName as supervisorrName,iu.purpose,iu.amount,
		group_concat(iud.purpose,' ### ',iud.amount SEPARATOR '<br/>') as parts,
		payment_date,cancel_status,step_status

		from mis_iou iu
		left join mis_iou_details iud on iud.iou_id=iu.id 

		inner join tb_empinfo te on te.vEmpId=iu.req_by
		inner join tb_empinfo ts on ts.vEmpId=iu.dep_accept_by
		inner join tb_companylist c on c.iId=iu.company

		where step_status>1 and date(iu.req_date)>='".$from."'  and date(iu.req_date)<='".$to."' 
		group by iu.id" ;
		return $this->db->query($query);
	}
	
	function download_audit_kpi($from,$to, $company ){

		$query="select rcvdate,rcvtot as total_received,
		IFNULL(actot,0) as total_accepted,
		IFNULL(retot,0) as total_returned,
		IFNULL(ctot,0) as total_canceled,audit_comment
		 from
		(select rcvdate,count(doc_id) as rcvtot from
		(select date(max(ma.date)) as rcvdate,ma.doc_id from mis_document_action ma 
		inner join users u on ma.user_id=u.username
		inner join mis_document md on ( md.id=ma.doc_id and md.company_id IN ($company))
		
		where 
		(date(ma.date)>'".$from."' and date(ma.date)<'".$to."') and
		step_status>=3 and flow_type<>'PASC' and
		 u.authlevel=6 and ma.action='accepted' 
		group by ma.doc_id
		) as det group by rcvdate) as det2

		left join 
		(select date(ma.date) as pdate,count(ma.id) as tot
		,sum(if(ma.action='accepted',1,0)) as actot,
		sum(if(ma.action='Returned',1,0)) as retot,
		sum(if(ma.action='cancel',1,0)) as ctot,

		group_concat(
		if(ma.action='Returned'  or ma.action='cancel',concat(ma.doc_id,' ### ',ma.action,' ### ',ma.comment,'<hr><br/>'),'')
		separator ''
		 
		) as audit_comment


		 from  mis_document_action ma 
		 
		inner join mis_document md on ( md.id=ma.doc_id and md.company_id IN ($company)) 
		inner join users u on ma.user_id=u.username
		where date(ma.date)>'".$from."' and date(ma.date)<'".$to."'
		and u.authlevel=9 group by date(ma.date)) as det3
		on det2.rcvdate=det3.pdate
		" ;

		// echo '<pre>'  ;
		// var_dump($query) ;
		// echo '</pre>';
		
		return $this->db->query($query);
	}
	
	
	
	
	
}
