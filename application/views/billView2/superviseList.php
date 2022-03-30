<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$(function() {
    $('div .divcost').dblclick(function() {
	
	
		var billid= $(this).attr('id'); 
	
		var pathloc=$("#loc").val()+"get_doc_details";
		//alert(pathloc);
		$.post(pathloc,
					  { 'data':billid },

					  // when the Web server responds to the request
					  function(result) { 
						// if the result is TRUE write a message return by the request
						   if (result) {	
							//alert(billid);	
							$('#'+billid).empty();

								$('#'+billid).append(result);	
						 // $("#horse_power").val (result);			  
						}
						}
					);
	
	
	
	
	
	
	});
	
	
	
});







});
</script>
<body>
	<input type="hidden" id="loc" value="<?php echo base_url(); ?>index.php/bill/supervisebill/" /> 
	<div class="content" style="width:900; " valign="top" align="left" >
		<h1 valign="top"><?php  echo $title; ?></h1><?php echo $message; ?>
		
			<div class="paging">
				
				<?php echo form_open_multipart($action); ?>
				<table><tr><td><font color='BLUE'>From&nbsp;Date:</font></td>
				<td><input type="text" readonly name="from" onclick="displayDatePicker('from');" class="text" value=""/>
				<a href="javascript:void(0);" onclick="displayDatePicker('from');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				</td></tr>
				
				<tr>
				<td><font color='BLUE'>To&nbsp;Date:</font></td>
				<td><input type="text" readonly name="to" onclick="displayDatePicker('to');" class="text" value=""/>
				<a href="javascript:void(0);" onclick="displayDatePicker('to');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				&nbsp;&nbsp;&nbsp;<input type="submit" value=">>"/></td>
				</tr>
				</table>
				<?php echo form_close(); ?>
				
				
				<?php echo form_open_multipart($action2); ?>
				<table>
				<tr>
						<td ><font color='BLUE'>Search&nbsp;by&nbsp;</font>
						<select name="stype">
							<option value="d.vEmpName">Submitted By</option>
							<option value="bill_description">Description</option>
							<option value="amount">Amount</option>
							<option value="id">Bill id</option>
							<option value="c.vCompany">Company</option>
							
						</select>
						</td>
						<td>			
						<input type="text" name="svalue" size="40" />&nbsp;&nbsp;&nbsp;<input type="submit" value=">>"/></td>			
					</tr>
				</table>
				<?php echo form_close(); ?>
						
			</div>
		
		<div class="content" style="width:690;" valign="top" align="left">	
			<div class="data" ><?php if($totalrecord!=''){echo 'TOTAL: '.$totalrecord;}?><?php echo $table; ?><?php echo $pagination; ?></div>
		</div>
		
	</div>
</body>
</html>