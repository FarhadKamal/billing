<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />

<div class="content" style="width:710; ">
		<h1><?php echo $title; ?></h1>
		<?php echo $message;?>
		<div align='left'>
			Your Password Must Contain At Least 1 Number. Example: 1 2 3 9<br/>
			Your Password Must Contain At Least 1 Capital Letter. Example: A B C Z<br/>
			Your Password Must Contain At Least 1 Lowercase Letter Example: a b c z
		</div>
		<form method="post" action="<?php echo $action; ?>">
		<div class="data">
		<table border="0">
		
			<input type="hidden" name="Id" class="text" size="30%" readonly="true" value="<?php echo (string)$this->validation->Id; ?>"/></td>
			
			<tr>
				<td>UserName&nbsp;<span style="color:red;">*</span></td>
				<td><input type="text" name="username" class="text" size="25%" readonly="true" value="<?php echo $this->validation->username; ?>"/></td>
			</tr>
			<tr>
				<td>Old&nbsp;Password&nbsp;<span style="color:red;">*</span></td>
				<td><input type="password" name="oldpassword" class="text" size="25%" value="<?php echo $this->validation->oldpassword; ?>"/>
				<?php echo $this->validation->oldpassword_error; ?></td>
			</tr>
			<tr>
				<td>New&nbsp;Password&nbsp;<span style="color:red;">*</span></td>
				<td><input type="password" name="newpassword" class="text" size="35%" value="<?php echo $this->validation->newpassword; ?>"/>
				<?php echo $this->validation->newpassword_error; ?></td>
			</tr>
			<tr>
				<td>Retype&nbsp;New&nbsp;Password&nbsp;<span style="color:red;">*</span></td>
				<td><input type="password" name="retypepassword" class="text" size="35%" value="<?php echo $this->validation->retypepassword; ?>"/>
				<?php echo $this->validation->retypepassword_error; ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4"><input type="submit" value="Save"/></td>
			</tr>
		</table>
		</div>
		</form>
</div>
</html>