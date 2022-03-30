<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
<div class="content" >
		<h1><?php echo $title; ?></h1>
		<?php echo $message; ?>
		<?php echo form_open_multipart($action); ?>
		<div><?php echo anchor('employee/transfer/','New Employee Transfer',array('class'=>'add')); ?></div>
		<div class="data">
		<table border="0" style="width: 400px;">
		
			<tr>
				<td >Employee&nbsp;Id&nbsp;<span style="color:red;">*</span></td>
				<td>			
				<select name="EmpId" ><option value="">-&nbsp;-Select-&nbsp;-</option><?php foreach ($employee->result() as $row) { ?>
				<option value="<?php echo $row->vEmpId; ?>"  ><?php echo $row->vEmpName; ?></option>
				<?php }?>
				</select><?php echo $this->validation->EmpId_error; ?></td>
			
			</tr>
	

			<tr>
				<td>From Date(dd-mm-yyyy)</td><td>
				<input type="text"  name="Sdate" onclick="displayDatePicker('Sdate');" class="text" value="<?php echo $this->validation->Sdate; ?>"/>
				<a href="javascript:void(0);" onclick="displayDatePicker('Sdate');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				<?php echo $this->validation->Sdate_error; ?></td>
			</tr>	
			
			<tr>
				<td>To Date(dd-mm-yyyy)</td><td>
				<input type="text"  name="Edate" onclick="displayDatePicker('Edate');" class="text" value="<?php echo $this->validation->Edate; ?>"/>
				<a href="javascript:void(0);" onclick="displayDatePicker('Edate');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				<?php echo $this->validation->Edate_error; ?></td>
			</tr>	
								
			<tr>
				<td>Place</td><td>
				<input type="text"  name="Stname"  class="text" value="<?php echo $this->validation->Stname; ?>"/>
				<?php echo $this->validation->Stname_error; ?></td>
			</tr>	
			
			<tr>
				<td>Purpose</td><td>
				<input type="text"  name="Reason"  class="text" value="<?php echo $this->validation->Reason; ?>"/>
				<?php echo $this->validation->Reason_error; ?></td>
			</tr>	
	
	

		
			
			<tr>				
				<td ><input type="submit" value="Save"/></td>
			</tr>
		</table>
		</div>
		
		<?php echo form_close(); ?>
</div>
</html>