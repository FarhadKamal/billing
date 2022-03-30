<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>

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
				<td align="left">Amount&nbsp;<span style="color:red;">*</span></td>
				<td><input type="text" name="amount" class="text" readonly value="<?php echo $this->validation->amount; ?>"/>
					</td>
				<td align="left">Purpose&nbsp;<span style="color:red;">*</span></td>
				<td align="left" >
					<textarea rows=3  name="purpose"  cols=40><?php echo $this->validation->purpose; ?></textarea>
					<?php echo $this->validation->purpose_error; ?>
				</td>
			</tr>	
			<tr>
			<td align="left"><input type="submit" value="Save"/></td>	
			</tr>
		</table>
		<?php echo form_close(); ?>
	
		<?php if($openmodel=="yes"){ ?>
		
		
		<?php echo form_open_multipart($action3); ?>
			<table border="0"  style="font: 0.90em arial;width:690;" bgcolor="#8CC5FF">								
				<tr>
					<td align="left">
						Particular&nbsp;<br/>
						<textarea rows=3  name="reason_des"  cols=40></textarea>
					</td>
					<td align="left">
						Amount&nbsp;<br/><input type="text" value="0" name="reason_amount" />
					</td>
					
					<td align="left">
						<input type="submit" value="add"/>
					</td>	
				</tr>	
			</table>
		<?php echo form_close(); ?>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<div class="content" valign="top" align="left">	
			<div class="data" >
			<?php echo $table; ?>			
			</div>
		</div>
		
		
		
			
		<?php
		 echo form_open_multipart($action2); 
		 
		 $compfaz = array(1,3,7);
		 $utrack=strtolower($this->session->userdata('username'));
		 
		 ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">
			<?php if($entry==0){ ?>
			
				<?php if( in_array($this->validation->Company,$compfaz) ){?>
					<?php if($utrack!=2405 and $utrack!="003" and $dep_code==0){ ?>
					<tr>		
						<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
							<select name="forward" >					
								<?php
									foreach($depOfficerList->result() as $row)
									{ 					
										if($reportboss==$row->id)
										echo '<option selected value="'.$row->id.'">'.$row->Name.'--'.$row->Designation.'</option>';	
										else 
										echo '<option  value="'.$row->id.'">'.$row->Name.'--'.$row->Designation.'</option>';	
									}
								?>
							</select>
						</td>
					</tr>
					<?php	} else if($utrack==2405 and $this->validation->amount>50000){ ?>
					<tr>		
						<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
							<select name="forward" >					
								<option value="003">Director</option>
							</select>
						</td>
					</tr>
					<?php	} ?>
					
					
				
				<?php	} ?>
				<tr>
					
					<td>Remarks&nbsp;</td>
					<td>
						<textarea cols="40" rows="5" name="comment"  ></textarea>
					</td>		
				</tr>
			<?php } ?>	
				<tr>
					<td align="left">
						<input type="submit" value="<?php if($recommend==1)echo"Approve"; else echo "Final Submit"; ?> "    />
					</td>		
				</tr>	
			</table>
		<?php echo form_close(); } ?>

		</div>

</html>