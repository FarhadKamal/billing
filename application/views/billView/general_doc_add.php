<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />

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
		Bill ID: 	<?php echo $billid; ?>		
		

	
		
		<div class="content" valign="top" align="left">	
			<div class="data" >
			<?php echo $table; ?>			
			</div>
		</div>
		
		<?php echo form_open_multipart($action2); ?>
			<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">								
				<input type="hidden" name="extra_doc" value="add_doc_general">
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
							<option value="GR">GR</option>
							<option value="Delivery Challan">Delivery Challan</option>
							<option value="Receiving Challan">Receiving Challan</option> 
							<option value="PO">PO</option>	
							<option value="Approval">Approval</option>
							<option value="Comparative Statement">Comparative Statement</option>
							<option value="Quotation">Quotation</option>
							<option value="PR">PR</option>
							<option value="Money Receipt">Money Receipt</option>	
							<option value="Supporting Doc.">Supporting Doc.</option>
							<option value="Mushak">Mushak</option>	
							<option value="Others">Others</option>
						</select>
						<input type="submit" value="add"/>
					</td>		
				</tr>	
			</table>
			<?php echo form_close(); ?>
		
		
		
		</div>

</html>