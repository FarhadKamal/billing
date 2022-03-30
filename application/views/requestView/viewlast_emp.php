

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<head>
<title>Employee ID</title>
</head>
<body> 

	<div class="content"> 
		<div >
			<table border="1"  CELLPADDING="4" CELLSPACING="0" width="20%" align="center" valign="top">
				<?php  ?>
				<tr>			
					<td align="Center" colspan="2"><h1><?php echo 'Available Employee ID';?></h1></td>
				</tr>
				<?php 
					foreach ($results->result() as $row):  endforeach; ?>
				<tr>			
					<td align="left" ><b><font color="green">Employee&nbsp;ID:&nbsp;</font></td><td><font color="red"><?php echo $row->tot ?></font></b></td>
				</tr>				
			</table>
		</div>
	</div>
	
	</body>
</html>
