<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>


<script type="text/javascript" src="<?php echo base_url(); ?>script/jquery.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>style/flexselect.css" type="text/css" media="screen" />
<script src="<?php echo base_url(); ?>script/liquidmetal.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>script/jquery.flexselect.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("select.flexselect").flexselect();
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
			<tr>
				<td>Location&nbsp;<span style="color:red;">*</span></td><td>
				<select name="loc" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<option value="1" <?php  if($this->validation->loc==1)echo "selected"; ?>>Chittagong Head Office</option>
				<option value="2" <?php  if($this->validation->loc==2)echo "selected"; ?>>Dhaka Office</option>
				</select><?php echo $this->validation->loc_error; ?></td>							
			<td align="left"><input type="submit" value="Save"/></td>	
			</tr>
		</table>
		<?php echo form_close(); ?>
	
		<?php if($openmodel=="yes"){ ?>
		
		<?php echo form_open_multipart($action2); ?>
			<table border="0"  style="font: 0.90em arial;width:690;" bgcolor="#8CC5FF">								
				<tr>
					<td align="left">
						Item&nbsp;Name&nbsp;<br/>
						<select name="item_name" class="flexselect"><option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<?php
							foreach($list_material->result() as $row)
							{ 
								
								echo '<option value="'.$row->mat_name.'">'.$row->mat_name.'</option>';
								
							}
						?>
						</select>
					</td>
					<td align="left">
						specs&nbsp;<br/><input type="text" name="req_sap_id" />
					</td>
					<td align="left">
						Quantity&nbsp;in&nbsp;hand&nbsp;<br/><input size=3 type="text" name="qty_hand" />
					</td>
					<td align="left">
						Quantity&nbsp;Required&nbsp;<br/><input size=3 type="number" name="qty_req" />
					</td>
					<td align="left">
						Unit&nbsp;<br/>
						<select name="item_unit">
							<option value="pcs">pcs</option>
							<option value="bag">bag</option>
							<option value="basta">basta</option>
							<option value="book">book</option>
							<option value="bottle">bottle</option>
							<option value="box">box</option>
							<option value="bundle">bundle</option>
							<option value="carton">carton</option>
							<option value="cft">cft</option>
							<option value="coil">coil</option>
							<option value="copy">copy</option>
							<option value="cylinder">cylinder</option>
							<option value="dista">dista</option>
							<option value="dozen">dozen</option>
							<option value="drum">drum</option>
							<option value="feet">feet</option>
							<option value="gallon">gallon</option>
							<option value="gram">gram</option>
							<option value="guz">guz</option>
							<option value="inch">inch</option>
							<option value="jar">jar</option>
							<option value="kg">kg</option>
							<option value="kva">kva</option>
							<option value="lbs">lbs</option>
							<option value="liter">liter</option>
							<option value="meter">meter</option>
							<option value="mg">mg</option>
							<option value="nos.">nos.</option>
							<option value="packet">packet</option>
							<option value="page">page</option>
							<option value="pail">pail</option>
							<option value="pair">pair</option>
							<option value="pot">pot</option>
							<option value="roll">roll</option>
							<option value="sack">sack</option>
							<option value="set">set</option>
							<option value="sft">sft</option>
							<option value="sheet">sheet</option>
							<option value="sqft">sqft</option>
							<option value="strip">strip</option>
							<option value="tin">tin</option>
							<option value="ton">ton</option>
							<option value="tub">tub</option>
							<option value="yard">yard</option>
						</select>
					</td>
					<td align="left">
						<table><tr><td>
						Delivery&nbsp;Date&nbsp;<br/><input type="text" name="delivery_date" onclick="displayDatePicker('delivery_date');" class="text"/></td><td>										
						<a href="javascript:void(0);" onclick="displayDatePicker('delivery_date');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
						</td></tr></table>
					</td>
					<td align="left">
						Contact&nbsp;Person&nbsp;Details&nbsp;<br/>
						<textarea rows=3  name="contact_details"  cols=20></textarea>
					</td>
					<td align="left">
						Reason&nbsp;For&nbsp;Request&nbsp;<br/>
						<textarea rows=3  name="reason_req"  cols=20></textarea>
					</td>
					
					<td align="left">
						Remark&nbsp;<br/>
						<textarea rows=3  name="remarks"  cols=20></textarea>
					</td>
					<td align="left">
						<input type="submit" value="add"/>
					</td>	
				</tr>	
			</table>
		<?php echo form_close(); ?>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<div class="content" valign="top" align="left">	
			<div class="data" >
			<?php if($itemcount>0) echo $table; ?>			
			</div>
		</div>
		
		
		<?php
		 echo form_open_multipart($action3); ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">
				<?php if($this->session->userdata('username')==$Proc1 or $this->session->userdata('username')==$Proc2){  ?>
				<tr><td>Assign&nbsp;To:&nbsp;<select name="assigned_person" >
				<?php 
					foreach($assigned_personList->result() as $row)
					{ 				
						if($assigned_person==$row->vEmpId)
						echo '<option value="'.$row->vEmpId.'" selected>'.$row->vEmpName.'</option>';	
						else echo '<option value="'.$row->vEmpId.'">'.$row->vEmpName.'</option>';						
					}
				?>
				</select>
				</td></tr>
				<tr>
				<td>Comment&nbsp
					<textarea cols="40" rows="5" name="comment"></textarea>
				</td>		
				</tr>
				<?php } else if($entry==0){ ?>
				<tr><td>Forward&nbsp;To:&nbsp;<select name="forward_person" >
	
						
					
						<option value="<?php echo $Proc1;?>" <?php  if($this->validation->loc==1)echo "selected"; ?>>Procurement Department Chittagong</option>
						<option value="<?php echo $Proc2;?>" <?php  if($this->validation->loc==2)echo "selected"; ?>>Procurement Department Dhaka</option>
						<option value="<?php echo $IT;?>">Head of IS</option>
						<option value="<?php echo $Director;?>">Director</option>
						
						
						
				</select>
				</td></tr>
				<tr>
				<td>Comment&nbsp
					<textarea cols="40" rows="5" name="comment"></textarea>
				</td>		
				</tr>
				<?php } ?>
				<tr>
					<td align="left">
						<input type="submit" value="Final Submit"/>
					</td>		
				</tr>	
			</table>
		<?php echo form_close(); } ?>

		</div>

</html>