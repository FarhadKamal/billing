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
	<tr><td align="Center" ><b>Material Bill Report</b></td></tr> 
	<tr><td align="Center" ><b>From <?php echo $from;?> To <?php echo $to;?></b></td></tr> 
	</table><br/>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center"><b>Requisiotion&nbsp;ID</b></td>
			<td align="Center"><b>Request&nbsp;Date</b></td>
			<td align="Center"><b>Assigned&nbsp;Person</b></td>
			<td align="Center"><b>Requested&nbsp;By</b></td>
			<td align="Center"><b>Requested&nbsp;Department</b></td>
			<td align="Center"><b>Supervised&nbsp;By</b></td>
			<td align="Center"><b>Procurement&nbsp;Aprroved&nbsp;date</b></td>
			<td align="Center"><b>Complete&nbsp;Status</b></td>
			<td align="Center"><b>Bill&nbsp;ID</b></td>	
		</tr> 
	<?php 
		echo $table;
	?>
	</table>
	

	<?php }else echo "No Record Found";
	if($options == '2'){?>		
	</body>
</html>
<?php }?>