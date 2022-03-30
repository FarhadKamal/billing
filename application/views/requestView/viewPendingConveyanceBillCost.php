<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />

<head>
	<title>Pending Conveyance Bill</title>
</head>

<body>

	<div class="content">
		<div class="data">
			<table border="1" CELLPADDING="4" CELLSPACING="0" width="50%" align="center" valign="top">


				<?php if ($results->result()) {
					foreach ($results->result() as $mydata) : endforeach; ?>



					<tr>

						<td align="Center" colspan="8"><?php echo $mydata->vCompany; ?></td>
					</tr>
					<tr>
						<td align="Center" colspan="8">
							<h1><?php echo 'Pending Conveyance Bill '; ?></h1>
							<h2><?php echo $lot_no; ?></h2>
						</td>
					</tr>
					<tr align="Center">
						<td align="Center"><b>Applied By</b></td>
						<td align="Center"><b>Approved By</b></td>
						<td align="Center"><b>Paticulars</b></td>
						<td align="Center"><b>Business Area</b></td>
						<td align="Center"><b>Cost Center</b></td>
						<td align="Center"><b>Internal Order</b></td>
						<td align="Center"><b>Applied Date</b></td>
						<td align="Center">&nbsp;<b>Signature</b>&nbsp;</td>
					</tr>



					<?php
					$nettotal = 0;
					foreach ($results->result() as $mydata) :
						$nettotal = $nettotal + $mydata->amount;
					?>
						<tr>
							<td align="left">
								<table>
									<tr>
										<td><b><?php echo 'Employee&nbsp;Id&nbsp;:'; ?></b></td>
										<td><?php echo $mydata->vEmpId; ?></td>
									</tr>
									<tr>
										<td><b><?php echo 'Name&nbsp;:'; ?></b></td>
										<td><?php echo $mydata->vEmpName; ?></td>
									</tr>
									<tr>
										<td><b><?php echo 'Designation&nbsp;:'; ?></b></td>
										<td><?php echo $mydata->vDesignation; ?></td>
									</tr>
									<tr>
										<td><b><?php echo 'Department&nbsp;:'; ?></b></td>
										<td><?php echo $mydata->DeptName; ?></td>
									</tr>
								</table>
							</td>
							<td align="left">
								<table>
									<tr>
										<td><b><?php echo 'Name&nbsp;:'; ?></b></td>
										<td><?php echo $mydata->dname; ?></td>
									</tr>
									<tr>
										<td><b><?php echo 'Department&nbsp;:'; ?></b></td>
										<td><?php echo $mydata->ddes; ?></td>
									</tr>

								</table>
							</td>
							<td align="left">
								<table>
									<tr>
										<td>
											<table>
												<tr>
													<td><b><?php echo 'Purpose:&nbsp;'; ?></b></td>
													<td><?php echo $mydata->purpose; ?></td>
												</tr>
												<tr>
													<td><b><?php echo 'From:&nbsp;'; ?></b></td>
													<td><?php echo $mydata->vfrom; ?></td>
												</tr>
												<tr>
													<td><b><?php echo 'To:&nbsp;'; ?></b></td>
													<td><?php echo $mydata->vto; ?></td>
												</tr>
											</table>
										</td>
										<td>
											<table>
												<tr>
													<td><b><?php echo 'Transport Mode:&nbsp;'; ?></b></td>
													<td><?php echo $mydata->trans_mode; ?></td>
												</tr>
												<tr>
													<td><b><?php echo 'Amount:&nbsp;'; ?></b></td>
													<td><?php echo $mydata->amount; ?></td>
												</tr>
												<tr>
													<td><b><?php echo 'Updown:&nbsp;'; ?></b></td>
													<td><?php echo $mydata->updown; ?></td>
												</tr>
												<tr>
													<td><b><?php echo 'Journey&nbsp;Date:&nbsp;'; ?></b></td>
													<td><?php echo $mydata->journey_date; ?></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
							<td align="Center"><?php echo $mydata->area ?></td>
							<td align="Center"><?php echo $mydata->vCostCentre ?></td>
							<td align="Center"><?php echo $mydata->internal_order ?></td>
							<td align="Center"><?php echo $mydata->created_date ?></td>
							<td align="Center">&nbsp;</td>
						</tr>


				<?php endforeach;
				} else echo "No data found." ?>
				<tr>
					<td colspan=8><b>Net Total:&nbsp;</b><?php echo $nettotal; ?></td>
				</tr>
			</table>
		</div>
	</div>

</body>

</html>