

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<head>
<title>Previos Leave Records</title>
</head>
<body> 

	<div class="content"> 
	<form method="post" action="<?php echo $action; ?>">
	<input type="hidden" name="lot_no" value="<?php echo $lot_no; ?>" />
	<input type="hidden" name="companyid" value="<?php echo $companyid; ?>" />
	<input type="hidden" name="employeeid" value="<?php echo $employeeid; ?>" />
		<div >
			<table border="1"   width="100%" align="center">

	
				<?php if($results->result()) {?>
				
				
				<tr align="Center" >
					<td align="Center" colspan=6>Lot No: <?php echo $lot_no; ?></td>			
				</tr>
				<?php foreach ($results->result() as $mycompany): endforeach; ?> 
				<tr align="Center" >
					<td align="Center" colspan=6>Company: <?php echo $mycompany->vCompany; ?></td>			
				</tr>
				
				<tr align="Center" >
					<td align="Center" colspan=6>Employee Id: <?php echo $employeeid; ?></td>			
				</tr>
				
				
				<tr align="Center">
					<td align="Center"><b>Journey Date</b></td>
					<td align="Center"><b>From</b></td>
					<td align="Center"><b>To</b></td>
					<td align="Center"><b>Trans Mode</b></td>
					<td align="Center"><b>Purpose</b></td>
					<td align="Center"><b>Amount</b></td>
				</tr>

				<?php $total=0;
					foreach ($results->result() as $mydata): 
					$total=$mydata->amount+$total;
				?> 
				<tr>
					
					<td align="Center"><?php echo $mydata->journey_date ?></td>
					<td align="Center"><?php echo $mydata->vfrom ?></td>
					<td align="Center"><?php echo $mydata->vto ?></td>
					<td align="Center"><?php echo $mydata->trans_mode ?></td>
					<td align="Center"><?php echo $mydata->purpose ?></td>
					<td align="right"><?php echo $mydata->amount ?></td>
				</tr>
							
				<?php endforeach;
				
				?>
				<tr>
					
					<td align="Center" colspan=5>Total</td>
					<td align="right"><?php echo $total.".00" ?></td>
					
				</tr>
				<tr>				
					<td align="Center" colspan="8"><input type="submit" value="Make Payment"/></td>
				</tr>
				<?php
				}else{
				?>
				<tr>			
					<td align="Center" colspan="6"><?php echo 'No Records Found';?></td>
				</tr>
				
				
				<?php 
				}
				?>
			</table>
		</div>
	</form>
	</div>

	</body>
</html>
