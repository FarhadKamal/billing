<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset="utf-8" />
	<link href="<?php echo base_url(); ?>style/bill.css" rel="stylesheet" type="text/css" />
	</head>
<body>

<form method="post" class="form-style-9" action="<?php echo $action ?>">
<div align="center"> <img src="<?php echo base_url(); ?>images/billpay.png" /></div>

<h1>
<span>In House Billing System</span>
</h1>
<h2>Log In to your Account</h2>
<font color="red" size="2"><?php  echo $error ?></br></font>

<ul>
<li>
    <input type="text" name="user_id" id="user_id"class="field-style field-split align-none" placeholder="User Name" view="<?php echo $this->validation->user_id; ?>"/>
    <font color="red" size="1.5"><?php echo $this->validation->user_id_error; ?></font>
</li>
<li>
    <input type="password" name="password" id="password" class="field-style field-split align-none" placeholder="Password" view="<?php echo $this->validation->password; ?>"/>
    <font color="red" size="1.5"><?php echo $this->validation->password_error; ?></font>
</li>

<li>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;
		
        <input  type="submit" class="submit" value="Log In" />
		
</li>
</ul>
</br>
<!--
<div align="center"> <img src="<?php //echo base_url(); ?>images/logo.png" /></div>
<h1>Users' Login</h1>
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<font color="red" size="3"><?php  //echo $error ?></br></font>
</br>

	<label>
		<span>User Name: </span><input name="user_id" type="text" id="user_id" view="<?php //echo $this->validation->user_id; ?>"/>
		<?php //echo $this->validation->user_id_error; ?>	
	</label>
	
	<label>
		<span>Password : </span><input name="password" type="password" id="password" view="<?php //echo $this->validation->password; ?>"/>
		<?php //echo $this->validation->password_error; ?>
	</label>
	
	<label>
        
        <input  type="submit" class="submit" value="Log In >>" />
    </label>
</br>
-->
<!--
<div align='center' style="font-size:0.77em; border-top:1px solid #C6C6C6; margin-left: 60px;
	margin-right: 60px; margin-top: 30px; margin-bottom: 10px; padding-top: 0.5em;">

         @ Copyright 2019 AZNEO Ltd. All Rights Reserved.

</div> -->
</form>
	
</body>
</html>