<?php if($options == '2'){?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report</title>
</head>
<body> 
<?php } elseif($options == '3') {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Salary.xls;");
header("Content-Type: application/ms-excel");
header("Pragma: no-cache");
header("Expires: 0");
}?>





	
<?php 
if($results->result()){

foreach ($results->result() as $mydata2): endforeach;

?>
<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Material Requisition: <?php echo $mydata2->master_id ?></b></td></tr> 
	</table><br/>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>Company:</b></td>
			<td align="Center"><b><?php echo $mydata2->vCompany ?></b></td>
			<td align="Center"><b>Request&nbsp;Date&nbsp;</b></td>
			<td align="Center"><b><?php echo $mydata2->request_date ?></b></td>

		</tr>
	</table>	
	
	<br/>
	
	
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>SL</b></td>
			<td align="Center"><b>Item&nbsp;Name&nbsp;</b></td>
			<td align="Center"><b>SAP&nbsp;Code&nbsp;</b></td>
			<td align="Center"><b>Required&nbsp;</b></td>
			<td align="Center"><b>Quantity&nbsp;Order&nbsp;</b></td>
			<td align="Center"><b>Already&nbsp;Order&nbsp;</b></td>
			<td align="Center"><b>Quantity&nbsp;in&nbsp;Hand&nbsp;</b></td>
			<td align="Center"><b>Unit</b></td>
			<td align="Center"><b>Delivery&nbsp;Date&nbsp;</b></td>
			<td align="Center"><b>Contact&nbsp;Person&nbsp;Details&nbsp;</b></td>
			<td align="Center"><b>Reason&nbsp;For&nbsp;Request&nbsp;</b></td>
			<td align="Center"><b>Remark&nbsp;</b></td>
		</tr> 
	<?php 
	$sl=0;

	foreach ($results->result() as $mydata): 
	$sl=$sl+1;
	$qtyOrder=$mydata->qtyOrder;
	if($qtyOrder=='')$qtyOrder=0;
	?>	

		<tr>
		<td align="center"><b><?php echo $sl ?></b></td>
		<td align="center"><b><?php echo $mydata->item_name ?></b></td>
		<td align="center"><b><?php echo $mydata->req_sap_id ?></b></td>
		<td align="center"><b><?php echo $mydata->qty_req ?></b></td>
		<td align="center" <?php if($qtyOrder!=0)print "bgcolor='yellow'" ?> ><b><?php echo $qtyOrder ?></b></td>
		<td align="center"><b><?php echo ($mydata->alOrder-$qtyOrder) ?></b></td>
		<td align="center"><b><?php echo $mydata->qty_hand ?></b></td>
		<td align="center"><b><?php echo $mydata->item_unit ?></b></td>
		<td align="center"><b><?php echo $mydata->delivery_date ?></b></td>
		<td align="center"><b><?php echo $mydata->contact_details ?></b></td>
		<td align="center"><b><?php echo $mydata->reason_req ?></b></td>
		<td align="center"><b><?php echo $mydata->remarks ?></b></td>
		</tr>	
	<?php  endforeach; ?>

	</table>
	<br/>
	
	<table border="0"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td>
				<table>
					<tr>
						<td><b>Request By</b></td>
					</tr>
					<tr>
						<td>
								<b><?php echo $mydata2->reqname ?></b><br/>
								<b><?php echo $mydata2->reqDesignation ?></b><br/>
								<b><?php echo $mydata2->reqdep ?></b><br/>
						</td>
					</tr>
				</table>
			</td>
			<td align="right">
				<table>
					<tr>
						<td><b>Approved By</b></td>
					</tr>
					<tr>
						<td>
								<?php if($mydata2->approve_status==1){ ?>
								
								<b><?php echo $mydata2->appname ?></b><br/>
								<b><?php echo $mydata2->appDesignation ?></b><br/>
								<b><?php echo $mydata2->appdep ?></b><br/>
								<b>Approved&nbsp;Date:&nbsp;<?php echo $mydata2->approved_date ?></b><br/>
								<?php } ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>


	<?php 
if($results2->result()){

?>
		<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>

			<td align="Center"><b>Date</b></td>
			<td align="Center"><b>Action</td>
			<td align="Center"><b>Name</b></td>
			<td align="Center"><b>Designation</b></td>
			<td align="Center"><b>Comments</b></td>
		</tr> 
		<?php 


	foreach ($results2->result() as $mydata3): 

	?>	

		<tr>

		<td align="center"><b><?php echo $mydata3->action_date ?></b></td>
		<td align="center"><b><?php echo $mydata3->action ?></b></td>
		<td align="center"><b><?php echo $mydata3->vEmpName ?></b></td>
		<td align="center"><b><?php echo $mydata3->vDesignation ?></b></td>
		<td align="center"><b><?php echo $mydata3->comments ?></b></td>

		</tr>	
	<?php  endforeach;

	
	?>

	</table>
	
	

	<?php } }	
	
	

else echo "No Record Found";
	if($options == '2'){?>		
	</body>
</html>
<?php }?>