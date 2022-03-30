<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<body>
	<div class="content" style="width:900; height:850;" valign="top" align="left" >
		<h1 valign="top">Employee's Movement List</h1><?php echo $message; ?>
		<?php echo form_open_multipart($action); ?>
			<div class="paging">
				<?php if($emp!=1){ ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php if($totalrecord!=''){echo 'TOTAL: '.$totalrecord;}?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="search_type"><option value="EmpId" >EMPLOYEE ID</option>
				<option value="SL" >Ref No</option><select>
				<input name="search_value" class="inputArea" type="text" size="10"/><input type="submit" value=">>"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
				<?php echo $pagination; ?>
			</div>
		<?php echo form_close(); ?>
		<div class="content" style="width:690; height:400;" valign="top" align="left">	
			<div class="data" ><?php echo $table; ?></div>
		</div>
	</div>
</body>
</html>