

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<head>
<title>Canteen Mobile</title>
</head>
<body> 

	<div class="content"> 
		<div >
			<table border="1"  CELLPADDING="4" CELLSPACING="0" width="20%" align="center" valign="top">
				<?php  ?>
				<tr>			
					<td align="Center" colspan="2"><h1><?php echo 'Canteen Mobile Expense';?></h1></td>
				</tr>
				<?php  if($results->result()) {
					foreach ($results->result() as $row):   
				
				if($row->dedtype==3){ ?>
				<tr>			
					<td align="left" ><b><font color="green">Stamp:&nbsp;</font></td><td><font color="red"><?php echo $row->dAmount ?></font></b></td>
				</tr>
				<?php }

				if($row->dedtype==0){
				?>
				<tr>			
					<td align="left" ><b><font color="green">Mobile:&nbsp;</font></td><td><font color="red"><?php echo $row->dAmount ?></font></b></td>
				</tr>
			
				<?php }

				if($row->dedtype==1){
				?>
				<tr>			
					<td align="left" ><b><font color="green">Canteen:&nbsp;</font></td><td><font color="red"><?php echo $row->dAmount ?></font></b></td>
				</tr>
				
				<?php }

				if($row->dedtype==2){
				?>
				<tr>			
					<td align="left" ><b><font color="green">Insurance:&nbsp;</font></td><td><font color="red"><?php echo $row->dAmount ?></font></b></td>
				</tr>
			
				<?php }


				endforeach; } 
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
