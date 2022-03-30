

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<head>
<title>Previos Leave Records</title>
</head>
<body> 

	<div class="content"> 
		<div >
			<table border="1"  CELLPADDING="4" CELLSPACING="0" width="20%" align="center" valign="top">
				<?php  ?>
				<tr>			
					<td align="Center" colspan="2"><h1><?php echo 'Leave Records';?></h1></td>
				</tr>
				<?php if($results->result()) {
					foreach ($results->result() as $row):  endforeach; ?>
				<tr>			
					<td align="left" ><b><font color="green">Earned Remain:&nbsp;</font></td><td><font color="red"><?php echo $row->EarnedRemain ?></font></b></td>
				</tr>
				<tr>	
					<td align="left" ><b><font color="green">Casual Remain:&nbsp;</font></td><td><font color="red"><?php echo $row->CasualRemain ?></font></b></td>
				</tr>
				<tr>	
					<td align="left" ><b><font color="green">Sick Remain:&nbsp;</font></td><td><font color="red"><?php echo $row->SickRemain ?></font></b></td>
				</tr>
				<tr>	
					<td align="left" ><b><font color="green">Consolidated Remain:&nbsp;</font></td><td><font color="red"><?php echo $row->consolidatedRemain ?></font></b></td>
				</tr>
				<tr>			
					<td align="Center" colspan="2"><h1><?php echo '&nbsp;';?></h1></td>
				</tr>
				
				<?php } if($results2->result()) {
					foreach ($results2->result() as $row):  endforeach; ?>
				
				<tr>			
					<td align="left" ><b><font color="green">Earned Availed:&nbsp;</font></td><td><font color="red"><?php echo $row->Earned ?></font></b></td>
				</tr>
				<tr>	
					<td align="left" ><b><font color="green">Casual Availed:&nbsp;</font></td><td><font color="red"><?php echo $row->Casual ?></font></b></td>
				</tr>
				<tr>	
					<td align="left" ><b><font color="green">Sick Availed:&nbsp;</font></td><td><font color="red"><?php echo $row->Sick ?></font></b></td>
				</tr>
				<tr>	
					<td align="left" ><b><font color="green">Consolidated Availed:&nbsp;</font></td><td><font color="red"><?php echo $row->consolidated ?></font></b></td>
				</tr>
				<tr>	
					<td align="left" ><b><font color="green">Leave Adjusted:&nbsp;</font></td><td><font color="red"><?php echo $row->LeaveAdjust ?></font></b></td>
				</tr>
							
				<?php } 
				else{
				?>
				<tr>			
					<td align="Center" colspan="7"><h1><?php echo 'No Records Found';?></h1></td>
				</tr>
				
				
				<?php 
				}
				?>
			</table>
		</div>
	</div>
	
	</body>
</html>
