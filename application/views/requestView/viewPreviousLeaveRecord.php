

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<head>
<title>Previos Leave Records</title>
</head>
<body> 

	<div class="content"> 
		<div class="data">
			<table border="1"  CELLPADDING="4" CELLSPACING="0" width="50%" align="center" valign="top">
				<?php  ?>
				<tr>			
					<td align="Center" colspan="7"><h1><?php echo 'Previos Leave Records ';?></h1></td>
				</tr>
				<?php if($results->result()) {?>
				<?php
					foreach ($results->result() as $row):  endforeach; ?>
				<tr>			
					<td align="Center" ><b><font color="green">Earned Remain:&nbsp;</font><font color="red"><?php echo $row->EarnedRemain ?></font></b></td>
					<td align="Center" ><b><font color="green">Casual Remain:&nbsp;</font><font color="red"><?php echo $row->CasualRemain ?></font></b></td>
					<td align="Center" ><b><font color="green">Sick Remain:&nbsp;</font><font color="red"><?php echo $row->SickRemain ?></font></b></td>
					<td align="Center" ><b><font color="green">Consolidated Remain:&nbsp;</font><font color="red"><?php echo $row->consolidatedRemain ?></font></b></td>
				</tr>
				
				<tr align="Center">
					<td align="Center"><b>Employee Id</b></td>
					<td align="Center"><b>Employee Name</b></td>
					<td align="Center"><b>Leave Type</b></td>
					<td align="Center"><b>Reason</b></td>
					<td align="Center"><b>From</b></td>
					<td align="Center"><b>To</b></td>
					<td align="Center"><b>Leave Availed</b></td>
				</tr>
				
				
				
				<?php
					foreach ($results->result() as $mydata): 
				?> 
				<tr>
					
					<td align="Center"><?php echo $mydata->EmpId ?></td>
					<td align="Center"><?php echo $mydata->FirstName.' '.$mydata->MiddleName.' '.$mydata->LastName ?></td>
					<td align="Center"><?php echo $mydata->LeaveType ?></td>
					<td align="Center"><?php echo $mydata->Reason ?></td>
					<td align="Center"><?php echo $mydata->LDate ?></td>
					<td align="Center"><?php echo $mydata->EDate ?></td>
					<td align="Center"><?php echo $mydata->Availed ?></td>
				</tr>
							
				<?php endforeach;
				}else{
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
