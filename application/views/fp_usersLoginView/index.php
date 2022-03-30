<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">

<head>
	<!--<link rel="shortcut icon" href="../icons/icon.png"/>-->
	<link rel="shortcut icon" href="images/pnlfav.ico" type="image/ico" />


	<title>Bill</title>
</head>
<table width='900' border="0" cellspacing='0' cellpadding='0' border='0' align='center' valign="top">
	<tr>
		<td>
			<font color="#FF0000"><?php $this->load->view('/header.php'); ?></font>
		</td>
	</tr>
	<tr>
		<td><br />
			<table width='100%' border="0" cellspacing='0' cellpadding='0' border='0' align='center'>

				<tr valign="top">
					<td width="100%" height="85%" align="center"><?php $this->load->view($page); ?></td>
				</tr>
			</table>
		</td>
	</tr>

</table>

</html>