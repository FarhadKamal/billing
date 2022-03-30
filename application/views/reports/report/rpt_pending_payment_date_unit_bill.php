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
	<tr><td align="Center" ><b>Bill Pending for Payment, Date Wise</b></td></tr> 
	<tr><td align="Center" ><b>from <?php echo $from; ?>  to <?php echo $to; ?></b></td></tr> 
	</table><br/>
	<div align="center"><b>Cash</b></div>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>	
			<td align="Center"><b>Company</b></td>
			<td align="Center"><b>Amount</b></td>		
		</tr> 
	<?php 
	$sl=0;

	foreach ($results->result() as $mydata): 
	$sl=$sl+1;
	?>	

		<tr>

		<td align="center"><b><?php echo $mydata->vCompany ?></b></td>
		<td align="center"><b><?php echo $mydata->total ?></b></td>
	
		</tr>	
	<?php  endforeach; ?>

	</table>
	
	
	<br/>	<br/>
	
	<div align="center"><b>Cheque</b></div>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>	
			<td align="Center"><b>Company</b></td>
			<td align="Center"><b>Amount</b></td>		
		</tr> 
	<?php 
	$sl=0;

	foreach ($results2->result() as $mydata): 
	$sl=$sl+1;
	?>	

		<tr>

		<td align="center"><b><?php echo $mydata->vCompany ?></b></td>
		<td align="center"><b><?php echo $mydata->total ?></b></td>
	
		</tr>	
	<?php  endforeach; ?>

	</table>
	

	<?php }else echo "No Record Found";
	if($options == '2'){?>		
	</body>
</html>
<?php }?>