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


$('#requisition_id').change(function(){
						
		
		var req=$("#requisition_id").val();
		
		
		var pathloc=$("#loc").val()+"get_item_by_req";
	
		
		$.post(pathloc,
		  { 'data':req },

		  // when the Web server responds to the request
		  function(result) {
		  
				$('#requisition_item').empty();
				
			// if the result is TRUE write a message return by the request
			   if (result) {	

				$('#requisition_item').append(result);
			 // $("#horse_power").val (result);			  
			}
			}
		);
		
		
		
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

</style><input type="hidden" id="loc" value="<?php echo base_url(); ?>index.php/bill/vendorforreq/" /> 
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
				</select><?php echo $this->validation->loc_error; ?></td>				
			</tr>
			<tr>
				<td align="left">Billing&nbsp;Date:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="bill_date" readonly onclick="displayDatePicker('bill_date');" class="text" value="<?php echo $this->validation->bill_date; ?>"/>
					<a href="javascript:void(0);" onclick="displayDatePicker('bill_date');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
					<?php echo $this->validation->bill_date_error; ?>
				</td>
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
				<td align="left">Description&nbsp;<span style="color:red;">*</span></td>
				<td align="left" >
					<textarea rows=3  name="bill_description"  cols=40><?php echo $this->validation->bill_description; ?></textarea>
					<?php echo $this->validation->bill_description_error; ?>
				</td>
				<td>Vendor&nbsp;Name&nbsp;<span style="color:red;">*</span></td><td>
				<select name="Vendor" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<?php
					foreach($vendor->result() as $row)
					{ 
						if ($row->vendor_code == $this->validation->Vendor)
						{
							echo '<option value="'.$row->vendor_code.'" selected="selected">'.$row->vendor_name.' ## '.$row->vendor_code.'</option>';
						}
						else
						{
							echo '<option value="'.$row->vendor_code.'">'.$row->vendor_name.' ## '.$row->vendor_code.'</option>';
						}
					}
				?>
				</select><?php echo $this->validation->Vendor_error; ?></td>	
			</tr>
			
			
			<tr>
				<td align="left">PO&nbsp;No:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="po_no" class="text" value="<?php echo $this->validation->po_no; ?>"/>
					<?php echo $this->validation->po_no_error; ?>
				</td>
				<td align="left">PO&nbsp;Date:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="po_date" readonly onclick="displayDatePicker('po_date');" class="text" value="<?php echo $this->validation->po_date; ?>"/>
					<a href="javascript:void(0);" onclick="displayDatePicker('po_date');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
					<?php echo $this->validation->po_date_error; ?>
				</td>				
			</tr>
			
			<tr>
				<td align="left">GR&nbsp;No:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="gr_no" class="text" value="<?php echo $this->validation->gr_no; ?>"/>
					<?php echo $this->validation->gr_no_error; ?>
				</td>
				<td align="left">GR&nbsp;Date:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="gr_date" readonly onclick="displayDatePicker('gr_date');" class="text" value="<?php echo $this->validation->gr_date; ?>"/>
					<a href="javascript:void(0);" onclick="displayDatePicker('gr_date');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
					<?php echo $this->validation->gr_date_error; ?>
				</td>				
			</tr>	

			<tr>
				<td align="left">IV&nbsp;No:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="iv_no" class="text" value="<?php echo $this->validation->iv_no; ?>"/>
					<?php echo $this->validation->iv_no_error; ?>
				</td>
				<td align="left">IV&nbsp;Date:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="iv_date" readonly onclick="displayDatePicker('iv_date');" class="text" value="<?php echo $this->validation->iv_date; ?>"/>
					<a href="javascript:void(0);" onclick="displayDatePicker('iv_date');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
					<?php echo $this->validation->iv_date_error; ?>
				</td>				
			</tr>

			<tr>			
				<td align="left">TDS&nbsp;</td>
				<td align="left" align="left">
					<input type="text" name="tds" class="text" value="<?php echo $this->validation->tds; ?>"/>
					<?php echo $this->validation->tds_error; ?>
				</td>
				<td align="left">Asset&nbsp;No:&nbsp;</td>
				<td align="left" align="left">
					<input type="text" name="asset_no" class="text" value="<?php echo $this->validation->asset_no; ?>"/>
					<?php echo $this->validation->asset_no_error; ?>
				</td>	
			</tr>

			<tr>
				
				<td align="left">VDS&nbsp;</td>
				<td align="left" align="left">
					<input type="text" name="vds" class="text" value="<?php echo $this->validation->vds; ?>"/>
					<?php echo $this->validation->vds_error; ?>
				</td>
				<td align="left">Advance&nbsp;</td>
				<td align="left" align="left">
					<input type="text" name="advance" class="text" value="<?php echo $this->validation->advance; ?>"/>
					<?php echo $this->validation->advance_error; ?>
				</td>	
			</tr>
			
			<tr>
				
				<td align="left">Amount&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="amount" class="text" value="<?php echo $this->validation->amount; ?>"/>
					<?php echo $this->validation->amount_error; ?>
				</td>
				<td align="left">Retype&nbsp;Amount&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="retypeamount" class="text" value="<?php echo $this->validation->retypeamount; ?>"/>
					<?php echo $this->validation->retypeamount_error; ?>
				</td>		
			</tr>

			
			<tr>							
				<td>Payment&nbsp;Method&nbsp;<span style="color:red;">*</span>&nbsp;</td>
				<td>
				<select name="payment_type" id="chequeTarget">
				<option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<option <?php if ($this->validation->payment_type=="Cash")print("selected") ?> value="Cash">Cash</option>
				<option <?php if ($this->validation->payment_type=="Cheque")print("selected") ?> value="Cheque">Cheque</option>
				<option <?php if ($this->validation->payment_type=="DD")print("selected") ?> value="DD">DD</option>
				<option <?php if ($this->validation->payment_type=="TT")print("selected") ?> value="TT">TT</option>
				<option <?php if ($this->validation->payment_type=="Pay-Order")print("selected") ?> value="Pay-Order">Pay Order</option>
				</select>		
				<?php echo $this->validation->payment_type_error; ?>
				</td>
				
				<?php 
		
		if($entry==1){ ?>
				
				<td >Reporting&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;</td>
				<td><select name="ReportingOfficer" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<?php
					foreach($reportingOfficerList->result() as $row)
					{ 
						if ($row->id == $this->validation->ReportingOfficer)
						{
							echo '<option value="'.$row->id.'" selected="selected">'.$row->Name.'--'.$row->Designation.'</option>';
						}
						else
						{
							echo '<option value="'.$row->id.'">'.$row->Name.'--'.$row->Designation.'</option>';
						}
					}
				?>
				</select><?php echo $this->validation->ReportingOfficer_error; ?>
				</td>   <?php  } ?>
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
		<?php if($openmodel=="yes"){
		?>
		


		
	
		
		<div class="content" valign="top" align="left">	
			<div class="data" >
			<?php echo $table; ?>			
			</div>
		</div>
		
		<?php echo form_open_multipart($action2); ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">								
				<tr>
					<td align="left">
						Document&nbsp;Name&nbsp;<input type="text" size="50" name="doc_file" />
						<input type="hidden" value="" name="requisition" />
						<input type="submit" value="add"/>
					</td>		
				</tr>	
			</table>
			<?php echo form_close(); ?>
			
			
			
			
			
			<?php  echo form_open_multipart($action5); //jitu ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">								
				<tr>
					<td>
					<select name="requisition_id" id="requisition_id" >
				<?php
					
					echo '<option value="0">Requisition ## Select</option>';	
					foreach($list_requisition->result() as $row)
					{ 
	
						echo '<option value="'.$row->id.'">Requisition ## '.$row->id.'</option>';					
					}
				?>
				</select>
				<div id="requisition_item">

				</div>
				
				
					</td>
					<td align="left">
						<input type="submit" value="add"/>
					</td>		
				</tr>	
			</table>
			<?php echo form_close(); ?>

	
		
		
		<?php 
		
		if($entry==1){
		echo form_open_multipart($action3); ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">								
				<tr>
					<td align="left">
						<input type="submit" value="Final Submit"/>
					</td>		
				</tr>	
			</table>
			<?php echo form_close(); ?>

		<?php		
			}if($recommend==1){ 
			
			echo form_open_multipart($action3); ?>
	
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">								
				
				<?php if($usercode!="audit"){?>
				<tr>
				
				<td >Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;</td>
				
				
				<?php  if(($usercode==3 and $this->validation->amount<=10000) or ($usercode==2 and $this->validation->amount<=50000)  or $usercode==1){?>
				
				<td>	<select name="ReportingOfficer" ><option value="audit">audit</option>
				<option value="3">Director</option></select></td>
				
				<?php } else{ ?>
				<td><select name="ReportingOfficer" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<?php
					foreach($reportingOfficerList2->result() as $row)
					{ 					
						echo '<option value="'.$row->id.'">'.$row->Name.'--'.$row->Designation.'</option>';						
					}
				?>
				</select>
				</td>
				<?php } ?>
				
				
				
				
				</tr>
				<?php }?>
				
				
				
				<?php  if($usercode=="audit"){?>
				<tr>
				
				<td >Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;</td>
				
				<td><select name="ReportingOfficer" >
					<option value="account">Account</option>
					<option value="director">Director</option>
					<option value="dep">Department</option>
				</select>
				</td>
			
	
				</tr>
				<?php }?>
				
				
				
				
				
				
				<tr>
				<td>Recommendation&nbsp;</td>
					<td>
						<textarea cols="40" rows="5" name="comment"  ></textarea>
					</td>		
				</tr>
				<tr>
					<td align="left">
						<input type="submit" value="Final Submit"/>
					</td>		
				</tr>	
			</table>
			<?php echo form_close(); ?>
			
				
			
			
			<?php }
			
			
			
			
		}
		?>
		
		
		
		
		</div>

</html>