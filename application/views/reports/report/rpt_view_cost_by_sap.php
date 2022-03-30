
<?php if($options == '2'){?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report</title>
</head>
<body> 
<?php } elseif($options == '3') {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=CostAllocation.xls;");
header("Content-Type: application/ms-excel");
header("Pragma: no-cache");
header("Expires: 0");
}?>

	<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Bill Processing System</b></td></tr> 
	</table>

	

	<?php 


if($results->result()){

?>


<br/>
<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Cost Allocation</b></td></tr> 
	</table>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>SL</b></td>
			<td align="Center"><b>Bill&nbsp;ID</b></td>
			<td align="Center"><b>Company&nbsp;</b></td>
			<td align="Center"><b>Business&nbsp;Area&nbsp;</b></td>
			<td align="Center"><b>Account&nbsp;Head&nbsp;</b></td>
			<td align="Center"><b>Profit&nbsp;Center&nbsp;</b></td>
			<td align="Center"><b>Cost&nbsp;Centre&nbsp;</b></td>
			<td align="Center"><b>Internal&nbsp;Order&nbsp;</b></td>
			<td align="Center"><b>SAP&nbsp;ID&nbsp;</b></td>
			<td align="Center"><b>Vendor&nbsp;</b></td>
			<td align="Center"><b>Amount&nbsp;BDT</b></td>
			<td align="Center"><b>Remark</b></td>				
		</tr> 
	<?php 
	$sl=0;

	foreach ($results->result() as $mydata2): 
	$sl=$sl+1;
	?>	

		<tr>
		<td align="center"><b><?php echo $sl ?></b></td>
		<td align="center"><b><?php echo $mydata2->doc_id ?></b></td>
		<td align="center"><b><?php echo $mydata2->vCompany ?></b></td>
		<td align="center"><b><?php echo $mydata2->area ?></b></td>
		<td align="center"><b><?php echo $mydata2->account_head ?></b></td>
		<td align="center"><b><?php echo $mydata2->profit_center ?></b></td>
		<td align="center"><b><?php echo $mydata2->cost_id."&nbsp;##&nbsp;".$mydata2->cost_text ?></b></td>
		<td align="center"><b><?php echo $mydata2->Order."&nbsp;".$mydata2->Description ?></b></td>
		<td align="center"><b><?php echo $mydata2->sap_id ?></b></td>
		<td align="center"><b><?php echo $mydata2->vendor ?></b></td>
		<td align="center"><b><?php echo $mydata2->divide_amount ?></b></td>
		<td align="center"><b><?php echo $mydata2->remark ?></b></td>
		</tr>	
	<?php  endforeach; ?>

	</table>
	

	<?php }else echo "No Record Found";

?>

<br/>




<?php


	if($options == '2'){?>		
	</body>
</html>
<?php }?>