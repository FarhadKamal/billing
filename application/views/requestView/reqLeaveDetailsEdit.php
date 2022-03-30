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
				<td >Employee&nbsp;Id&nbsp;<span style="color:red;">*</span></td>
				<td>			 
				<input name="EmpId" readonly type="text" value="<?php echo $this->validation->EmpId; ?>" /></td>

			
			</tr>

			<tr>
				<td>Leave Type</td>
				<td>
					<select name="LeaveType" >
						<option value="Earned" <?php if($this->validation->LeaveType=="Earned")echo "Selected"; ?>>Earned</option> 
						<option value="Sick" <?php if($this->validation->LeaveType=="Sick")echo "Selected"; ?>>Sick</option>
						<option value="Casual" <?php if($this->validation->LeaveType=="Casual")echo "Selected"; ?>>Casual</option>
						<option value="Consolidated" <?php if($this->validation->LeaveType=="Consolidated")echo "Selected"; ?>>Consolidated</option>
						<option value="LWP" <?php if($this->validation->LeaveType=="LWP")echo "Selected"; ?>>LWP</option>
						<option value="Compensatory" <?php if($this->validation->LeaveType=="Compensatory")echo "Selected"; ?>>Compensatory</option>
					</select>				
				</td>
			</tr>				
			<tr>
				<td>From Date<span style="color:red;">*</span></td><td>
				<input type="text" readonly name="LDate" onclick="displayDatePicker('LDate');" class="text" value="<?php echo $this->validation->LDate; ?>"/>
				<a href="javascript:void(0);" onclick="displayDatePicker('LDate');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				<?php echo $this->validation->LDate_error; ?></td>
			</tr>
			<tr>
				<td>To Date<span style="color:red;">*</span></td><td>
				<input type="text" readonly name="EDate" onclick="displayDatePicker('EDate');" class="text" value="<?php echo $this->validation->EDate; ?>"/>
				<a href="javascript:void(0);" onclick="displayDatePicker('EDate');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				<?php echo $this->validation->EDate_error; ?></td>
			</tr>
			
			<tr>
				<td >Reason&nbsp;</td>
				<td>	<textarea name="Reason" cols="20" rows="3"><?php echo $this->validation->Reason; ?></textarea></td>

			
			</tr>
	
	
	

		
			
			<tr>				
				<td ><input type="submit" value="Save"/></td>
			</tr>
		</table>
		</div>
		
		<?php echo form_close(); ?>
</div>
</html>