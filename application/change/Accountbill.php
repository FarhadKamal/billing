<?php
class Accountbill extends MY_Controller
{

    // num of records per page
    private $limit = 10;
    private $data;
    public function __construct()
    {
        parent::__construct();
        // load library
        $this->load->library(array('table', 'validation'));
        // load model
        $this->load->model('/billaccountmodel', '', true);
        $this->load->model('/billcostmodel', '', true);
        $this->load->model('/generalmodel', '', true);
        $this->load->model('/billmodel', '', true);
        $this->load->model('/advancesupmodel', '', true);


        if ($this->userauth->is_logged_in() and $this->session->userdata('authlevel') != 7) {
            show_401();
        }
    }

    public function Index()
    {
    }

    public function _set_fields()
    {
        $fields['Company'] = 'Company';
        $fields['Area'] = 'Area';
        $fields['internal_order'] = 'internal_order';
        $fields['CostCentre'] = 'Cost Center';
        $fields['divide_amount'] = 'Amount';
        $fields['tmpProfitC'] = 'Profit Center';
        $fields['account_head'] = 'Account Head';
        $fields['remark'] = 'Remark';

        $this->validation->set_fields($fields);
    }

    public function _set_rules()
    {
        $rules['Company'] = 'trim|required';
        $fields['Area'] = 'trim|required';
        $rules['internal_order'] = 'trim|required';
        $rules['CostCentre'] = 'trim|required';
        $rules['divide_amount'] = 'trim|required';
        $rules['account_head'] = 'trim|required';
        $this->validation->set_rules($rules);
        $this->validation->set_message('required', '* required');
        $this->validation->set_error_delimiters('<p class="error">', '</p>');
    }


    public function transfer_set_fields()
    {
        $fields['Company'] = 'Company';
        $fields['loc'] = 'Location';
        $fields['payment_type'] = 'payment_type';
        $fields['suggested_cheque'] = 'Suggested Cheque';
        $this->validation->set_fields($fields);
    }

    public function transfer_set_rules()
    {
        $rules['Company'] = 'trim|required';
        $fields['loc'] = 'trim|required';
        $rules['payment_type'] = 'trim|required';
        $this->validation->set_rules($rules);
        $this->validation->set_message('required', '* required');
        $this->validation->set_error_delimiters('<p class="error">', '</p>');
    }


    public function bill_transfer($id)
    {
        $this->transfer_set_fields();
        $this->transfer_set_rules();

        $changeItem = '';

        $rows = $this->billaccountmodel->get_by_id($id)->row();
        $this->validation->bill_date = 						date('d-m-Y', strtotime($rows->bill_date));
        $this->validation->Company = 						$rows->company_id;
        $this->validation->payment_type = 					$rows->payment_type;
        $this->validation->suggested_cheque = 				$rows->suggested_cheque;
        $this->validation->loc = 							$rows->loc;



        $data = $this->getListData();

        if ($this->validation->run() == false) {
            $data['message'] = '';
        } elseif ($this->input->post('payment_type') == 'Cheque' and $this->input->post('suggested_cheque') == '') {
            $data['message'] = 			'<div class="cancel" align=left>please input Cheque Name..</div>';
        } else {
            $this->billaccountmodel->update($id, $this->makeQueryTransfer());
            $tcheck = 0;
            if ($rows->company_id <> $this->input->post('Company')) {
                $tcheck = 1;
                $oldcompany = $this->billaccountmodel->getCompanyName($rows->company_id);
                $newcompany = $this->billaccountmodel->getCompanyName($this->input->post('Company'));

                $changeItem = 'Transfer Company: ' . $oldcompany . ' to ' . $newcompany;

                $this->billaccountmodel->action_doc(
                    array(
                        'doc_id' => $id, 'action' => $changeItem, 'user_id' => $this->session->userdata('username')
                    )
                );
            }
            if ($rows->loc <> $this->input->post('loc')) {
                $tcheck = 1;
                if ($rows->loc == 1) {
                    $oldLocation = 'Chittagong';
                } elseif ($rows->loc == 3) {
                    $oldLocation = 'Mohakhali';
                } else {
                    $oldLocation = 'Dhaka';
                }

                if ($this->input->post('loc') == 1) {
                    $newLocation = 'Chittagong';
                } elseif ($this->input->post('loc') == 3) {
                    $newLocation = 'Mohakhali';
                } else {
                    $newLocation = 'Dhaka';
                }


                $changeItem = 'Transfer Location: ' . $oldLocation . ' to ' . $newLocation;

                $this->billaccountmodel->action_doc(
                    array(
                        'doc_id' => $id, 'action' => $changeItem, 'user_id' => $this->session->userdata('username')
                    )
                );
            }
            if ($rows->payment_type <> $this->input->post('payment_type')) {
                $tcheck = 1;
                $changeItem = 'Transfer Payment Type: ' . $rows->payment_type . ' to ' . $this->input->post('payment_type');

                $this->billaccountmodel->action_doc(
                    array(
                        'doc_id' => $id, 'action' => $changeItem, 'user_id' => $this->session->userdata('username')
                    )
                );
            }



            if ($tcheck == 1) {
                $data['message'] = '<div class="success" align="left">Transfer successful..</div>';
            } else {
                $data['message'] = '<div class="success" align="left">Nothing Transfer!..</div>';
            }
        }

        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['title'] = 'Bill Transfer';
        $data['action'] =  site_url('bill/accountbill/bill_transfer/' . $id . '/');
        $data['page'] = '/billView/accountBillEdit'; //add page name as a parameter
        $this->load->view('index', $data);
    }




    public function makeQueryTransfer()
    {
        return $member = array(

            'company_id' => $this->input->post('Company'),
            'payment_type' => $this->input->post('payment_type'),
            'suggested_cheque' => $this->input->post('suggested_cheque'),
            'loc' => $this->input->post('loc')

        );
    }





    public function viewDoc($doc)
    {
        $data['doc'] = $doc;
        $data['userlevel'] = $this->session->userdata('authlevel');
        $this->load->view('billView/viewDoc', $data);
    }


    public function viewDocs($id)
    {
        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['docs'] = $this->billaccountmodel->get_documents($id);
        $this->load->view('billView/viewDocs', $data);
    }





    public function makeQuery($id)
    {
        return $member = array(


            'doc_id' => $id,
            'business_area' => $this->input->post('Area'),
            'company_id' => $this->input->post('Company'),
            'internal_order' => $this->input->post('internal_order'),
            'costcenter_id' => $this->input->post('CostCentre'),
            'profit_center' => $this->input->post('tmpProfitC'),
            'divide_amount' => $this->input->post('divide_amount'),
            'account_head' => $this->input->post('account_head'),
            'remark' => $this->input->post('remark')
        );
    }




    public function mkDate($userDate)
    {
        if ($userDate != '') {
            $date_arr = explode('-', $userDate);
            $data = date("Y-m-d", mktime(0, 0, 0, $date_arr[1], $date_arr[0], $date_arr[2]));
            return $data;
        } else {
            return '';
        }
    }

    public function getListData()
    {
        $data['accounthead'] = $this->billaccountmodel->account_head();
        $data['company'] = $this->billaccountmodel->list_company();
        $data['vendor'] = $this->billaccountmodel->list_vendor();
        $data['costcentre'] = $this->billaccountmodel->list_costcentre();
        $data['reportingOfficerList'] = $this->billaccountmodel->list_head();
        return $data;
    }



    public function billList($offset = 0, $message = '')
    {
        // offset
        $uri_segment = 4;
        $offset = $this->uri->segment($uri_segment);

        //Search
        $data['action'] = site_url('bill/accountbill/searchbill');
        $data['title'] = "bill List";
        // set user message
        $data['message'] = $message;

        if ($message == 'accept') {
            $data['message'] = "<div class='success' align=left>Clear Successful..!!</div>";
        } elseif ($message == 'resubmit') {
            $data['message'] = "<div class='success' align=left>Resubmit Successful..!!</div>";
        } elseif ($message == 'pass') {
            $data['message'] = "<div class='success' align=left>Passed Successful..!!</div>";
        } elseif ($message == 'hold') {
            $data['message'] = "<div class='cancel' align=left>Hold Successful..!!</div>";
        } elseif ($message == 'dupAction') {
            $data['message'] = "<div class='cancel' align=left>You have already made an action for this bill!</div>";
        }



        // load data
        //$bill = $this->billaccountmodel->get_paged_list($this->limit, $offset)->result();
        $bill = $this->billaccountmodel->get_paged_list(0, 0)->result();
        // generate pagination
        /*
        $this->load->library('pagination');
        $config['base_url'] = site_url('bill/accountbill/billList/');
        $number_of_rows=$this->billaccountmodel->count_all();
        $config['total_rows'] = $number_of_rows;
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = $uri_segment;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['totalrecord'] = $number_of_rows;
        */
        $data['totalrecord'] = $this->billaccountmodel->count_all();
        $data['table'] = $this->bill_table($bill);
        $data['page'] = '/billView/accountList'; //add page name as a parameter
        $data['userlevel'] = $this->session->userdata('authlevel');
        $this->load->view('index', $data);
    }


    public function resubmitToAudit($id)
    {
        if ($this->billaccountmodel->checkDuplicatePass($id) == 2) {
            redirect('bill/accountbill/billList/0/dupAction');
        }

        $this->billaccountmodel->resubmitAudit(
            $id,
            array(
                'step_status' => 3,
                'account_head_pass' => 0,
                'az_status' => 0,
                'account_comment' => $this->input->post('comment')
            )
        );
        $this->billaccountmodel->action_doc(
            array(
                'doc_id' => $id, 'action' => 'Resubmit', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
            )
        );

        redirect('bill/accountbill/billList/0/resubmit');
    }



    public function resubmitToAccount($id)
    {
        if ($this->billaccountmodel->checkDuplicatePass($id) == 2) {
            redirect('bill/accountbill/billList/0/dupAction');
        }

        $this->billaccountmodel->resubmitAudit(
            $id,
            array(
                'step_status' => 4,
                'account_pass' => 0,
                'account_head_pass' => 0,
                'account_comment' => $this->input->post('comment')
            )
        );
        $this->billaccountmodel->action_doc(
            array(
                'doc_id' => $id, 'action' => 'Resubmit', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
            )
        );

        redirect('bill/accountbill/billList/0/resubmit');
    }


    public function resubmitToDep($id)
    {
        if ($this->billaccountmodel->checkDuplicatePass($id) == 2) {
            redirect('bill/accountbill/billList/0/dupAction');
        }

        $this->billaccountmodel->ReSubmitDep($id);
        $this->billaccountmodel->action_doc(
            array(
                'doc_id' => $id, 'action' => 'Resubmit', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
            )
        );

        redirect('bill/accountbill/billList/0/resubmit');
    }


    public function resubmitAudit($id)
    {
        $data['message'] = '';
        $data['comment'] = '';
        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['title'] = 'Resubmit Reason';
        $data['action'] =  site_url('bill/accountbill/resubmitToAudit/' . $id . '/');
        $data['page'] = '/billView/comment'; //add page name as a parameter
        $this->load->view('index', $data);
    }

    public function resubmitAccount($id)
    {
        $data['message'] = '';
        $data['comment'] = '';
        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['title'] = 'Resubmit Reason';
        $data['action'] =  site_url('bill/accountbill/resubmitToAccount/' . $id . '/');
        $data['page'] = '/billView/comment'; //add page name as a parameter
        $this->load->view('index', $data);
    }




    public function resubmitDep($id)
    {
        $data['message'] = '';
        $data['comment'] = '';
        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['title'] = 'Resubmit Reason';
        $data['action'] =  site_url('bill/accountbill/resubmitToDep/' . $id . '/');
        $data['page'] = '/billView/comment'; //add page name as a parameter
        $this->load->view('index', $data);
    }




    public function bill_table($bill)
    {
        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('id', 'billing&nbsp;date', 'company', 'location', 'bill&nbsp;type', 'payment&nbsp;type', 'description', 'Net&nbsp;Pay', 'Submitted&nbsp;by', 'Flow&nbsp;Type', 'status', 'action', 'documents', 'resubmit');

        $status = "";

        //$i = 0 + $offset;
        $sl = 1;
        foreach ($bill as $row) {
            if ($row->step_status == 4) {
                $status = "Still Not Clear";
            } elseif ($row->step_status == 5) {
                $status = "Accounts Clear";
            }

            if ($row->supervise_cancel == 1) {
                $status = "Cancel by Supervisor";
            } elseif ($row->authority_cancel == 1) {
                $status = "Cancel by Authority";
            } elseif ($row->audit_cancel == 1) {
                $status = "Cancel by Audit";
            }


            if ($row->step_status == 4 and $row->supervise_cancel == 0 and $row->authority_cancel == 0 and $row->audit_cancel == 0) {
                $clear = anchor('bill/accountbill/bill_Accept_account/' . $row->id, 'clear', array('class' => 'add'));
                $anclear = anchor('bill/pobill/bill_Accept_account/' . $row->id, 'Without&nbsp;PO&nbsp;Clear', array('class' => 'add'));
            } else {
                $clear = "";
                $anclear = "";
            }


            if ($row->bill_type == "vendor") {
                $viewbill = anchor('reports/reports/view_bill/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
            } elseif ($row->bill_type == "distribution") {
                $viewbill = anchor('reports/reports/view_distribution/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
                $viewhistory = '';
            } else {
                $viewbill = anchor('reports/reports/view_general/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
            }
            $resubmit = '';
            if ($row->company_id != 4 and $row->company_id != 12  and $row->company_id != 22  and $row->company_id != 23) {
                $resubmit = anchor('bill/accountbill/resubmitAudit/' . $row->id, 'Resubmit&nbsp;to&nbsp;Audit', array('class' => 'add'));
            }
            $resubmit .= "<br/>" . anchor('bill/accountbill/resubmitDep/' . $row->id, 'Resubmit&nbsp;to&nbsp;Department', array('class' => 'add'));


            if ($row->account_head_pass == 0 and ($row->account_pass == 1  or   $row->bill_type == "vendor" or   $row->payment_type == "Adjustment")) {
                $pass = anchor('bill/accountbill/pass/' . $row->id, 'Comment&nbsp;and&nbsp;Pass', array('class' => 'add'));
                $pass = $pass . '<br/>' . anchor('bill/accountbill/quickpass/' . $row->id, 'Pass', array('class' => 'add', 'onclick' => "return confirm('Are you sure want to Pass?')"));
                $pass = $pass . '<br/>' . anchor('bill/accountbill/holdach/' . $row->id, 'Hold', array('class' => 'add', 'onclick' => "return confirm('Are you sure want to Hold?')"));
                $clear = "";
                $anclear = "";
            } else {
                $pass = anchor('bill/accountbill/holdach/' . $row->id, 'Hold', array('class' => 'add', 'onclick' => "return confirm('Are you sure want to Hold?')"));
            }


            if ($row->account_head_pass == 0 and ($row->account_pass == 1  and  $row->bill_type != "vendor" and   $row->payment_type != "Adjustment")) {
                $clear = anchor('bill/accountbill/bill_Accept_account/' . $row->id, 'Edit Cost Centre', array('class' => 'add'));
                $anclear = anchor('bill/pobill/bill_Accept_account/' . $row->id, 'Without&nbsp;PO&nbsp;Edit', array('class' => 'add'));
                $resubmit .= "<br/>" . anchor('bill/accountbill/resubmitAccount/' . $row->id, 'Resubmit&nbsp;to&nbsp;Account', array('class' => 'add'));
            }


            if (in_array($row->company_id, array(4,12,22,23)) and   $row->bill_type == "general" and   $row->payment_type == "Adjustment") {
                $clear = anchor('bill/accountbill/bill_Accept_account/' . $row->id, 'clear', array('class' => 'add'));
                $anclear = anchor('bill/pobill/bill_Accept_account/' . $row->id, 'Without&nbsp;PO&nbsp;Edit', array('class' => 'add'));
            }




            if ($row->account_pass == 0  and $this->session->userdata('authlevel') == 7  and $row->account_color == 0) {
                $marked = '<br/>' . anchor('bill/accountbill/quickmark/' . $row->id, 'Mark', array('class' => 'add', 'onclick' => "return confirm('Are you sure want to Mark it as Complete?')"));
            } else {
                $marked = "";
            }




            $viewbilldoc = ""; /*
            $docsl=1;
            $pchk="start";
        $pcount=1;

        if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
        //$billdoc=$this->billaccountmodel->get_documents($row->id);
        $billdoc=$this->billmodel->get_special_documents($row->id);
        foreach($billdoc->result() as $rows)
                    {


                    if(strlen($rows->doc_file)<5)
                            $viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));
                            else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/accountbill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
                        $docsl=$docsl+1;
                    }

        }else if($row->bill_type=="general"){
        $billdoc=$this->generalmodel->get_special_documents($row->id);
        foreach($billdoc->result() as $rows)
                    {

                        if(strlen($rows->doc_file)<5)
                    $report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
                    else $report=base_url().'index.php/bill/accountbill/url/'.$rows->doc_file;//'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;


                    if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
                else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
                else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
                $docsl=$docsl+1;

                $pchk=$rows->detail_id;
                    }

        } */

            $doctable = "<div id='" . $row->id . "'  class='divcost' ><font color='#FF000'><b>Double click here to view documents.</b></font></div>";

            $viewaction = anchor('reports/reports/view_action_bill/' . $row->id, 'view&nbsp;step', array('class' => 'view', 'target' => 'about_blank'));
            $loc = "";
            if ($row->loc == 1) {
                $loc = "Chittagong Head Office";
            } elseif ($row->loc == 2) {
                $loc = "Dhaka Office";
            } elseif ($row->loc == 3) {
                $loc = "Mohakhali Office";
            }
            if ($row->account_color == 1) {
                $mid = '<div class="highlight" style=" background-color: #00FF00;">' . $row->id . '</div>';
            } elseif ($row->holdach == 1) {
                $mid = '<div class="highlight" style=" background-color: #B2BEB5;">' . $row->id . '</div>';
            } else {
                $mid = $row->id;
            }


            //$netpay=($row->amount-$row->advance)."&nbsp;BDT";
            //if($row->bill_type=='general')
            $netpay = ($row->amount - $row->advance - $row->tds - $row->vds - $row->tot_auth_deduction - $row->general_deduction) . "&nbsp;BDT";

            $billtransfer = "";
            if ($row->step_status == 4 and $row->supervise_cancel == 0 and $row->authority_cancel == 0 and $row->audit_cancel == 0 and $row->account_pass == 0) {
                $billtransfer = anchor('bill/accountbill/bill_transfer/' . $row->id, 'Trasnfer&nbsp;Bill', array('class' => 'add'));
            }

            $billTpye = $row->bill_type;
            if ($row->bill_type == 'vendor') {
                $billTpye = "<b><font color='#ff0000'>VENDOR</font></b>";
            }

            $company = $row->vCompany;
            if ($row->company_id == 1) {
                $company = "<font color='#0B2574'><b>" . $row->vCompany . "</b></font>";
            } elseif ($row->company_id == 3) {
                $company = "<font color='#2DBE1A'><b>" . $row->vCompany . "</b></font>";
            } elseif ($row->company_id == 7) {
                $company = "<font color='#FF5050'><b>" . $row->vCompany . "</b></font>";
            } elseif ($row->company_id == 24) {
                $company = "<font color='#FF5733'><b>" . $row->vCompany . "</b></font>";
            } elseif ($row->company_id == 21) {
                $company = "<font color='#524093'><b>" . $row->vCompany . "</b></font>";
            }

            $investigation = anchor('reports/reports/investigation/' . $row->id, 'investigation', array('class' => 'view', 'target' => 'about_blank'));
                
            $this->table->add_row(
                $mid,
                $row->bill_date,
                $company,
                $loc,
                $billTpye,
                $row->payment_type,
                $row->bill_description,
                $netpay,
                $row->empName,
                $row->flow_type,
                $status,
                $viewbill . "</br>" . $clear . "<br>" . $anclear . "</br>" . $viewaction . "</br>" . $pass . "</br>" . $marked . "</br>" . $billtransfer."</br>".$investigation,
                $doctable,
                $resubmit
            );
            $sl = $sl + 1;
        }
        return $this->table->generate();
    }

    public function get_doc_details()
    {
        $bill_id = $this->input->post('data');

        $row = $this->billaccountmodel->get_by_id($bill_id)->row();

        $viewbilldoc = "";
        $docsl = 1;
        $pchk = "start";
        $pcount = 1;
        $doccount = 0;

        if ($row->bill_type == "vendor" or $row->bill_type == "distribution") {
            $billdoc = $this->billmodel->get_special_documents($row->id);
            foreach ($billdoc->result() as $rows) {
                $doccount = $doccount + 1;

                if (strlen($rows->doc_file) < 5) {
                    $viewbilldoc = $viewbilldoc . anchor('reports/reports/vendor_material_list/' . $rows->doc_id . '/' . $rows->detail_id . '/' . $rows->doc_file, 'document-' . $docsl, array('class' => 'view', 'target' => 'about_blank'));
                } else {
                    $viewbilldoc =	$viewbilldoc . '<a href="' . base_url() . 'index.php/bill/accountbill/url/' . $rows->doc_file . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                }
                $docsl = $docsl + 1;
            }
        } elseif ($row->bill_type == "general") {
            $billdoc = $this->generalmodel->get_special_documents($row->id);
            foreach ($billdoc->result() as $rows) {
                $doccount = $doccount + 1;
                if (strlen($rows->doc_file) < 5) {
                    $report = base_url() . 'index.php/reports/reports/order_material_list/' . $rows->doc_id . '/' . $rows->detail_id . '/' . $rows->particular . '/' . $rows->doc_file;
                } else {
                    $report = base_url() . 'index.php/bill/accountbill/url/' . $rows->doc_file;
                } //'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;

                if ($pchk == "start") {
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                } elseif ($pchk == $rows->detail_id) {
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                } else {
                    $pcount = $pcount + 1;
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                }
                $docsl = $docsl + 1;

                $pchk = $rows->detail_id;
            }
        }
        if ($doccount == 0) {
            echo "<font color='#FF000'><b>No Document Found..</font>";
        }
        echo $viewbilldoc;
    }


    public function documten_table($items)
    {
        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('No', 'Document', 'Action');

        //$i = 0 + $offset;
        $sl = 1;
        foreach ($items as $row) {
            $del = '&nbsp;' . anchor('bill/accountbill/deleteDoc/' . $row->id . '/' . $row->doc_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));
            $view = '&nbsp;' . anchor('bill/accountbill/viewDoc/' . $row->doc_file, 'view', array('class' => 'view', 'target' => "about_blank"));

            $this->table->add_row(
                $sl,
                $row->doc_file,
                $view . '&nbsp;' . $del
            );
            $sl = $sl + 1;
        }
        return $this->table->generate();
    }




    public function searchbill()
    {

        //Search
        $data['action'] = site_url('bill/accountbill/searchbill');
        ;
        $data['title'] = "bill List";
        // set user message
        $data['message'] = '';

        // load data
        $bill = $this->billaccountmodel->get_search_by_id($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')))->result();

        // generate pagination
        $this->load->library('pagination');
        $data['pagination'] = '';
        $data['totalrecord'] = '';

        $data['table'] = $this->bill_table($bill);
        $data['page'] = '/billView/billList'; //add page name as a parameter
        $data['userlevel'] = $this->session->userdata('authlevel');
        $this->load->view('index', $data);
    }




    public function submitAccounts($id)
    {
        $this->billaccountmodel->submitAccounts($id);
        redirect('bill/accountbill/billList/0/submitAccounts');
    }

    public function cancel($id)
    {
        $this->billaccountmodel->cancel($id);
        redirect('bill/accountbill/billList');
    }

    public function pass($id)
    {
        $data['message'] = '';
        $data['comment'] = '';
        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['title'] = 'Comment';
        $data['action'] =  site_url('bill/accountbill/passadd/' . $id . '/');
        $data['page'] = '/billView/comment'; //add page name as a parameter
        $this->load->view('index', $data);
    }

    public function quickpass($id)
    {
        if ($this->billaccountmodel->checkDuplicatePass($id) == 2) {
            redirect('bill/accountbill/billList/0/dupAction');
        }

        $this->billaccountmodel->pass($id);
        $this->billaccountmodel->action_doc(
            array(
                'doc_id' => $id, 'action' => 'accepted', 'user_id' => $this->session->userdata('username')
            )
        );

        redirect('bill/accountbill/billList/0/pass');
    }


    public function quickmark($id)
    {
        $this->billaccountmodel->accountmark($id);


        redirect('bill/accountbill/billList/0/mark');
    }


    public function holdach($id)
    {
        $this->billaccountmodel->hold($id);

        redirect('bill/accountbill/billList/0/hold');
    }


    public function passadd($id)
    {
        if ($this->billaccountmodel->checkDuplicatePass($id) == 2) {
            redirect('bill/accountbill/billList/0/dupAction');
        }

        $this->billaccountmodel->pass($id);
        $this->billaccountmodel->action_doc(
            array(
                'doc_id' => $id, 'action' => 'accepted', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
            )
        );
        redirect('bill/accountbill/billList/0/pass');
    }



    public function deleteCost($id1, $id2)
    {
        $this->billaccountmodel->deleteCost($id1);
        redirect('bill/accountbill/bill_Accept_account/' . $id2, 'location');
    }



    public function bill_Accept_account($id)
    {
        $netaccount = $this->billaccountmodel->netamount($id);
        $newamount = $this->billaccountmodel->newamount($id);


        $billtag = $this->billaccountmodel->get_by_id($id)->row();




        if ($this->input->post('divide_amount') != "") {
            $newamount = $newamount + $this->input->post('divide_amount');
        }

        $this->_set_fields();
        $this->_set_rules();

        $this->validation->Company	=	$billtag->company_id;
        if ($this->validation->Area == '') {
            $this->validation->Area	= 6;
        }

        if ($this->validation->Area == '') {
            $this->validation->Area	= 6;
        }


        if ($this->validation->remark == '') {
            $this->validation->remark	= $billtag->bill_description;
        }

        $viewbilldoc = "";
        $docsl = 1;
        $pchk = "start";
        $pcount = 1;

        if ($billtag->bill_type == "vendor" or $billtag->bill_type == "distribution") {
            //$billdoc=$this->billaccountmodel->get_documents($row->id);
            $billdoc = $this->billmodel->get_special_documents($billtag->id);
            foreach ($billdoc->result() as $rows) {
                if (strlen($rows->doc_file) < 5) {
                    $viewbilldoc = $viewbilldoc . anchor('reports/reports/vendor_material_list/' . $rows->doc_id . '/' . $rows->detail_id . '/' . $rows->doc_file, 'document-' . $docsl, array('class' => 'view', 'target' => 'about_blank'));
                } else {
                    $viewbilldoc =	$viewbilldoc . '<a href="' . base_url() . 'index.php/bill/accountbill/url/' . $rows->doc_file . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                }
                $docsl = $docsl + 1;
            }
        } elseif ($billtag->bill_type == "general") {
            $billdoc = $this->generalmodel->get_special_documents($billtag->id);
            foreach ($billdoc->result() as $rows) {
                if (strlen($rows->doc_file) < 5) {
                    $report = base_url() . 'index.php/reports/reports/order_material_list/' . $rows->doc_id . '/' . $rows->detail_id . '/' . $rows->particular . '/' . $rows->doc_file;
                } else {
                    $report = base_url() . 'index.php/bill/accountbill/url/' . $rows->doc_file;
                } //'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;


                if ($pchk == "start") {
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                } elseif ($pchk == $rows->detail_id) {
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                } else {
                    $pcount = $pcount + 1;
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                }
                $docsl = $docsl + 1;

                $pchk = $rows->detail_id;
            }
        }

        $data['viewbilldoc'] = $viewbilldoc;




        if ($this->validation->tmpProfitC == '') {
            if ($billtag->loc == 1) {
                $this->validation->tmpProfitC = "PC-01";
            } elseif ($billtag->loc == 2) {
                $this->validation->tmpProfitC = "PC-02";
            }
        }

        //$this->validation->EmpId =	$this->session->userdata('username');

        // run validation
        if ($this->validation->run() == false) {
            $data['message'] = '';
        } elseif ($newamount > $netaccount) {
            $data['message'] = '<div class="cancel" align=left>Balance Not Available..</div>';
        } else {
            // save data
            $data = $this->makeQuery($id);

            //print_r($request);
            $this->billaccountmodel->save($data);

            // set user message

            $data['message'] = '<div class="success" align=left>Save Successful..</div>';

            //$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);
            $data['viewbilldoc'] = $viewbilldoc;
        }
        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['area'] = $this->billaccountmodel->list_area();
        $data['costcentre'] = $this->billaccountmodel->list_costcentre();

        $data['netaccount'] = $netaccount;
        $balance = $netaccount - $this->billaccountmodel->newamount($id);
        $data['balance'] = $balance;
        if ($balance == 0) {
            $this->billaccountmodel->accountmark($id);
        } else {
            $this->billaccountmodel->rmv_accountmark($id);
        }

        $data['accounthead'] = $this->billaccountmodel->account_head();
        $data['company'] = $this->billaccountmodel->list_company();
        $data['internal_order'] = $this->billaccountmodel->list_internal_order();
        // set common properties
        $data['title'] = 'Bill Accept Form';
        $data['action'] =  site_url('bill/accountbill/bill_Accept_account/' . $id . '/');
        $data['action2'] =  site_url('bill/accountbill/FinalSubmit/' . $id);
        $items = $this->billaccountmodel->cost_center($id)->result();
        $data['table'] =			$this->cost_table($items);

        $data['page'] = '/billView/billAccept'; //add page name as a parameter
        $this->load->view('index', $data);
    }

    public function UpdateAccountHead($id1, $id2)
    {
        $this->billaccountmodel->update_account_head($id2);

     
        
        redirect('bill/accountbill/bill_Accept_account/'.$id1);
    }

    public function ChangeAccountHead($id1, $id2)
    {
        $this->billaccountmodel->change_account_head($id2);

     
        
        redirect('bill/accountbill/bill_Accept_account/'.$id1);
    }

    public function cost_table($items)
    {
        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('No', 'Company&nbsp;', 'Business&nbsp;Area&nbsp;', 'Account&nbsp;Head&nbsp;', 'Profit&nbsp;Center&nbsp;', 'Cost&nbsp;Centre&nbsp;', 'Internal&nbsp;Order:&nbsp;', 'Amount&nbsp;BDT', 'Remark', 'Action');

        //$i = 0 + $offset;
        $sl = 1;
        foreach ($items as $row) {
            $del = '&nbsp;' . anchor('bill/accountbill/deleteCost/' . $row->id . '/' . $row->doc_id, 'delete', array('class' => 'delete', 'onclick' => "return confirm('Are you sure want to delete?')"));


            $account_head=$row->account_head;


            if (strtolower($this->session->userdata('username'))=='hillac1' and strlen($row->account_head)==0) {
                $acthead=$this->billaccountmodel->account_head();
                $action_head=site_url('bill/accountbill/UpdateAccountHead/'.$row->doc_id.'/' .$row->id);
                $account_head="<form action='". $action_head."' method='post'><select name='head'>";
                foreach ($acthead->result() as $acthrow) {
                    $account_head.= '<option value="'.$acthrow->account.'##'.$acthrow->code.'">'.$acthrow->account.'##'.$acthrow->code.'</option>';
                }

                $account_head.="</select><input type='submit' value='update'></form>";
            } elseif (strtolower($this->session->userdata('username'))=='hillac1' and strlen($row->account_head)>1) {
                $action_head=site_url('bill/accountbill/ChangeAccountHead/'.$row->doc_id.'/' .$row->id);
                $account_head=$row->account_head."</br><form action='". $action_head."' method='post'>";
                $account_head.="<input type='submit' value='change'></form>";
            } else {
                $account_head=$row->account_head;
            }
            $this->table->add_row(
                $sl,
                $row->vCompany,
                $row->area,
                $account_head,
                $row->profit_center,
                $row->cost_id . "&nbsp;##&nbsp;" . $row->cost_text,
                $row->Order . "&nbsp;" . $row->Description,
                $row->divide_amount,
                $row->remark,
                $del
            );
            $sl = $sl + 1;
        }
        return $this->table->generate();
    }


    public function FinalSubmit($id)
    {
        $billtag = $this->billaccountmodel->get_by_id($id)->row();
        $comp = array(1, 3, 5, 7, 10);

        $hcomp = array(4, 12, 22, 23);
        $azcomp = array(2, 6, 8, 14, 9, 18, 21, 24);

        if (in_array($billtag->company_id, $comp)) {
            $ac_boss = $this->billaccountmodel->getacboss();

            if ($ac_boss == 1) {
                $this->billaccountmodel->update(
                    $id,
                    array(
                        'account_comment' => $this->input->post('comment'), 'account_pass' => 1,	'account_head_pass' => 1, 'step_status' => 5
                    )
                );
            } else {
                $this->billaccountmodel->update(
                    $id,
                    array(
                        'account_comment' => $this->input->post('comment'), 'account_pass' => 1
                    )
                );
            }

            $this->billaccountmodel->action_doc(
                array(
                    'doc_id' => $id, 'action' => 'accepted', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
                )
            );
        } elseif (in_array($billtag->company_id, $azcomp)) {
            $this->billaccountmodel->update(
                $id,
                array(
                    'account_comment' => $this->input->post('comment'), 'account_pass' => 1,	'account_head_pass' => 1, 'step_status' => 5
                )
            );

            $this->billaccountmodel->action_doc(
                array(
                    'doc_id' => $id, 'action' => 'accepted', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
                )
            );
        } elseif (in_array($billtag->company_id, $hcomp)) {
            $this->billaccountmodel->update(
                $id,
                array(
                    'account_comment' => $this->input->post('comment'), 'account_pass' => 1,	'account_head_pass' => 1, 'step_status' => 5, 'hold_by' => 'account', 'az_status' => 1,	'hill_status' => 1
                )
            );

            $this->billaccountmodel->action_doc(
                array(
                    'doc_id' => $id, 'action' => 'Released', 'user_id' => $this->session->userdata('username'), 'comment' => $this->input->post('comment')
                )
            );
        }
        //,'authority_by' => 2405

        redirect('bill/accountbill/billList/0/accept');
    }


    public function adjustList($type = 'Adjustment', $message = '', $printid = 0)
    {
        // offset


        //Search
        $data['action'] = site_url('bill/costbill/searchbill');
        $data['title'] = "bill List";
        // set user message
        $data['message'] = $message;



        if ($message == 'clear') {
            $data['message'] = "<div class='success' align=left>Clear&nbsp;Successful..!!</div>";
        }




        // load data
        $bill = $this->billaccountmodel->adjust_bill_list($type)->result();


        $data['table'] = $this->adjust_table($bill);
        $data['page'] = '/billView/adjustList'; //add page name as a parameter
        $data['userlevel'] = $this->session->userdata('authlevel');
        $this->load->view('index', $data);
    }



    public function adjust_table($bill)
    {
        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('id', 'billing&nbsp;date', 'location', 'description', 'Suggested Cheque', 'Net&nbsp;Pay', 'Submitted&nbsp;by', 'status', 'action', 'documents');

        $status = "";

        //$i = 0 + $offset;
        $sl = 1;
        foreach ($bill as $row) {
            if ($row->step_status == 5) {
                $status = "Still Not Clear";
            }


            if ($row->supervise_cancel == 1) {
                $status = "Cancel by Supervisor";
            } elseif ($row->authority_cancel == 1) {
                $status = "Cancel by Authority";
            } elseif ($row->audit_cancel == 1) {
                $status = "Cancel by Audit";
            }


            $viewbilldoc = "";/*
        $docsl=1;
        $pchk="start";
        $pcount=1;

        if($row->bill_type=="vendor" or $row->bill_type=="distribution"){
        //$billdoc=$this->billcostmodel->get_documents($row->id);
        $billdoc=$this->billmodel->get_special_documents($row->id);
        foreach($billdoc->result() as $rows)
                    {


                    if(strlen($rows->doc_file)<5)
                            $viewbilldoc=$viewbilldoc.anchor('reports/reports/vendor_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->doc_file,'document-'.$docsl,array('class'=>'view','target'=>'about_blank'));
                            else $viewbilldoc=	$viewbilldoc.'<a href="'.base_url().'index.php/bill/accountbill/url/'.$rows->doc_file.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';
                        $docsl=$docsl+1;
                    }

        }else if($row->bill_type=="general"){
        $billdoc=$this->generalmodel->get_special_documents($row->id);
        foreach($billdoc->result() as $rows)
                    {

                        if(strlen($rows->doc_file)<5)
                    $report=base_url().'index.php/reports/reports/order_material_list/'.$rows->doc_id.'/'.$rows->detail_id.'/'.$rows->particular.'/'.$rows->doc_file;
                    else $report=base_url().'index.php/bill/accountbill/url/'.$rows->doc_file;


                    if($pchk=="start"){$viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
                else if ($pchk==$rows->detail_id){ $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
                else {$pcount=$pcount+1; $viewbilldoc=	$viewbilldoc.'particular--'.$pcount.'&nbsp<a href="'.$report.'" target="about_blank" class="view">document-'.$docsl.'</a><br/>';}
                $docsl=$docsl+1;

                $pchk=$rows->detail_id;
                    }

        }

        */

            $doctable = "<div id='" . $row->id . "'  class='divcost' ><font color='#FF000'><b>Double click here to view documents.</b></font></div>";

            if ($row->supervise_cancel == 0 and $row->authority_cancel == 0 and $row->audit_cancel == 0) {
                $sap = anchor('bill/accountbill/sap/' . $row->id . '/' . $row->payment_type, 'Sap&nbsp;Entry', array('class' => 'add'));
            } else {
                $sap = "";
            }



            if ($row->bill_type == "vendor") {
                $viewbill = anchor('reports/reports/view_bill/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
            } elseif ($row->bill_type == "distribution") {
                $viewbill = anchor('reports/reports/view_distribution/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
                $viewhistory = '';
            } else {
                $viewbill = anchor('reports/reports/view_general/' . $row->id, 'view&nbsp;bill', array('class' => 'view', 'target' => 'about_blank'));
            }




            $sl = 0;





            $Cheque_ready = "";

            if ($row->payment_type == "Cheque"  and $row->check_ready == 0) {
                $Cheque_ready = anchor('bill/costbill/Cheque_ready/' . $row->id, 'Check&nbsp;Ready', array('class' => 'add', 'onclick' => "return confirm('Are you sure that Cheque is ready?')"));
            }




            $viewaction = anchor('reports/reports/view_action_bill/' . $row->id, 'view&nbsp;step', array('class' => 'view', 'target' => 'about_blank'));
            $loc = "";
            if ($row->loc == 1) {
                $loc = "Chittagong Head Office";
            } elseif ($row->loc == 2) {
                $loc = "Dhaka Head Office";
            } elseif ($row->loc == 3) {
                $loc = "Mohakhali Office";
            }
            $this->table->add_row(
                $row->id,
                $row->bill_date,
                $loc,
                $row->bill_description,
                $row->suggested_cheque,
                ($row->amount - $row->advance - $row->tds - $row->vds) . "&nbsp;BDT",
                $row->empName,
                $status,
                $viewbill . "</br>" . $viewaction . "</br>" . $sap . "</br>" . $Cheque_ready,
                $doctable
            );
            $sl = $sl + 1;
        }
        return $this->table->generate();
    }

    public function sap($id, $type)
    {

        // set common properties


        $this->_set_sap_fields();
        $this->_set_sap_rules();


        // run validation
        if ($this->validation->run() == false) {
            $data['message'] = '';
        } else {
            // save data
            $data = $this->makeSapQuery();

            //print_r($request);
            $this->billcostmodel->update($id, $data);

            // set user message

            $data['message'] = '<div class="success" align=left>Entry Successful..</div>';

            $this->billcostmodel->action_doc(
                array(
                    'doc_id' => $id, 'action' => 'sap entry', 'user_id' => $this->session->userdata('username')
                )
            );


            redirect('bill/accountbill/adjustList/' . $type . '/clear/' . $id);
            //$this->load->view('employee/leave/adddetails/'.$id.'/'.$date);
        }
        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['title'] = 'SAP Entry Form';
        $data['action'] =  site_url('bill/accountbill/sap/' . $id . '/' . $type . '/');
        $data['page'] = '/billView/billSAP'; //add page name as a parameter
        $this->load->view('index', $data);
    }


    public function _set_sap_fields()
    {
        $fields['sap'] = 'Sap';


        $this->validation->set_fields($fields);
    }

    public function _set_sap_rules()
    {
        $rules['sap'] = 'trim|required';

        $this->validation->set_rules($rules);
        $this->validation->set_message('required', '* required');
        $this->validation->set_error_delimiters('<p class="error">', '</p>');
    }

    public function makeSapQuery()
    {
        return $member = array(
            'sap_id' => $this->input->post('sap'),
            'step_status' => 6
        );
    }

    public function url($url1, $url2)
    {
        //redirect('ftp://billmaster:stargold@192.168.1.117/Common_share/'.$url, 'refresh');
        $location = 'location: ftp://bill:bill007@192.168.1.117/BILL/' . $url1 . '/' . $url2;
        $chkjpg = strtoupper($url2);
        if (strpos($chkjpg, 'JPG')) {

            //header('Content-Type: image/jpeg');

            echo  '<img style="width:900px;" src="data:image/jpeg;Base64,' . base64_encode(file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/" . $url1 . '/' . $url2)) . '"/>';
        }/*
        else if(strpos( $chkjpg , 'PDF' ) )
        {

            //header('Content-Type: image/jpeg');


            echo  '<embed type="application/pdf" src="'.base64_encode(file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/".$url1.'/'.$url2)).'"/>';
        }*/ else {
            // header($location);

            $this->load->helper('download');

            $data = file_get_contents("ftp://bill:bill007@192.168.1.117/BILL/" . $url1 . '/' . $url2);
            $name = $url2;

            force_download($name, $data);
        }
    }





    public function advanceList($offset = 0, $message = '')
    {
        // offset
        $uri_segment = 4;
        $offset = $this->uri->segment($uri_segment);

        //Search
        $data['action'] = site_url('bill/accountbill/searchAdvance');
        $data['title'] = "Advance List";
        // set user message
        $data['message'] = $message;

        if ($message == 'pass') {
            $data['message'] = "<div class='success' align=left>Submitted Successful..!!</div>";
        }


        // load data
        $bill = $this->advancesupmodel->get_paged_list_account($this->limit, $offset)->result();

        // generate pagination
        $this->load->library('pagination');
        $config['base_url'] = site_url('bill/accountbill/advanceList/');
        $number_of_rows = $this->advancesupmodel->count_all_account();
        $config['total_rows'] = $number_of_rows;
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = $uri_segment;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['totalrecord'] = $number_of_rows;

        $data['table'] = $this->advance_table($bill);
        $data['page'] = '/billView/advanceList'; //add page name as a parameter
        $data['userlevel'] = $this->session->userdata('authlevel');
        $this->load->view('index', $data);
    }


    public function searchAdvance()
    {

        //Search
        $data['action'] = site_url('bill/accountbill/searchAdvance');
        ;
        $data['title'] = "Advance List";
        // set user message
        $data['message'] = '';

        // load data
        $bill = $this->advancesupmodel->get_search_by_id_account($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')))->result();

        // generate pagination
        $this->load->library('pagination');
        $data['pagination'] = '';
        $data['totalrecord'] = $this->advancesupmodel->count_search_account($this->mkDate($this->input->post('from')), $this->mkDate($this->input->post('to')));

        $data['table'] = $this->advance_table($bill);
        $data['page'] = '/billView/advanceList'; //add page name as a parameter
        $data['userlevel'] = $this->session->userdata('authlevel');
        $this->load->view('index', $data);
    }




    public function advance_table($bill)
    {
        // generate table data
        $this->load->library('table');
        $this->table->set_empty("&nbsp;");
        $this->table->set_heading('id', 'advance&nbsp;date', 'location', 'description', 'Net&nbsp;Pay', 'Created&nbsp;by', 'Supervised&nbsp;by', 'status', 'action', 'Documents');

        $status = "";

        //$i = 0 + $offset;
        $sl = 1;
        foreach ($bill as $row) {
            if ($row->step_status == 3) {
                $status = "Submitted to Accounts";
            } elseif ($row->step_status == 4) {
                $status = "Accounts Clear";
            } elseif ($row->step_status == 5) {
                $status = "Payment Made";
            }









            $viewbill = anchor('reports/reports/view_advance/' . $row->id, 'view&nbsp;advance', array('class' => 'view', 'target' => 'about_blank'));
            //$viewhistory=anchor('reports/reports/view_details_history/'.$row->id,'view&nbsp;history',array('class'=>'view','target'=>'about_blank'));






            $viewbilldoc = "";
            $docsl = 1;
            $pchk = "start";
            $pcount = 1;


            $billdoc = $this->advancesupmodel->get_special_documents($row->id);
            foreach ($billdoc->result() as $rows) {
                if (strlen($rows->doc_file) < 5) {
                    $report = base_url() . 'index.php/reports/reports/order_material_list/' . $rows->advance_id . '/' . $rows->advance_details_id . '/' . $rows->particular . '/' . $rows->doc_file;
                } else {
                    $report = base_url() . 'index.php/bill/accountbill/url/' . $rows->doc_file;
                } //'ftp://billmaster:stargold@192.168.1.117/Common_share/'.$rows->doc_file;

                if ($pchk == "start") {
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                } elseif ($pchk == $rows->advance_details_id) {
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                } else {
                    $pcount = $pcount + 1;
                    $viewbilldoc =	$viewbilldoc . 'particular--' . $pcount . '&nbsp<a href="' . $report . '" target="about_blank" class="view">document-' . $docsl . '</a><br/>';
                }
                $docsl = $docsl + 1;

                $pchk = $rows->advance_details_id;
            }




            if ($row->check_ready == 1) {
                $status = "&nbsp;Check&nbsp;is&nbsp;Ready&nbsp;";
            }



            if ($row->step_status == 3) {
                $pass = anchor('bill/acadvancebill/bill_Accept_account/' . $row->id, 'pass', array('class' => 'add'));
            } else {
                $pass = "";
            }

            if ($row->cancel_staus == 1) {
                $status = "&nbsp;Canceled&nbsp;";
            }

            $loc = "";
            if ($row->loc == 1) {
                $loc = "Chittagong Head Office";
            } elseif ($row->loc == 2) {
                $loc = "Dhaka Office";
            } elseif ($row->loc == 3) {
                $loc = "Mohakhali Office";
            }
            $this->table->add_row(
                $row->id,
                $row->advance_date,
                $loc,
                $row->advance_description,
                $row->amount,
                $row->createdName,
                $row->superviseName,
                $status,
                $viewbill . "<br/>" . $pass,
                $viewbilldoc
            );
            $sl = $sl + 1;
        }
        return $this->table->generate();
    }


    public function advancePass($id)
    {
        $data['message'] = '';
        $data['comment'] = '';
        $data['userlevel'] = $this->session->userdata('authlevel');
        $data['title'] = 'Advance Pass';
        $data['action'] =  site_url('bill/accountbill/advancePassAdd/' . $id . '/');
        $data['page'] = '/billView/comment'; //add page name as a parameter
        $this->load->view('index', $data);
    }


    public function advancePassAdd($id)
    {
        $com = $this->advancesupmodel->get_by_id($id)->row()->company;

        if ($com == 4 or $com == 12 or $com == 22 or $com == 23) {
            $this->advancesupmodel->update(
                $id,
                array(
                    'step_status' => 2,
                    'authority_by' => 2346,
                    'hold_by' => 2346
                )
            );

            $this->advancesupmodel->action_doc(
                array(
                    'advance_id' => $id, 'str_action' => 'checked', 'user_id' => $this->session->userdata('username'), 'remarks' => $this->input->post('comment')
                )
            );
        } else {
            $this->advancesupmodel->update(
                $id,
                array(
                    'step_status' => 4

                )
            );

            $this->advancesupmodel->action_doc(
                array(
                    'advance_id' => $id, 'str_action' => 'accepted', 'user_id' => $this->session->userdata('username'), 'remarks' => $this->input->post('comment')
                )
            );
        }

        redirect('bill/accountbill/advanceList/0/pass');
    }
}
