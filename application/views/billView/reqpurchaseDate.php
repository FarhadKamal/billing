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
				<td align="left">Purchase&nbsp;Date:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="purchase_date" readonly onclick="displayDatePicker('purchase_date');" class="text" value="<?php echo $nowdate; ?>"/>
					<a href="javascript:void(0);" onclick="displayDatePicker('purchase_date');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
			
				</td>
			</tr>
			
			<tr>
				<td>Comment&nbsp;<span style="color:red;"></span></td>
				<td>
					<textarea cols="40" rows="5" name="comment"  ><?php echo $comment; ?></textarea>
				</td>		
			</tr>

			<tr>				
				<td ><input type="submit" value="Save"/></td>
			</tr>
		</table>
		</div>
		
		<?php echo form_close(); ?>
</div>
</html>