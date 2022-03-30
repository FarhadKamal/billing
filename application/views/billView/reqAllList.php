<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<body>
	<div class="content" style="width:900; " valign="top" align="left" >
		<h1 valign="top"><?php  echo $title; ?></h1><?php echo $message; ?>
		<div class="paging">
		<?php echo form_open_multipart($action); ?>
				<table>
				<tr>
						<td ><font color='BLUE'>Search&nbsp;by&nbsp;</font>
						<select name="stype">
							<option value="d.vEmpName">Request By</option>
							<option value="a.vEmpName">Supervised By</option>
							<option value="c.vEmpName">Assigned To</option>
							<option value="mis_requisition_master.id">Requisition ID</option> 					
						</select>
						</td>
						<td>			
						<input type="text" name="svalue" size="40" />&nbsp;&nbsp;&nbsp;<input type="submit" value=">>"/></td>			
					</tr>
				</table>
				<?php echo form_close(); ?>
		</div>
		<div class="content" style="width:690;" valign="top" align="left">	
			<div class="data" ><?php if($totalrecord!=''){echo 'TOTAL: '.$totalrecord;}?><?php echo $table; ?><?php echo $pagination; ?></div>
		</div>
		
	</div>
</body>
</html>