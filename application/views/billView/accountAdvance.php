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
					
					<td>Net&nbsp;Pay&nbsp;</td>
					<td align="left">					
						<input type="text" readonly value="<?php echo $netaccount; ?>" />
					</td>		
				</tr>
				<tr>
					
					<td>Balance&nbsp;</td>
					<td align="left">					
						<input type="text" readonly value="<?php echo $balance; ?>" />
					</td>		
				</tr>
				
			<tr>
				<td>Account&nbsp;Head&nbsp;<span style="color:red;">*</span></td>
				<td>	
			    <select name="account_head" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<?php 
					foreach($accounthead->result() as $row)
					{ 						
						echo '<option value="'.$row->account.'##'.$row->code.'">'.$row->account.'##'.$row->code.'</option>';						
					} 
				?>
				</select><?php echo $this->validation->account_head_error; ?></td>	
			</tr>
			<tr>			
			<td>Vendor&nbsp;Name&nbsp;<span style="color:red;">*</span></td><td>
				<select name="Vendor" ><option value="">-&nbsp;-SELECT-&nbsp;-</option>
				<?php
					foreach($vendor->result() as $row)
					{ 
						
							echo '<option value="'.$row->vendor_name.' ## '.$row->vendor_code.'">'.$row->vendor_name.' ## '.$row->vendor_code.'</option>';
						
					}
				?>
				</select><?php echo $this->validation->Vendor_error; ?></td>	
			</tr>
			<tr>
				<td>Amount&nbsp;<span style="color:red;">*</span></td>
				<td>	
			    <input type="text" name="divide_amount" value="<?php echo $balance; ?>" /><?php echo $this->validation->divide_amount_error; ?></td>	
			</tr>
			<tr>
				<td>Remark&nbsp;</td>
				<td>
					<textarea cols="40" rows="5" name="remark"  ></textarea>
				</td>		
			</tr>	
				
			<tr>				
				<td ><input type="submit" value="add"/></td>
			</tr>
		</table>
		</div><br/>
		
		<?php echo form_close(); 
			if($balance==0){
			echo form_open_multipart($action2); ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">				
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
			<?php echo form_close(); }?>
		<div class="content" valign="top" align="left">	
			<div class="data" >
			<?php echo $table; ?>			
			</div>
		</div>
		
</div>
</html>