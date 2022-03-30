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
	<tr><td align="Center" ><b>Bill Payment Date Wise</b></td></tr> 
	<tr><td align="Center" ><b>from <?php echo $from; ?>  to <?php echo $to; ?></b></td></tr> 
	</table><br/>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>SL</b></td>
			<td align="Center"><b>Bill-ID</b></td>
			<td align="Center"><b>Bill-Date</b></td>
			<td align="Center"><b>Company</b></td>
			<td align="Center"><b>Created-by</b></td>
			<td align="Center"><b>Supervisor</b></td>
			<td align="Center"><b>Bill-Description</b></td>
			<td align="Center"><b>Amount</b></td>
			<td align="Center"><b>Advance</b></td>
			<td align="Center"><b>TDS</b></td>
			<td align="Center"><b>VDS</b></td>
			<td align="Center"><b>PO-NO</b></td>
			<td align="Center"><b>PO-Date</b></td>
			<td align="Center"><b>GR-No</b></td>
			<td align="Center"><b>Gr-Date</b></td>
			<td align="Center"><b>IV-No</b></td>
			<td align="Center"><b>IV-Date</b></td>
			<td align="Center"><b>Asset-No</b></td>
			<td align="Center"><b>Bill-Type</b></td>
			<td align="Center"><b>Loc</b></td>
			<td align="Center"><b>Payment-Type</b></td>
			<td align="Center"><b>Payment-Date</b></td>
			<td align="Center"><b>Cheque-Name</b></td>
			<td align="Center"><b>Action</b></td>

		</tr> 
	<?php 
	$sl=0;

	foreach ($results->result() as $mydata): 
	$sl=$sl+1;
	$view='&nbsp<a href="'.base_url().'index.php/reports/reports/view_bill_Report_by_ID/'.$mydata->id.'" target="about_blank" class="view">view</a>'.'<br/>';
	?>	

		<tr>
		<td align="center"><b><?php echo $sl ?></b></td>
		<td align="center"><b><?php echo $mydata->id ?></b></td>
		<td align="center"><b><?php echo $mydata->bill_date ?></b></td>
		<td align="center"><b><?php echo $mydata->vCompany ?></b></td>
		<td align="center"><b><?php echo $mydata->created_by ?></b></td>	
		<td align="center"><b><?php echo $mydata->supervise_by ?></b></td>
		<td align="center"><b><?php echo $mydata->bill_description ?></b></td>
		<td align="center"><b><?php echo $mydata->amount ?></b></td>
		<td align="center"><b><?php echo $mydata->advance ?></b></td>
		<td align="center"><b><?php echo $mydata->tds ?></b></td>
		<td align="center"><b><?php echo $mydata->vds ?></b></td>
		<td align="center"><b><?php echo $mydata->po_no ?></b></td>
		<td align="center"><b><?php echo $mydata->po_date ?></b></td>
		<td align="center"><b><?php echo $mydata->gr_no ?></b></td>
		<td align="center"><b><?php echo $mydata->gr_date ?></b></td>
		<td align="center"><b><?php echo $mydata->iv_no ?></b></td>
		<td align="center"><b><?php echo $mydata->iv_date ?></b></td>
		<td align="center"><b><?php echo $mydata->asset_no ?></b></td>
		<td align="center"><b><?php echo $mydata->bill_type ?></b></td>	
		<td align="center"><b><?php echo $mydata->loc ?></b></td>	
		<td align="center"><b><?php echo $mydata->payment_type ?></b></td>	
		<td align="center"><b><?php echo $mydata->payment_made_date ?></b></td>	
		<td align="center"><b><?php echo $mydata->suggested_cheque ?></b></td>	
		
	
		<td align="center"><b><?php echo $view ?></b></td>	
		</tr>	
	<?php  endforeach; ?>

	</table>
	

	<?php }else echo "No Record Found";
	if($options == '2'){?>		
	</body>
</html>
<?php }?>