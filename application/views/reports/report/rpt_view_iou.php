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
	<tr><td align="Center" ><b>IOU</b></td></tr> 
	</table><br/>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>Company:</b></td>
			<td align="Center"><b><?php echo $mydata2->vCompany ?></b></td>
			<td align="Center"><b>Request&nbsp;Date&nbsp;</b></td>
			<td align="Center"><b><?php echo $mydata2->req_date ?></b></td>

		</tr>
		<tr>
			<td align="Center" width="1%"><b>Request&nbsp;by:</b></td>
			<td align="left" colspan=3>&nbsp;<b><?php echo " (".$mydata2->req_by.") ".$mydata2->createdName; ?></b></td>
		</tr>
		<tr>
			<td align="Center" width="1%"><b>Purpose:</b></td>
			<td align="left" colspan=3>&nbsp;<b><?php echo $mydata2->purpose ?></b></td>
		</tr>
	</table>	
	
	<br/>
	
	
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>Ref&nbsp;ID</b></td>
			<td align="Center"><b>Particular&nbsp;</b></td>
			<td align="Center"><b>Amount&nbsp;</b></td>
		</tr> 
	<?php 
	$sl=0;

	foreach ($results2->result() as $mydata): 
	$sl=$sl+1;
	?>	

		<tr>
		<td align="center"><b><?php echo $mydata->id  ?></b></td>
		<td align="center"><b><?php echo $mydata->purpose ?></b></td>
		<td align="center"><b><?php echo $mydata->amount ?></b></td>
		</tr>	
	<?php  endforeach; ?>

	</table>
	<br/>
	
	<table border="0"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td>
			<?php if($mydata2->dep_accept_by!=$mydata2->ceo_accept_by){ ?>
				<table>
					<tr>
						<td><b>Recommended By</b></td>
					</tr>
					<tr>
						<td>
								<b><?php echo $mydata2->deptName ?></b><br/>
								<b><?php echo $mydata2->deptDesignation ?></b><br/>
								<?php if($mydata2->dep_accept_date!=""){ ?>
 								<b><?php echo "Approved&nbsp;Date: ".$mydata2->dep_accept_date ?></b><br/>
								<b><?php echo "Remakrs: ".$mydata2->dep_remarks ?></b><br/>
							   <?php }else if($mydata2->dgm_accept_date=="") echo "Not Approve Yet"; ?>
						</td>
					</tr>
				</table>
			</td>
			<?php } ?>
			<?php if($mydata2->dep_accept_by!=$mydata2->dgm_accept_by){ ?>
			<td align="right">
				<table>
					<tr>
						<td><b><?php echo $mydata2->AGMDesignation ?></b></td>
					</tr>
					<tr>
						<td>
								<b><?php echo $mydata2->AGMName ?></b><br/>
								<?php if($mydata2->dep_accept_date!="" or $mydata2->dgm_accept_date!="" ){ ?>
								<b><?php echo "Approved&nbsp;Date: ".$mydata2->dgm_accept_date ?></b><br/>
								<b><?php echo "Remakrs: ".$mydata2->agm_remarks ?></b><br/>
								<?php } ?>
						</td>
					</tr>
				</table>
			</td>
			<?php } ?>
		</tr>
		
		<tr>
			

			<td >
			<?php if($mydata2->ceo_accept_date!="" or $mydata2->ceo_accept_date!=null ){ ?>
				<table>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><b><?php echo $mydata2->ceoDesignation ?></b></td>
					</tr>
					<tr>
						<td>
								<b><?php echo $mydata2->ceoName ?></b><br/>
								
								<b><?php echo "Approved&nbsp;Date: ".$mydata2->ceo_accept_date ?></b><br/>
								<b><?php echo "Remakrs: ".$mydata2->ceo_remarks ?></b><br/>
								
						</td>
					</tr>
				</table>
			<?php } ?>	
			</td>
			
			<td align="right">
				<?php if($mydata2->dir_accept_date!="" or $mydata2->dir_accept_date!=null ){ ?>
				<table>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><b><?php echo $mydata2->dirDesignation ?></b><br/></td>
					</tr>
					<tr>
						<td>
								<b><?php echo $mydata2->dirName ?></b><br/>
							
								<b><?php echo "Approved&nbsp;Date: ".$mydata2->dir_accept_date ?></b><br/>
								<b><?php echo "Remakrs: ".$mydata2->dir_remarks ?></b><br/>
								
						</td>
					</tr>
				</table>
				<?php } ?>
			</td>
			
		</tr>
	</table>	
	
	
<?php	 }else echo "No Record Found";
	if($options == '2'){?>		
	</body>
</html>
<?php }?>