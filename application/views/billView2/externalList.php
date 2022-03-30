<?php

if($this->input->post('from')<>0 and $this->input->post('to')<>0)
{
$from	=	$this->input->post('from');
$to		=	$this->input->post('to');
}
else{ 


$to= date("d-m-Y");

$from= date('d-m-Y',(strtotime ( '-30 day' , strtotime ( $to) ) ));
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>script/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>script/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

//$("#myTable").tablesorter();

$.tablesorter.addParser({
        id: 'fancyNumber',
        is:function(s){return false;},
        format: function(s) {return s.replace(/[\,\.]/g,'');},
        type: 'numeric'
    });
    $("#myTable").tablesorter({headers: {7: {sorter: 'fancyNumber'}}});


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
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />

<body>
	<input type="hidden" id="loc" value="<?php echo base_url(); ?>index.php/bill/external/" /> 
	<div class="content" style="width:1150; " valign="top" align="left" >
		<h1 valign="top"><?php  echo $title; ?></h1><?php echo $message; ?>
		
			<div class="paging">
				
				<?php echo form_open_multipart($action); ?>
				<table  style="border: 1px solid black;"><tr><td><font color='BLUE'>From&nbsp;Date:</font></td>
				<td><input type="text" value="<?php echo $from; ?>"  name="from" onclick="displayDatePicker('from');" class="text" value=""/>
				<a href="javascript:void(0);" onclick="displayDatePicker('from');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				</td></tr>
				
				<tr>
				<td><font color='BLUE'>To&nbsp;Date:</font></td>
				<td><input type="text"  name="to"  value="<?php echo $to; ?>" onclick="displayDatePicker('to');" class="text" value=""/>
				<a href="javascript:void(0);" onclick="displayDatePicker('to');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
				</td>
				<td>&nbsp;&nbsp;&nbsp;<input type="hidden" name="con" value="2"/><input type="submit" value=">>"/></td>
				</tr>
				</table>
				<?php echo form_close(); ?>
	

				
				
				<?php echo form_open_multipart($action); ?>
				<table  style="border: 1px solid black;">
				<tr>
						<td ><font color='BLUE'>Sap ID&nbsp;</font>
						<input type="text" name="sapid" /><input type="hidden" name="con" value="3"/>&nbsp;<input type="submit" value=">>"/>
						</td>
		
					</tr>
				</table>
				<?php echo form_close(); ?>
			</div>
		<a href="<?php echo $action; ?>">Refresh</a>
		<div class="content" style="width:690;" valign="top" align="left">	
			<div class="data" ><?php if($totalrecord!=''){echo 'TOTAL: '.$totalrecord;}?><?php echo $table; ?><?php echo $pagination; ?></div>
		</div>
		
	</div>
</body>
</html>