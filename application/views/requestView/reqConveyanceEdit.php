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
				<input name="EmpId" readonly type="text" value="<?php echo $this->validation->EmpId; ?>" />
				<input name="SL" type="hidden" value="<?php echo $this->validation->SL; ?>" />
				</td>

			
			</tr>
			<tr>
				<td width="30">Company&nbsp;Name</td>
				<td><select name="company" id="<?php echo $id;?>">
				<option  value=""><?php echo '---SELECT---';?></option>
				<?php foreach ($company_list->result() as $row) { ?>
				<option  value="<?php echo $row->iId; ?>" <?php if($this->validation->company==$row->iId)print("Selected");  ?>><?php echo $row->vCompany; ?></option>
				<?php }?></select><?php echo $this->validation->company_error; ?></td>
			</tr>

			<tr>
				<td>Journey Date<span style="color:red;">*</span></td><td>
				<input type="text" readonly name="journey_date" onclick="displayDatePicker('journey_date');" class="text" value="<?php echo $this->validation->journey_date; ?>"/>
				<a href="javascript:void(0);" onclick="displayDatePicker('journey_date');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				<?php echo $this->validation->journey_date_error; ?></td>
			</tr>
			
			
			<tr>
				<td >Purpose&nbsp;</td>
				<td>	<textarea name="purpose" cols="20" rows="3"><?php echo $this->validation->purpose; ?></textarea><?php echo $this->validation->purpose_error; ?></td>		
			</tr>
			
			
			<tr>
				<td >From&nbsp;</td>
				<td>	<input type="text" name="vfrom" size="40" value="<?php echo $this->validation->vfrom; ?>" /><?php echo $this->validation->vfrom_error; ?></td>		
			</tr>
			
			<tr>
				<td >To&nbsp;</td>
				<td>	<input type="text" name="vto" size="40" value="<?php echo $this->validation->vto; ?>" /><?php echo $this->validation->vto_error; ?></td>		
			</tr>

			<tr>
				<td>Mode of Transport</td>
				<td>	
					<select name="trans_mode">
						<option value="">--Select--</option>
						<option value="Rickshaw"  <?php if($this->validation->trans_mode=="Rickshaw")print("Selected");  ?> >Rickshaw</option>
						<option value="CNG" <?php if($this->validation->trans_mode=="CNG")print("Selected");  ?>>CNG</option>
						<option value="Taxi" <?php if($this->validation->trans_mode=="Taxi")print("Selected");  ?>>Taxi</option>
						<option value="Private Car" <?php if($this->validation->trans_mode=="Private Car")print("Selected");  ?>>Private Car</option>	
						<option value="Bus" <?php if($this->validation->trans_mode=="Bus")print("Selected");  ?>>Bus</option>
						<option value="Train" <?php if($this->validation->trans_mode=="Train")print("Selected");  ?>>Train</option>
						<option value="Plane" <?php if($this->validation->trans_mode=="Plane")print("Selected");  ?>>Plane</option>
					</select>
					<?php echo $this->validation->trans_mode_error; ?>			
				</td>		
			</tr>
			</tr>
			<tr>
				<td >updown&nbsp;</td>
				<td>	<input type="checkbox" name="updown" size="40" value="yes"  <?php if($this->validation->updown=="yes")print("checked");  ?> /></td>		
			</tr>	

			<tr>
				<td >Amount&nbsp;</td>
				<td>	<input type="text" name="amount" size="40" value="<?php echo $this->validation->amount; ?>" /><?php echo $this->validation->amount_error; ?></td>		
			</tr>
				
			<tr>
				<td>Location&nbsp;<span style="color:red;">*</span></td><td>
				<select name="loc" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<option value="1" <?php  if($this->validation->loc==1)echo "selected"; ?>>Chittagong Head Office</option>
				<option value="2" <?php  if($this->validation->loc==2)echo "selected"; ?>>Dhaka Office</option>
				<option value="3" <?php  if($this->validation->loc==3)echo "selected"; ?>>Mohakhali Office</option>
				</select><?php echo $this->validation->loc_error; ?></td>				
			</tr>	
		
			<tr>				
				<td ><input type="submit" value="Save"/></td>
			</tr>
		</table>
		</div>
		
		<?php echo form_close(); ?>
</div>
</html>