<?php if($options == '2'){?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report</title>
</head>
<body> 
<?php } elseif($options == '3') {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Bill.xls;");
header("Content-Type: application/ms-excel");
header("Pragma: no-cache");
header("Expires: 0");
}?>

	
<?php 
if($results->result()){



?>
<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Bill Payment Under my Supervison</b></td></tr> 
	<tr><td align="Center" ><b>from <?php echo $from; ?>  to <?php echo $to; ?></b></td></tr> 
	</table><br/>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>SL</b></td>
			<td align="Center"><b>Requisition ID</b></td>
			<td align="Center"><b>Requisition Date</b></td>
			<td align="Center"><b>Item Name</b></td>
			<td align="Center"><b>Quantity Request</b></td>
			<td align="Center"><b>Contact Details</b></td>
			<td align="Center"><b>Reason Request</b></td>
			<td align="Center"><b>Remarks</b></td>
			<td align="Center"><b>Unit</b></td>

		</tr> 
	<?php 
	$sl=0;

	foreach ($results->result() as $mydata): 
	$sl=$sl+1;
	$view='&nbsp<a href="'.base_url().'index.php/reports/reports/view_bill_Report_by_ID/'.$mydata->id.'" target="about_blank" class="view">view</a>'.'<br/>';
	?>	

		<tr>
		<td align="center"><b><?php echo $sl ?></b></td>
		<td align="center"><b><?php echo $mydata->master_id ?></b></td>
		<td align="center"><b><?php echo $mydata->request_date ?></b></td>
		<td align="center"><b><?php echo $mydata->item_name ?></b></td>
		<td align="center"><b><?php echo $mydata->qty_req ?></b></td>	
		<td align="center"><b><?php echo $mydata->contact_details ?></b></td>
		<td align="center"><b><?php echo $mydata->reason_req ?></b></td>
		<td align="center"><b><?php echo $mydata->remarks ?></b></td>
		<td align="center"><b><?php echo $mydata->item_unit ?></b></td>

		</tr>	
	<?php  endforeach; ?>

	</table>
	

	<?php }else echo "No Record Found";
	if($options == '2'){?>		
	</body>
</html>
<?php }?>