<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="<?php echo base_url();?>style/button.css" rel="stylesheet" type="text/css" />
</head>
<body>

	
		<table border="0" width="100%">
			
			<tr>
				<td align="center" >
				<?php if(strtolower($this->session->userdata('username'))!='haldahead' and strtolower($this->session->userdata('username'))!='scost' and strtolower($this->session->userdata('username'))!='azhead' ){ ?>
				
				<?php } ?>
				<br>
				<!--
				<?php if(strtolower($this->session->userdata('username'))=='accounthead'){ ?>
				<a  href="<?php echo $AuthChangeLink.'AH/accounthead'; ?>" >
				<div>
					<img style='vertical-align: middle' width=20 height=20 src='<?php echo base_url();?>/images/left_arrow.gif'><input type="submit" class="submit" value="Switch to Supervisor Mode" />							
				</div>
				</a>
				<?php } ?>
				-->
				
				
				<?php if(strtolower($this->session->userdata('username'))=='dhkhead'){ ?>
				<a  href="<?php echo $AuthChangeLink.'AH/dhkhead'; ?>" >
				<div>
					<img style='vertical-align: middle' width=20 height=20 src='<?php echo base_url();?>/images/left_arrow.gif'><input type="submit" class="submit" value="Switch to Supervisor Mode" />							
				</div>
				</a>
				<?php } ?>
				
				
				<?php
				if($this->session->userdata('username')=='1016'){ ?>
				<a  href="<?php echo $AuthChangeLink.'AH/1016'; ?>" >
				<div>
					<img style='vertical-align: middle' width=20 height=20 src='<?php echo base_url();?>/images/left_arrow.gif'><input type="submit" class="submit" value="Switch to Account Head Mode" />							
				</div>
				</a>
				<?php } ?>
				
				
				<!--
				
				<?php if($this->session->userdata('username')=='1508'){ ?>
				<a  href="<?php echo $AuthChangeLink.'AH/1508'; ?>" >
				<div>
					<img style='vertical-align: middle' width=20 height=20 src='<?php echo base_url();?>/images/left_arrow.gif'><input type="submit" class="submit" value="Switch to Account Head Mode" />							
				</div>
				</a>
				<?php } ?>
				-->
					<img style='vertical-align: middle' src='<?php echo base_url(); ?>/images/banner3.png' border=0>
				</td>
			</tr>
		</table>
	
	
</body>
	</html>