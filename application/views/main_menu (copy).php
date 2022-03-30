<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset='utf-8'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo base_url(); ?>style/styles.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="<?php echo base_url(); ?>script/.js"></script>
	<title>PAYROLL</title>
	<!--
<style>
ul {
  font-family: Arial, Verdana;
  font-size: 14px;
  margin: 0;
  padding: 0;
  list-style: none;
}
ul li {
  display: block;
  position: relative;
  float: left;
}
li ul { display: none; }
ul li a {
  display: block;
  text-decoration: none;
  color: #ffffff;
  border-top: 1px solid #ffffff;
  padding: 5px 15px 5px 15px;
  background: #2C5463;
  margin-left: 1px;
  white-space: nowrap;
}
ul li a:hover { background: #617F8A; }
li:hover ul {
  display: block;
  position: absolute;
}
li:hover li {
  float: none;
  font-size: 11px;
}
li:hover a { background: #617F8A; }
li:hover li a:hover { background: #95A9B1; }

</style>
-->

	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<script type="text/javascript">
		function poponload(url) {

			try {
				if (testwindow != null) {

					if (testwindow.closed == false) {
						alert('Window already opened!');
					} {
						testwindow = window.open(url + "index.php/message/message/", "My Message", "location=1,status=1,scrollbars=no,width=400,height=400,resizable=0, menubar=0,toolbar=0");
						testwindow.moveTo(0, 0);
					}
				} else {
					testwindow = window.open(url + "index.php/message/message/", "My Message", "location=1,status=1,scrollbars=no,width=400,height=400,resizable=0, menubar=0,toolbar=0");
					testwindow.moveTo(0, 0);
				}
			} catch (ex) {

				testwindow = window.open(url + "index.php/message/message/", "My Message", "location=1,status=1,scrollbars=no,width=400,height=400,resizable=0, menubar=0,toolbar=0");
				testwindow.moveTo(0, 0);

			}



		}
	</script>
</head>

<body>
	<div id="header-wrap">
		<div class="cssmenu">
			<ul>

				<!-- Begin Simple Item Without Drop -->
				<li class="">
					<a href="<?php echo base_url(); ?>index.php/controlpanel">Home</a>
				</li>
				<!-- End Simple Item Without Drop -->


				<li>
					<a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Desk&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<!-- Begin Sub-Menu -->
					<ul>
						<li>
							<a href='#' onclick="javascript: poponload('<?php echo base_url(); ?>')"><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/Message.png' border=0 hspace=3>Message Box</a>

						</li>
						<li>
							<a href='<?php echo base_url(); ?>index.php/users/changedpassword/changePassword'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/switch.gif' border=0 hspace=3>Change&nbsp;Password</a>

						</li>
						<li>
							<a href='<?php echo base_url(); ?>index.php/login/login/logout' target='_top'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/logoff.gif' border=0 hspace=3>Logout</a>

						</li>
					</ul>
					<!-- End Sub-Menu -->
				</li>

				<?php if ($userlevel == 5 or $userlevel == 6) { ?>
					<li>
						<a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Entry&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						<!-- Begin Sub-Menu -->
						<ul>


							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/bill/'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Claim&nbsp;Vendor&nbsp;Bill&nbsp;</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/general/'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Claim&nbsp;General&nbsp;Bill&nbsp;</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/distribution/'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Claim&nbsp;Distribution&nbsp;Bill&nbsp;</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/requisition/'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Material&nbsp;Requisition&nbsp;</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/vendorforreq/'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Claim&nbsp;Vendor&nbsp;Against&nbsp;Requisition&nbsp;</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/billforreq/'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Claim&nbsp;General&nbsp;Bill&nbsp;Against&nbsp;Requisition&nbsp;</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/iou/'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Claim&nbsp;IOU&nbsp;</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/advance/'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Claim&nbsp;Advance&nbsp;</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/request/request/RequestForConveyance'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/reports.png' border=0 hspace=3>Conveyance Apply</a>
							</li>


						</ul>
						<!-- End Sub-Menu -->
					</li>
				<?php } ?>

				<li>
					<a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;List&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<!-- Begin Sub-Menu -->
					<ul>
						<?php if ($userlevel == 5 or $userlevel == 6) { ?>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/bill/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>My&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/requisition/requisitionList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>My&nbsp;Material&nbsp;Requisition&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/iou/iouList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>My&nbsp;IOU&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/advance/advanceList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>My&nbsp;Advance&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/request/request/Conveyance_Leave_status'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>My&nbsp;Conveyance&nbsp;Status</a>
							</li>

						<?php } ?>
						<?php if ($userlevel == 6) { ?>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/supervisebill/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/reqsup/requisitionList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Material&nbsp;Requisition&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/iousup/iouList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;IOU&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/advancesup/advanceList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/request/request/Dep_Request_Conveyance'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Conveyance Request</a>
							</li>

						<?php } ?>


						<?php if (
							$userlevel == 1  or  $this->session->userdata('username') == 1109 or $this->session->userdata('username') == 1775 or $this->session->userdata('username') == 1508 or $this->session->userdata('username') == 1091  or $this->session->userdata('username') == 1017 or $this->session->userdata('username') == 1936 or $this->session->userdata('username') == 1580  or $this->session->userdata('username') == 2346 or $this->session->userdata('username') == 2405
							or $this->session->userdata('username') == 1087
						) { ?>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/admin/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>All&nbsp;Bill&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/admin/requisitionList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>All&nbsp;Requisition&nbsp;List</a>
							</li>

						<?php } ?>











						<?php if ($this->session->userdata('username') == 2245) { ?>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/admin/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>All&nbsp;Bill&nbsp;List</a>
							</li>



						<?php } ?>







						<?php if ($userlevel == 666) { ?>

							<!--
				<li>
				<a href='<?php echo base_url(); ?>index.php/bill/external/billList' ><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>All&nbsp;Bill&nbsp;List</a>
				</li>
				-->

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/admin/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>All&nbsp;Bill&nbsp;List</a>
							</li>


						<?php } ?>




						<?php if ($userlevel == 9) { ?>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/auditbill/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/auditbill/parkList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Park&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/admin/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>All&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/admin/requisitionList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>All&nbsp;Requisition&nbsp;List</a>
							</li>

						<?php } ?>

						<?php if ($userlevel == 7) { ?>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/accountbill/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;List</a>
							</li>
							<?php if (strtolower($this->session->userdata('username')) != 'haldahead' and   strtolower($this->session->userdata('username')) != 'azhead'    and  strtolower($this->session->userdata('username')) != 'hillac2' and  strtolower($this->session->userdata('username')) != 'hillac3') { ?>
								<li>
									<a href='<?php echo base_url(); ?>index.php/bill/accountbill/advanceList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;List</a>
								</li>
							<?php } ?>
							<?php if (strtolower($this->session->userdata('username')) != 'amar' and strtolower($this->session->userdata('username')) != 'sharma' and strtolower($this->session->userdata('username')) != 'aman' and strtolower($this->session->userdata('username')) != 'haldahead'     and   strtolower($this->session->userdata('username')) != 'azhead') {  ?>
								<li>
									<a href='<?php echo base_url(); ?>index.php/bill/admin/billList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>All&nbsp;Bill&nbsp;List</a>
								</li>
							<?php } ?>

							<?php if (strtolower($this->session->userdata('username')) != 'haldahead'  and   strtolower($this->session->userdata('username')) != 'azhead') { ?>

								<!-- <li>
				<a href='<?php echo base_url(); ?>index.php/bill/admin/advancePaymentList' ><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Payment&nbsp;Date&nbsp;Wise&nbsp;Advance&nbsp;List</a>
				</li>
				
				
				<li>
				<a href='<?php echo base_url(); ?>index.php/bill/admin/billPaymentList' ><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Payment&nbsp;Date&nbsp;Wise&nbsp;Bill&nbsp;List</a>
				</li> -->

							<?php } ?>

							<?php if (strtolower($this->session->userdata('username')) == 'feroz' or strtolower($this->session->userdata('username')) == 'gep'  or   strtolower($this->session->userdata('username')) == 'aman' or   strtolower($this->session->userdata('username')) == 'amar' or   strtolower($this->session->userdata('username')) == 'relacc' or   strtolower($this->session->userdata('username')) == 'sharma'  or   strtolower($this->session->userdata('username')) == 'biru' or strtolower($this->session->userdata('username')) == 'dhkaccount' or strtolower($this->session->userdata('username')) == 'sudhir' or strtolower($this->session->userdata('username')) == 'dipankar'   or strtolower($this->session->userdata('username')) == 'account'  or strtolower($this->session->userdata('username')) == 'sanjit'  or strtolower($this->session->userdata('username')) == 'pdhlac' or strtolower($this->session->userdata('username')) == 'pdhlac2' or strtolower($this->session->userdata('username')) == 'pdhlac3'  or strtolower($this->session->userdata('username')) == 'pnlaccount' or strtolower($this->session->userdata('username')) == 'hillac1' or strtolower($this->session->userdata('username')) == 'hillac2' or strtolower($this->session->userdata('username')) == 'hillac3'  or strtolower($this->session->userdata('username')) == 'nizam'  or strtolower($this->session->userdata('username')) == 'bablu' or strtolower($this->session->userdata('username')) == 'azaccount') { ?>
								<li>
									<a href='<?php echo base_url(); ?>index.php/bill/accountbill/adjustList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Adjust&nbsp;Bill&nbsp;List</a>
								</li>
							<?php }

							if (strtolower($this->session->userdata('username')) != 'accounthead'  and strtolower($this->session->userdata('username')) != 'ctgaccount') {
							?>



								<li>
									<a href='<?php echo base_url(); ?>index.php/request/request/Audit_Request_Conveyance'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Conveyance Request</a>
								</li>



								<li>
									<a href='<?php echo base_url(); ?>index.php/request/request/Search_Audit_Request_Conveyance/1/1'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Search Conveyance Request</a>
								</li>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/Cost_Pending_Conveyance'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending Conveyance Request</a>
								</li>



						<?php }
						} ?>

						<?php if ($userlevel == 8) { ?>
							<li>
								<a href='<?php echo base_url(); ?>index.php/request/request/Cost_Request_Conveyance'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>&nbsp;Conveyance Request</a>
							</li>

							<?php if (strtolower($this->session->userdata('username')) != 'pdhlcost') { ?>
								<li>
									<a href='<?php echo base_url(); ?>index.php/request/request/Quick_Pay_Conveyance'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Quick&nbsp;Pay&nbsp;Conveyance</a>
								</li>
							<?php } ?>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/billList/Cheque'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Cheque&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/billList/Cash'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Cash&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/billList/DD'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>DD&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/billList/TT'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>TT&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/billList/Pay-Order'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pay-Order&nbsp;Bill&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/iouList'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>IOU&nbsp;List</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/advanceList/Cheque'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Cheque&nbsp;Advance&nbsp;List</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/advanceList/Cash'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Cash&nbsp;Advance&nbsp;List</a>
							</li>

						<?php } ?>

						<?php if (strtolower($this->session->userdata('username')) == 'arif' or $this->session->userdata('username') == '2346' or $this->session->userdata('username') == '2405') {  ?>
							<li>
								<a href='<?php echo base_url(); ?>index.php/bill/costbill/pending_date'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Unassign&nbsp;Cheque&nbsp;Date&nbsp;List</a>
							</li>
							<!--
				<li>
						<a href='<?php echo base_url(); ?>index.php/bill/costbill/pending_date_agm_park' ><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Finance&nbsp;Park&nbsp;Bill&nbsp;List</a>
				</li> -->
						<?php } ?>

					</ul>
					<!-- End Sub-Menu -->
				</li>





				<li>
					<a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reports&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php if ($userlevel != 666) { ?>

						<!-- Begin Sub-Menu -->
						<ul>
							<li>
								<a href='<?php echo base_url(); ?>index.php/reports/reports/bill_Report'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Bill&nbsp;Report&nbsp;By&nbsp;Bill_ID</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/reports/reports/bill_Report_For_Sap'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Bill&nbsp;Report&nbsp;By&nbsp;SAP_ID</a>
							</li>
							<li>
								<a href='<?php echo base_url(); ?>index.php/reports/reports/bill_Report_by_sap_year'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Bill&nbsp;Report&nbsp;By&nbsp;SAP_ID&nbsp;and&nbsp;Year</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/reports/reports/Advance_Report'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Advance&nbsp;Report&nbsp;By&nbsp;Advance_ID</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/reports/reports/Advance_Report_For_Sap'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Advance&nbsp;Report&nbsp;By&nbsp;SAP_ID</a>
							</li>

							<li>
								<a href='<?php echo base_url(); ?>index.php/reports/reports/scan_Report'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Bill&nbsp;Report&nbsp;By&nbsp;Scan_ID</a>
							</li>

							<?php if (strtolower($this->session->userdata('username')) == 'account' or  strtolower($this->session->userdata('username')) == 'pdhlac' or  strtolower($this->session->userdata('username')) == 'pdhlac2') { ?>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/bill_Report_For_WithoutPO'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Bill&nbsp;Report&nbsp;By&nbsp;Without&nbsp;PO&nbsp;NO</a>
								</li>
							<?php }
							?>


							<?php if (
								strtolower($this->session->userdata('username')) == 'hillac1' or strtolower($this->session->userdata('username')) == 'hillac2'
								or strtolower($this->session->userdata('username')) == 'hillac3'
							) { ?>
								<li>
									<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_adjust/4'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Adjustment #Halda Valley Tea CO. Ltd</a>
								</li>

								<li>
									<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_adjust/12'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Adjustment #Hill Plantation Ltd</a>
								</li>

								<li>
									<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_adjust/22'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Adjustment #Nuovo Renewable Energy Ltd</a>
								</li>

								<li>
									<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_adjust/23'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Adjustment #Halda Solid Wood Flooring Ltd</a>
								</li>



							<?php }
							?>

							<?php if (strtolower($this->session->userdata('username')) == 'accounthead') { ?>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/payment_date_wise_bill'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Bill&nbsp;Report&nbsp;Payment&nbsp;Date&nbsp;Wise</a>
								</li>
							<?php }
							?>

							<?php if (strtolower($this->session->userdata('username')) == '2346') { ?>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/pending_payment_date_unit_bill'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Bill&nbsp;Pending&nbsp;for&nbsp;Payment&nbsp;Date&nbsp;Wise</a>
								</li>
							<?php }
							?>


							<?php if ($this->session->userdata('username') == 1091 or $this->session->userdata('username') == 1017 or $this->session->userdata('username') == 2284) { ?>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/bill_Report_For_Req'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Material&nbsp;Requsition&nbsp;Bill&nbsp;Report&nbsp;</a>
								</li>

							<?php } ?>


							<?php if ($userlevel == 5 or $userlevel == 6) { ?>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/my_requisition_by_date_wise'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>My&nbsp;Requisition&nbsp;Date&nbsp;Wise</a>
								</li>

							<?php } ?>


							<?php if ($userlevel == 6) { ?>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/under_my_supervision_bill'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Bill&nbsp;Payment&nbsp;Under&nbsp;My&nbsp;Supervision</a>
								</li>

							<?php } ?>



							<?php if ($userlevel == 1   or  $this->session->userdata('username') == 2283  or  $this->session->userdata('username') == 1109 or $this->session->userdata('username') == 1661  or $this->session->userdata('username') == 2346) { ?>



								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/count_date_wise_bill_doc'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Date&nbsp;Wise&nbsp;Bill&nbsp;Documents&nbsp;Count</a>
								</li>


								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/mat_items_report_by_year'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Items&nbsp;&&nbsp;month&nbsp;wse&nbsp;requistion&nbsp;report&nbsp;Count</a>
								</li>

							<?php } ?>



							<?php if ($userlevel == 8) { ?>




								<?php if (strtolower($this->session->userdata('username')) == 'cost') { ?>



									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/1'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque #Pedrollo. Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/3'>
											<img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque #Pragati</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/7'>
											<img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque #PNL</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/2'>
											<img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque #Polyex Print</a>
									</li>


								<?php } ?>
								<?php if (strtolower($this->session->userdata('username')) == 'gepcost') { ?>
									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/6'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque GEP</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cash/6'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cash GEP</a>
									</li>

								<?php } ?>




								<?php if (strtolower($this->session->userdata('username')) == 'azcost') { ?>
									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/18'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque AZNEO</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cash/18'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cash AZNEO</a>
									</li>

								<?php } ?>



								<?php if (strtolower($this->session->userdata('username')) == 'scost') { ?>
									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/21'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque REL</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cash/21'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cash REL</a>
									</li>

								<?php } ?>





								<?php if (strtolower($this->session->userdata('username')) == 'hillcost') { ?>
									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cash/4'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cash #Halda Valley Tea CO. Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cash/12'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cash #Hill Plantation Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cash/22'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cash #Nuovo Renewable Energy Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cash/23'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cash #Halda Solid Wood Flooring Ltd</a>
									</li>


									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/4'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque #Halda Valley Tea CO. Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/12'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque #Hill Plantation Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/22'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque #Nuovo Renewable Energy Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/23'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque #Halda Solid Wood Flooring Ltd</a>
									</li>



									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/advanceList/Cash/4'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;For&nbsp;Cash #Halda Valley Tea CO. Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/advanceList/Cash/12'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;For&nbsp;Cash #Hill Plantation Ltd</a>
									</li>
									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/advanceList/Cash/22'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;For&nbsp;Cash #Nuovo Renewable Energy Ltd</a>
									</li>
									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/advanceList/Cash/23'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;For&nbsp;Cash #Halda Solid Wood Flooring Ltd</a>
									</li>


									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/advanceList/Cheque/4'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;For&nbsp;Cheque #Halda Valley Tea CO. Ltd</a>
									</li>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/advanceList/Cheque/12'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;For&nbsp;Cheque #Hill Plantation Ltd</a>
									</li>
									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/advanceList/Cheque/22'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;For&nbsp;Cheque #Nuovo Renewable Energy Ltd</a>
									</li>
									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/advanceList/Cheque/23'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Advance&nbsp;For&nbsp;Cheque #Halda Solid Wood Flooring Ltd</a>
									</li>

								<?php } ?>



								<?php if (strtolower($this->session->userdata('username')) == 'scost') { ?>

									<li>
										<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque_com/21'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Cheque&nbsp;Bill&nbsp;For&nbsp;REL</a>
									</li>
								<?php } ?>


								<li>
									<a target="about_blank" href='<?php echo base_url(); ?>index.php/reports/reports/pending_cheque'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Pending&nbsp;Bill&nbsp;For&nbsp;Cheque</a>
								</li>






							<?php } ?>

							<?php if ($userlevel == 1 or  strtolower($this->session->userdata('username')) == 'accounthead' or $this->session->userdata('username') == 1508  or $this->session->userdata('username') == 2346 or $this->session->userdata('username') == 2405) { ?>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/download_advance_rpt'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Download&nbsp;Advance&nbsp;Report</a>
								</li>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/download_iou_rpt'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Download&nbsp;IOU&nbsp;Report</a>
								</li>

							<?php } ?>

							<?php if ($userlevel == 9) { ?>

								<li>
									<a href='<?php echo base_url(); ?>index.php/reports/reports/download_audit_kpi'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/benefitInformation.png' border=0 hspace=3>Download&nbsp;Audit&nbsp;BILL&nbsp;Report</a>
								</li>

							<?php } ?>


						</ul>
						<!-- End Sub-Menu -->
					<?php } ?>
				</li>

				<li>
					<a href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<!-- Begin Sub-Menu -->
					<ul>

						<li>
							<a href='<?php echo base_url(); ?>docs/BillingV6.05.pdf'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/monthlyDepartmentWiseAbsentees.png' border=0 hspace=3>New&nbsp;Update&nbsp;V6.05</a>

						</li>

						<li>
							<a href='<?php echo base_url(); ?>docs/BillingV6.04.pdf'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/monthlyDepartmentWiseAbsentees.png' border=0 hspace=3>New&nbsp;Update&nbsp;V6.04</a>

						</li>

						<li>
							<a href='<?php echo base_url(); ?>docs/BillingV6.03.pdf'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/monthlyDepartmentWiseAbsentees.png' border=0 hspace=3>Release&nbsp;Note&nbsp;V6.03</a>

						</li>


						<li>
							<a href='<?php echo base_url(); ?>docs/BillingV6.02.pdf'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/monthlyDepartmentWiseAbsentees.png' border=0 hspace=3>Release&nbsp;Note&nbsp;V6.02</a>

						</li>

						<li>
							<a href='<?php echo base_url(); ?>docs/BillingV6.01.pdf'><img style='vertical-align: middle' width=15 height=15 src='<?php echo base_url(); ?>/images/menu/monthlyDepartmentWiseAbsentees.png' border=0 hspace=3>Release&nbsp;Note&nbsp;V6.01</a>

						</li>



					</ul>
					<!-- End Sub-Menu -->
				</li>
				</li>


				<!-- End Simple Item Without Drop -->

				<!-- Begin Simple Item Without Drop -->
				<!-- End Simple Item Without Drop -->

			</ul>
		</div>
	</div>
</body>

</html>