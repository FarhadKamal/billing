<?php
class BillaccountModel extends ci_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_company()
    {
        return $this->db->get('tb_companylist');
    }
    
    public function ReSubmitDep($id)
    {
        $this->db->query('update mis_document set hold_by=supervise_by,park_status=0,az_status =0,step_status=2 where id='.$id);
    }
    
    public function account_head()
    {
        if (strtolower($this->session->userdata('username'))=='hillac1' or strtolower($this->session->userdata('username'))=='hillac2'
        
        or strtolower($this->session->userdata('username'))=='hillac3'
        ) {
            $this->db->order_by('account', 'asc');
            return $this->db->get('mis_document_account_halda');
        } else {
            $this->db->order_by('account', 'asc');
        
            if (strtolower($this->session->userdata('username'))!='azaccount'  and   strtolower($this->session->userdata('username'))!='pdhlac3'  and   strtolower($this->session->userdata('username'))!='pdhlac2') {
                $this->db->where('id not in (566, 567, 568, 569, 570, 571, 572, 573)');
            }
            return $this->db->get('mis_document_account_head');
        }
    }
    
    public function accountLocCHK($id)
    {
        return $this->db->query("select count(id) as tot from mis_document where loc=2 and id=".$id)->row()->tot;
    }

    public function getloc()
    {
        return $this->db->query("select loc from users where username='".$this->session->userdata('username')."'")->row()->loc;
    }
    
    public function getloctrack()
    {
        return $this->db->query("select loc_track from users where username='".$this->session->userdata('username')."'")->row()->loc_track;
    }
    
    public function getacboss()
    {
        return $this->db->query("select ac_boss from users where username='".$this->session->userdata('username')."'")->row()->ac_boss;
    }
    
    public function checkDuplicatePass($id)
    {
        $user1=strtolower($this->session->userdata('username'));
            
        $user2=$this->db->query("select LOWER(user_id) as user_id from mis_document_action
			where doc_id=".$id." order by id desc limit 1")->row()->user_id;
            
        if ($user1==$user2) {
            return 2;
        } else {
            return 1;
        }
    }
    
    
    
    
    public function getCompanyName($id)
    {
        return $this->db->query("select vCompany from tb_companylist where iId=".$id)->row()->vCompany;
    }
    
    public function list_vendor()
    {
        $this->db->order_by('vendor_name', 'asc');
        if (
        strtolower($this->session->userdata('username'))=='hillac1' or
        strtolower($this->session->userdata('username'))=='hillac2' or
        strtolower($this->session->userdata('username'))=='hillac3'
        
        ) {
            $this->db->where('(vendor_code like "NV%" or vendor_code like "HL%" or vendor_code like "HD%")');
        } else {
            $this->db->where('(vendor_code not like "NV%" and vendor_code not like "HL%" and vendor_code not like "HD%")');
        }
        return $this->db->get('mis_vendor');
    }
    
    public function list_costcentre()
    {
        $this->db->order_by('cost_id', 'asc');
        if (strtolower($this->session->userdata('username'))=='account') {
            $this->db->where('track_id=', 1);
        }
        if (strtolower($this->session->userdata('username'))=='biru') {
            $this->db->where('track_id=', 1);
        }
        if (strtolower($this->session->userdata('username'))=='sudhir') {
            $this->db->where('track_id=', 3);
        }
        if (strtolower($this->session->userdata('username'))=='sanjit') {
            $this->db->where('track_id=', 3);
        }
        if (strtolower($this->session->userdata('username'))=='feroz') {
            $this->db->where('track_id=', 5);
        }
        if (strtolower($this->session->userdata('username'))=='ferdous') {
            $this->db->where('track_id=', 2);
        }
        if (strtolower($this->session->userdata('username'))=='dipankar') {
            $this->db->where('track_id=', 2);
        }
        return $this->db->get('mis_document_cost');
    }
    
    public function list_head()
    {
        return $this->db->get('reportingofficer');
    }

    public function list_internal_order()
    {
        return $this->db->get('mis_internal_order');
    }
    
    public function list_area()
    {
        return $this->db->get('mis_area');
    }

    
    public function update($updateId, $data)
    {
        $this->db->where('id', $updateId);
        $this->db->update('mis_document', $data);
    }
    
    
    public function assignSAPidENTRY($updateId, $data)
    {
        $this->db->where('id', $updateId);
        $this->db->update('mis_document_account', $data);
    }
    
    
    

    public function list_district()
    {
        return $this->db->get('listdistrict');
    }
    
    public function list_model()
    {
        return $this->db->get('pump_model');
    }
    
    public function action_doc($data)
    {
        $this->db->insert('mis_document_action', $data);
    }


        
    public function get_documents($billId)
    {
        $this->db->select('mis_scan.*');
        $this->db->where('doc_id', $billId);
        $this->db->order_by('id', 'desc');
        return $this->db->get('mis_scan');
    }


    public function get_paged_list($limit = 10, $offset = 0)
    {
        $loctrack=$this->getloctrack();
        $ac_boss=$this->getacboss();
        
        $this->db->select('mis_document.*,d.vEmpName as empName,c.vCompany');
        $this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
        $this->db->join('tb_companylist c', 'c.iId=mis_document.company_id');
        $this->db->where('step_status=', 4);
        if ($ac_boss==1) {
            $this->db->where('account_head_pass=', 0);
            $this->db->where('(account_pass=1 or bill_type="vendor"  or payment_type="Adjustment")');
            $this->db->where('loc in( '.$loctrack.'  )');
            if (strtolower($this->session->userdata('username'))=='haldahead') {
                $this->db->where('company_id in(9,14)');
            } elseif (strtolower($this->session->userdata('username'))=='azhead') {
                $this->db->where('company_id in(18)');
            } elseif (strtolower($this->session->userdata('username'))=='accounthead') {
                $this->db->where('company_id in(7)');
            } elseif (strtolower($this->session->userdata('username'))=='ctgaccount') {
                $this->db->where('company_id in(1,3,2,5)');
            } else {
                $this->db->where('company_id in(1,2,3,4,5,6,8,10,11,12,13,15,16,17,22,23)');
            }
        } else {
            $this->db->where('account_pass=', 0);
            
            
            if (strtolower($this->session->userdata('username'))!='pdhlac3' &&   strtolower($this->session->userdata('username'))!='pdhlac2'   &&
            strtolower($this->session->userdata('username'))!='azaccount' && strtolower($this->session->userdata('username'))!='relacc'
            ) {
                $this->db->where('loc=', $this->getloc());
            }
        
        
            if (strtolower($this->session->userdata('username'))=='hillac1' or   strtolower($this->session->userdata('username'))=='hillac2'   or
            strtolower($this->session->userdata('username'))=='hillac3'
            ) {
            } else {
                $this->db->where('bill_type<>"vendor"');
                $this->db->where('payment_type<>"Adjustment"');
            }
            $username=$this->session->userdata('username');
            $this->db->where('company_id in( select company_id from map_account_company where ac_id="'.$username.'"  )');
        }
        /*
        if(strtolower($this->session->userdata('username'))=='account')
        $this->db->where('company_id<>3 and company_id<>2  and company_id<>10 and company_id<>5 and company_id<>9  and company_id<>14');
        if(strtolower($this->session->userdata('username'))=='biru')
        $this->db->where('company_id<>3 and company_id<>2  and company_id<>10 and company_id<>5 and company_id<>9  and company_id<>14');
        if(strtolower($this->session->userdata('username'))=='pdhlac')
        $this->db->where('(company_id=9 or company_id=14)');
        if(strtolower($this->session->userdata('username'))=='sudhir')
        $this->db->where('company_id=', 3);
        if(strtolower($this->session->userdata('username'))=='sanjit')
        $this->db->where('company_id=', 3);
        if(strtolower($this->session->userdata('username'))=='ferdous')
        $this->db->where('(company_id=2 or company_id=10)');
        if(strtolower($this->session->userdata('username'))=='feroz')
        $this->db->where('company_id=5');
        */
        $this->db->order_by('holdach', 'asc');
        $this->db->order_by('id', 'asc');
        // $this->db->get('mis_document');
        // $str = $this->db->last_query();
   
        // echo "<pre>";
        // var_dump($str);
        // die();
        //return $this->db->get('mis_document', $limit, $offset);

        return $this->db->get('mis_document');
    }
    

    public function get_search_by_id($from, $to)
    {
        $loctrack=$this->getloctrack();
        $ac_boss=$this->getacboss();
        $this->db->select('mis_document.*,d.vEmpName as empName,c.vCompany');
        $this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
        $this->db->join('tb_companylist c', 'c.iId=mis_document.company_id');
        $this->db->where('step_status=', 4);
        if ($ac_boss==1) {
            $this->db->where('account_head_pass=', 0);
            $this->db->where('(account_pass=1 or bill_type="vendor"  or payment_type="Adjustment")');
            $this->db->where('loc in( '.$loctrack.'  )');
        } else {
            $this->db->where('account_pass=', 0);
            $this->db->where('loc=', $this->getloc());
            $this->db->where('bill_type<>"vendor"');
            $this->db->where('payment_type<>"Adjustment"');
            
            $username=$this->session->userdata('username');
            $this->db->where('company_id in( select company_id from map_account_company where ac_id="'.$username.'"  )');
        }
        /*
        if(strtolower($this->session->userdata('username'))=='account')
        $this->db->where('company_id<>3 and company_id<>2  and company_id<>10 and company_id<>5 and company_id<>9  and company_id<>14');
        if(strtolower($this->session->userdata('username'))=='biru')
        $this->db->where('company_id<>3 and company_id<>2  and company_id<>10 and company_id<>5 and company_id<>9  and company_id<>14');
        if(strtolower($this->session->userdata('username'))=='pdhlac')
        $this->db->where('(company_id=9 or company_id=14)');
        if(strtolower($this->session->userdata('username'))=='sudhir')
        $this->db->where('company_id=', 3);
        if(strtolower($this->session->userdata('username'))=='sanjit')
        $this->db->where('company_id=', 3);
        if(strtolower($this->session->userdata('username'))=='ferdous')
        $this->db->where('(company_id=2 or company_id=10)');
        if(strtolower($this->session->userdata('username'))=='feroz')
        $this->db->where('company_id=5');
        */
        $this->db->where('bill_date >=', $from);
        $this->db->where('bill_date <=', $to);
        $this->db->order_by('holdach', 'asc');
        $this->db->order_by('id', 'asc');
        return $this->db->get('mis_document');
    }
    

    
    public function count_all()
    {
        $this->db->from('mis_document');
        $this->db->where('step_status=', 4);
        $loctrack=$this->getloctrack();
        $ac_boss=$this->getacboss();
        if ($ac_boss==1) {
            $this->db->where('account_head_pass=', 0);
            $this->db->where('(account_pass=1 or bill_type="vendor"  or payment_type="Adjustment")');
            $this->db->where('loc in( '.$loctrack.'  )');
            
            if (strtolower($this->session->userdata('username'))=='haldahead') {
                $this->db->where('company_id in(9,14)');
            } elseif (strtolower($this->session->userdata('username'))=='azhead') {
                $this->db->where('company_id in(18)');
            } elseif (strtolower($this->session->userdata('username'))=='accounthead') {
                $this->db->where('company_id in(7)');
            } elseif (strtolower($this->session->userdata('username'))=='ctgaccount') {
                $this->db->where('company_id in(1,3,2)');
            } else {
                $this->db->where('company_id in(1,2,3,4,5,6,8,10,11,12,13,15,16,17,22,23)');
            }
        } else {
            $this->db->where('account_pass=', 0);
            
            
            if (strtolower($this->session->userdata('username'))!='pdhlac3' &&   strtolower($this->session->userdata('username'))!='pdhlac2'   &&
            strtolower($this->session->userdata('username'))!='azaccount'
            ) {
                $this->db->where('loc=', $this->getloc());
            }
            
            if (strtolower($this->session->userdata('username'))=='hillac1' or   strtolower($this->session->userdata('username'))=='hillac2'   or
            strtolower($this->session->userdata('username'))=='hillac3'
            ) {
            } else {
                $this->db->where('bill_type<>"vendor"');
                $this->db->where('payment_type<>"Adjustment"');
            }
            
            
            #$this->db->where('bill_type<>"vendor"');
            #$this->db->where('payment_type<>"Adjustment"');
            $username=$this->session->userdata('username');
            $this->db->where('company_id in( select company_id from map_account_company where ac_id="'.$username.'"  )');
        }
        /*
        if(strtolower($this->session->userdata('username'))=='account')
        $this->db->where('company_id<>3 and company_id<>2  and company_id<>10 and company_id<>5 and company_id<>9  and company_id<>14');
        if(strtolower($this->session->userdata('username'))=='biru')
        $this->db->where('company_id<>3 and company_id<>2  and company_id<>10 and company_id<>5 and company_id<>9  and company_id<>14');
        if(strtolower($this->session->userdata('username'))=='pdhlac')
        $this->db->where('(company_id=9 or company_id=14)');
        if(strtolower($this->session->userdata('username'))=='sudhir')
        $this->db->where('company_id=', 3);
        if(strtolower($this->session->userdata('username'))=='sanjit')
        $this->db->where('company_id=', 3);
        if(strtolower($this->session->userdata('username'))=='ferdous')
        $this->db->where('(company_id=2 or company_id=10)');
        if(strtolower($this->session->userdata('username'))=='feroz')
        $this->db->where('company_id=5');
        */
        return $this->db->count_all_results();
    }
    


    
    public function deleteCost($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('mis_document_account');
    }
    
    public function deleteAdvance($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('mis_advance_account');
    }

    
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('mis_document');
    }

    
    public function tot_document_by_bill_id($id)
    {
        $sql=$this->db->query("select count(id) as tot from mis_scan where doc_id=".$id);
        return $sql->row()->tot;
    }
    

    public function update_account_head($id)
    {
        $head=$this->input->post('head');
        $head=str_replace("'", "", $head);
        $head=str_replace('"', "", $head);
        $this->db->query('update mis_document_account set account_head="'.$head.'" where id='.$id);
    }

    public function change_account_head($id)
    {
        $this->db->query('update mis_document_account set account_head=null where id='.$id);
    }

    
    public function cancel($id)
    {
        $this->db->query('update mis_document set audit_cancel=1 where id='.$id);
    }
    
    public function pass($id)
    {
        $sql=$this->db->query("select bill_type from mis_document where id=".$id);
        $bill_type=$sql->row()->bill_type;
        if ($bill_type=="vendor") {
            $this->db->query('update mis_document set account_head_pass=1,step_status=5 where id='.$id);
            $this->db->query("insert into mis_document_account(doc_id,company_id,vendor,remark)
			(select mis_document.id,company_id,concat(vendor_name,' ## ',mis_document.vendor_code),'Account Head Approved' as remark  
			from mis_document
			inner join mis_vendor on mis_vendor.vendor_code=mis_document.vendor_code
			 where id='".$id."')");
        } else {
            $this->db->query('update mis_document set account_head_pass=1,step_status=5,holdach=0 where id='.$id);
        }
    }
    
    
    public function saveDoc($data)
    {
        $this->db->insert('mis_scan', $data);
    }
    
    public function save($data)
    {
        $this->db->insert('mis_document_account', $data);
    }
    
    public function saveadvance($data)
    {
        $this->db->insert('mis_advance_account', $data);
    }
    /*
    function netamount($id){
        $sql=$this->db->query('select (amount-advance-if(bill_type="General",if(tds is null,0,tds)  +if(tds is null,0,vds),0 ) ) as netpay  from mis_document where id='.$id);
        return $sql->row()->netpay;
    }
    */
    public function netamount($id)
    {
        $sql=$this->db->query('select (amount-advance-tds-vds-tot_auth_deduction-general_deduction) as netpay  from mis_document where id='.$id);
        return $sql->row()->netpay;
    }
    
    public function netadvanceamount($id)
    {
        $sql=$this->db->query('select (amount) as netpay from mis_advance where id='.$id);
        return $sql->row()->netpay;
    }
    
    
    public function newamount($id)
    {
        $sql=$this->db->query('select sum(divide_amount) as amount from mis_document_account where doc_id='.$id);
        return $sql->row()->amount;
    }
    
    
    public function newadvanceamount($id)
    {
        $sql=$this->db->query('select sum(amount) as amount from mis_advance_account where advance_id='.$id);
        return $sql->row()->amount;
    }
    
    public function resubmitAudit($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('mis_document', $data);
    }
    

    
    public function cost_center($id)
    {
        $query="select remark,sap_id,vendor,mis_document_account.id,account_head,vCompany,profit_center,mis_document_account.doc_id,cost_id,cost_text,mis_internal_order.Order,area,Description,divide_amount from mis_document_account 
		
		left join tb_companylist on tb_companylist.iId=mis_document_account.company_id
		left join mis_area on mis_area.id=mis_document_account.business_area
		left join mis_internal_order on mis_internal_order.Order=mis_document_account.internal_order
		left join mis_document_cost on mis_document_cost.iId=mis_document_account.costcenter_id
		where doc_id=".$id;
        return $this->db->query($query);
    }
    
    public function cost_center_advance($id)
    {
        $query="select * from mis_advance_account where advance_id=".$id;
        return $this->db->query($query);
    }
    
    
    
    public function adjust_bill_list($type)
    {
        $this->db->select('mis_document.*,d.vEmpName as empName');
        $this->db->join('tb_empinfo d', 'd.vEmpId=mis_document.created_by');
        $this->db->where('step_status>3 and step_status<6');
        $this->db->where('account_head_pass=', 1);
        if (strtolower($this->session->userdata('username'))=='hillac1' or   strtolower($this->session->userdata('username'))=='hillac2'   or
            strtolower($this->session->userdata('username'))=='hillac3'
            ) {
            $this->db->where('hill_status', 1);
        }
        $this->db->where('payment_type=', $type);
        $this->db->where('loc=', $this->getloc());
        $username=$this->session->userdata('username');
        $this->db->where('company_id in( select company_id from map_account_company where ac_id="'.$username.'"  )');
        $this->db->order_by('company_id', 'asc');
        return $this->db->get('mis_document');
    }

    
    public function hold($id)
    {
        $this->db->query('update mis_document set holdach=1 where id='.$id);
    }
    
    public function accountmark($id)
    {
        $this->db->query('update mis_document set account_color=1 where id='.$id);
    }
    
    public function rmv_accountmark($id)
    {
        $this->db->query('update mis_document set account_color=0 where id='.$id);
    }
}
