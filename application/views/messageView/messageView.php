<HTML>
<HEAD>
<META 
     HTTP-EQUIV="Refresh"
     CONTENT="5" >
	 <link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
</HEAD>
<BODY>
<div class="content"  valign="top" align="left">
<h1 valign="top">My Message</h1>
<div class="data" >
<table >
	
	<?php if($userlevel==6)
	{
	?>
	<tr>
		<td><a  href="<?php echo base_url(); ?>index.php/bill/supervisebill/billList" target="about_blank">Total&nbsp;Pending&nbsp;Bill:</a></td>
		<td><a href="<?php echo base_url(); ?>index.php/bill/supervisebill/billList" class="view" target="about_blank"><?php print($request_bill_for_supervise); ?></a></td>
	</tr>

	<tr>
		<td><a  href="<?php echo base_url(); ?>index.php/bill/reqsup/requisitionList" target="about_blank">Total&nbsp;Pending &nbsp;Requisition :</a></td>
		<td><a href="<?php echo base_url(); ?>index.php/bill/reqsup/requisitionList" class="view" target="about_blank"><?php print($req_pending); ?></a></td>
	</tr>	
	
	<?php
	}
	?>
	

	
	
	<?php if($userlevel==9)
	{
	?>
	<tr>
		<td><a  href="<?php echo base_url(); ?>index.php/bill/auditbill/billList" target="about_blank">Total&nbsp;Pending&nbsp;Bill:</a></td>
		<td><a href="<?php echo base_url(); ?>index.php/bill/auditbill/billList" class="view" target="about_blank"><?php print($request_bill_for_audit_notpark); ?></a></td>
	</tr>
	<tr>
		<td><a  href="<?php echo base_url(); ?>index.php/bill/auditbill/parkList" target="about_blank">Total&nbsp;Parked&nbsp;Bill:</a></td>
		<td><a href="<?php echo base_url(); ?>index.php/bill/auditbill/parkList" class="view" target="about_blank"><?php print($request_bill_for_audit_park); ?></a></td>
	</tr>	
	<?php
	}
	?>
	
</table>
</div></div>
</BODY>


</HTML>

	 
	 
	 
	 