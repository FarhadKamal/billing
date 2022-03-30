<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>script/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){

if($('#chequeTarget').val()=="Cheque")
	{
	
		$('#trcheque').show();
	} else $('#trcheque').hide();
	
	
$('#chequeTarget').change(function() {
	if($('#chequeTarget').val()=="Cheque")
	{
	
		$('#trcheque').show();
	} else $('#trcheque').hide();
});



});
</script>
<style type="text/css">


.container {
	list-style: none;
	padding: 5px 0 4px 0;
	margin: 0 0 0 10px;
	font: 0.90em arial;
	border: 1px solid #ccc;
	border-top: none;
}

</style>
		<div class="content" ><input type="hidden" id="loc" value="<?php echo base_url(); ?>index.php/work/work/" /> 
		<h1><?php echo $title; ?></h1>
		<?php echo $message; ?> </div>		
		<div class="content"><div class="data">
		<?php echo form_open_multipart($action); ?>
		<table border="0" style="width: 1000px;	font: 0.90em arial;">			
			<tr>
				<td>Location&nbsp;<span style="color:red;">*</span></td><td>
				<select name="loc" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<option value="1" <?php  if($this->validation->loc==1)echo "selected"; ?>>Chittagong Head Office</option>
				<option value="2" <?php  if($this->validation->loc==2)echo "selected"; ?>>Dhaka Office</option>
				<option value="3" <?php  if($this->validation->loc==3)echo "selected"; ?>>Mohakhali Office</option>
				</select><?php echo $this->validation->loc_error; ?></td>				
				
			</tr>
			<tr>
				<td>Company&nbsp;Name&nbsp;<span style="color:red;">*</span></td><td>
				<select name="Company" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<?php
					foreach($company->result() as $row)
					{ 
						if ($row->iId == $this->validation->Company)
						{
							echo '<option value="'.$row->iId.'" selected="selected">'.$row->vCompany.'</option>';
						}
						else
						{
							echo '<option value="'.$row->iId.'">'.$row->vCompany.'</option>';
						}
					}
				?>
				</select><?php echo $this->validation->Company_error; ?></td>				
			</tr>			
			

			
			<tr>							
				<td>Payment&nbsp;Method&nbsp;<span style="color:red;">*</span>&nbsp;</td>
				<td>
				<select name="payment_type" id="chequeTarget">
				<option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<option <?php if ($this->validation->payment_type=="Cash")print("selected") ?> value="Cash">Cash</option>
				<option <?php if ($this->validation->payment_type=="Cheque")print("selected") ?> value="Cheque">Cheque</option>
				</select>		
				<?php echo $this->validation->payment_type_error; ?>
				</td>	
			</tr>
			<tr id="trcheque">
				
				<td align="left">Cheque&nbsp;Name</td>
				<td align="left" align="left">
					<input type="text" name="suggested_cheque" size="40" class="text" value="<?php echo $this->validation->suggested_cheque; ?>"/>
					<?php echo $this->validation->suggested_cheque_error; ?>
				</td>	
			</tr>

			
			<td align="left"><input type="submit" value="Save"/></td>	
			</tr>
		</table>
		<?php echo form_close(); ?>						
		</div>

</html>