<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
<div class="content" >
		<h1><?php echo $title; ?></h1>
		<?php echo $message; ?>
		<?php echo form_open_multipart($action); ?>
		<div class="data">
		<table border="0" style="width: 400px;">		
			<tr>
					<td>SAP&nbsp;ID&nbsp;<span style="color:red;">*</span></td><td>
			<input type="text" name="sap" value="<?php echo $this->validation->sap; ?>" />
			<?php echo $this->validation->sap_error; ?></td>		
			</tr>
			</tr>	

			
	
		
			<tr>				
				<td ><input type="submit" value="Save"/></td>
			</tr>
		</table>
		</div>
		
		<?php echo form_close(); ?>
</div>
</html>