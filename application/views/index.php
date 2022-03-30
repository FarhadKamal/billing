<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">

<head>
	<link rel="shortcut icon" href="../icons/icon.png" />
	<title>Bill</title>
	<style>
		.flex-container {
			display: flex;
			flex-wrap: wrap;


		}

		.flex-container>div {
			background-color: #b7f5f4;

			margin: 10px;
			text-align: center;
			font-size: 30px;

		}
	</style>
</head>
<table border="0" align='left' valign="top">
	<tr>
		<td><?php $this->load->view('middleBar.php'); ?></td>
	</tr>
	<tr>
		<td><?php $this->load->view('main_menu.php'); ?></td>
	</tr>
	<tr>
		<td align="center"><?php $this->load->view($page); ?></td>
	</tr>
	<tr>
		<td><?php $this->load->view('footer.php'); ?></td>
	</tr>
</table>

</html>