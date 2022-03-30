<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
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
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<body>
	<input type="hidden" id="loc" value="<?php echo base_url(); ?>index.php/bill/costbill/" /> 
	<div class="content" style="width:900; " valign="top" align="left" >
		<h1 valign="top"><?php  echo $title; ?></h1><?php echo $message; ?>
		
			<div class="content" style="width:690;" valign="top" align="left">	
			<div class="data" ><?php echo $table; ?></div>
		</div>

		
	</div>
</body>
</html>