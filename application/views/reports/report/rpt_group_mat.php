<?php if ($options == '2') { ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>Report</title>
	</head>

	<body>
	<?php } elseif ($options == '3') {
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Salary.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
} ?>


	<?php
	if ($results->result()) {



	?>
		<table border="0" CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top">
			<tr>
				<td align="Center"><b>Item & Month Wise Requistion Report, Year:<?php echo $year; ?> </b></td>
			</tr>
		</table><br />
		<table border="1" CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center">

			<tr>
				<td align="Center" width="1%"><b>SL</b></td>
				<td align="Center"><b>Name</b></td>
				<td align="Center"><b>January</b></td>
				<td align="Center"><b>February</b></td>
				<td align="Center"><b>March</b></td>
				<td align="Center"><b>April</b></td>
				<td align="Center"><b>May</b></td>
				<td align="Center"><b>June</b></td>
				<td align="Center"><b>July</b></td>
				<td align="Center"><b>August</b></td>
				<td align="Center"><b>September</b></td>
				<td align="Center"><b>October</b></td>
				<td align="Center"><b>November</b></td>
				<td align="Center"><b>December</b></td>
				<td align="Center"><b>Average</b></td>
				<td align="Center"><b>Total</b></td>
			</tr>
			<?php
			$sl = 0;

			foreach ($results->result() as $mydata) :
				$sl = $sl + 1;
			?>

				<tr>
					<td align="center"><b><?php echo $sl ?></b></td>
					<td align="center"><b><?php echo $mydata->mat_name ?></b></td>
					<td align="center"><b><?php echo $mydata->jan ?></b></td>
					<td align="center"><b><?php echo $mydata->feb ?></b></td>
					<td align="center"><b><?php echo $mydata->mar ?></b></td>
					<td align="center"><b><?php echo $mydata->apr ?></b></td>
					<td align="center"><b><?php echo $mydata->may ?></b></td>
					<td align="center"><b><?php echo $mydata->jun ?></b></td>
					<td align="center"><b><?php echo $mydata->jul ?></b></td>
					<td align="center"><b><?php echo $mydata->aug ?></b></td>
					<td align="center"><b><?php echo $mydata->sep ?></b></td>
					<td align="center"><b><?php echo $mydata->octb ?></b></td>
					<td align="center"><b><?php echo $mydata->nov ?></b></td>
					<td align="center"><b><?php echo $mydata->decm ?></b></td>
					<td align="center"><b><?php echo round($mydata->total / 12, 2) ?></b></td>
					<td align="center"><b><?php echo $mydata->total ?></b></td>
				</tr>
			<?php endforeach; ?>

		</table>


	<?php } else echo "No Record Found";
	if ($options == '2') { ?>
	</body>

	</html>
<?php } ?>