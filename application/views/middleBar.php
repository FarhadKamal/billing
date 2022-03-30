<html>
<head>
    <style>
        body{
            margin:0px;
            background-color:#FFFFFF;
			
        }
		 .Heading {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 12px;
			border: 2px solid #FFFFFF;
		}
		
    </style>
</head>
<body>

<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  
  
  <tr><td class="Heading" colspan="3" align="right" height="40" style='vertical-align: middle'><font color="#A6A6A6"><?php echo 'log in as <b>'.$this->session->userdata('displayname').'</b>'; ?></font>
  
  <img style='vertical-align: middle' width=20 height=20 src='<?php echo base_url(); ?>/images/menu/logoff.gif' border=0 hspace=3 >&nbsp;<a href='<?php echo base_url(); ?>index.php/login/login/logout' target='_top' style="color:#A6A6A6">Logout</a>
  
  </td></tr>
  <tr>
  <td class="Heading" height="40" width="33.33%" align="left" style='vertical-align: bottom'><!--<img src='<?php echo base_url(); ?>/images/azneo.png'> --></td>
	
	<td class="Heading" width="33.33%" height="40" align="center" style='vertical-align: middle'>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td class="Heading" width="33.33%" height="40" align="right" style='vertical-align: bottom'><img src='<?php echo base_url(); ?>/images/billpay.png'></td>
	
  </tr>
</table>
</body>
</html>