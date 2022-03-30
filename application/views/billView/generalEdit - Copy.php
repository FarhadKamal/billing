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
				</select><?php echo $this->validation->loc_error; ?></td>				
			
				<td>Bill&nbsp; Type<span style="color:red;"></span></td><td>
				<select name="invoice_type" >
				<option value="General" <?php  if($this->validation->invoice_type=="General")echo "selected"; ?>>General Bill</option>
				<option value="Recurring" <?php  if($this->validation->invoice_type=="Recurring" )echo "selected"; ?>>Recurring Bill</option>
				
				</select></td>				
			</tr>
			<tr>
				<td align="left">Billing&nbsp;Date:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="bill_date" onclick="displayDatePicker('bill_date');" class="text" value="<?php echo $this->validation->bill_date; ?>"/>
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
					
				<td align="left">Total&nbsp;Amount&nbsp;</td>
				<td align="left" align="left">
					<input type="text" name="amount" readonly class="text" value="<?php echo $this->validation->amount; ?>"/>			
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
				<option <?php if ($this->validation->payment_type=="Adjustment")print("selected") ?> value="Adjustment">Adjustment</option>
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
			<?php echo $table2; ?>			
			</div>
		</div>
		
		<?php echo form_open_multipart($action4); ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">								
				<tr>
					<td align="left">
						Particular&nbsp;
						<textarea rows=3  name="particular"  cols=40></textarea>
					</td>
					<td align="left">
						Date&nbsp;<input type="text" name="particular_date" onclick="displayDatePicker('particular_date');" class="text"/>
					<a href="javascript:void(0);" onclick="displayDatePicker('particular_date');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
					</td>
					<td align="left">
						Amount&nbsp;<input type="text" name="particular_amount" /><input type="submit" value="add"/>
					</td>					
				</tr>	
			</table>
			<?php echo form_close(); ?>
		
	
		
		<div class="content" valign="top" align="left">	
			<div class="data" >
			<?php echo $table; ?>			
			</div>
		</div>
		
		<?php echo form_open_multipart($action2); ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">								
				<tr>
					<td>
					<select name="pid" >
				<?php
					$x=1;
					foreach($list_particular->result() as $row)
					{ 
					
						
						echo '<option value="'.$row->id.'">Particular --'.$x.'</option>';
						$x=$x+1;
					}
				?>
				</select>
					</td>
					<td align="left">
						Document&nbsp;Name&nbsp;<input type="text" size="50" name="doc_file" />
						<input type="hidden" value="" name="requisition" />

				&nbsp;&nbsp;
						Document&nbsp;Category:&nbsp;
						<select name="doc_category" >
							<option value="Invoice">Invoice</option>
							<option value="PR">PR</option>
							<option value="Quotation">Quotation</option>
							<option value="Approval">Approval</option>
							<option value="PO">PO</option>
							<option value="GR">GR</option>
							<option value="Others">Others</option>
						</select>
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
				
				
				<?php  if(($usercode==3 and $this->validation->amount<=10000) or ($usercode==2 and $this->validation->amount<=50000)  or $usercode==1 or $this->validation->invoice_type=='Recurring'){?>
				
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