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



?>
<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Similar Amount Bill</b></td></tr> 
	</table><br/>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>&nbsp;SL&nbsp;</b></td>
			<td align="Center" width="1%"><b>&nbsp;BILL&nbsp;ID&nbsp;</b></td>
			<td align="Center" width="1%"><b>&nbsp;BILL&nbsp;Date&nbsp;</b></td>
			<td align="Center"><b>&nbsp;Company&nbsp;</b></td>
			<td align="Center"><b>&nbsp;Description&nbsp;</b></td>
			<td align="Center"><b>&nbsp;Amount&nbsp;</b></td>
			<td align="Center"><b>&nbsp;Advance&nbsp;</b></td>
			<td align="Center"><b>&nbsp;TDS&nbsp;</b></td>
			<td align="Center"><b>&nbsp;VDS&nbsp;</b></td>	
			<td align="Center"><b>&nbsp;Claimer&nbsp;</b></td>
			<td align="Center"><b>&nbsp;Supervisor&nbsp;</b></td>
		</tr> 
	<?php 
	$sl=0;

	foreach ($results->result() as $mydata): 
	$sl=$sl+1;
	?>	

		<tr>
		<td align="center"><b><?php echo $sl ?></b></td>
		<td align="center"><a target="about_blank2" href='<?php echo base_url(); ?>index.php/reports/reports/view_bill_Report_by_ID/<?php echo $mydata->id; ?>'><b><?php echo $mydata->id ?></b></a></td>
		<td align="center"><b><?php echo $mydata->bill_date ?></b></td>
		<td align="center"><b><?php echo $mydata->vCompany ?></b></td>
		<td align="center"><b><?php echo $mydata->bill_description ?></b></td>
		<td align="center"><b><?php echo $mydata->amount ?></b></td>
		<td align="center"><b><?php echo $mydata->advance ?></b></td>
		<td align="center"><b><?php echo $mydata->tds ?></b></td>
		<td align="center"><b><?php echo $mydata->vds ?></b></td>
		<td align="center"><b><?php echo $mydata->claimer ?></b></td>
		<td align="center"><b><?php echo $mydata->supervisor ?></b></td>

		</tr>	
	<?php  endforeach; ?>

	</table>
	

	<?php }else echo "No Record Found for similar amount for this bill! (Last three months)";
	if($options == '2'){?>		
	</body>
</html>
<?php }?>