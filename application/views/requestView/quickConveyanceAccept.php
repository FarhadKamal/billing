<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/jquery.js"></script>

<script type="text/javascript">
	$(document).ready(function() {



		$('#lot_no').change(function() {


			var lot = $("#lot_no").val();


			var pathloc = $("#loc").val() + "get_company_by_lot";

			//alert(lot);

			$.post(pathloc, {
					'data': lot
				},

				// when the Web server responds to the request
				function(result) {

					$('#companyid')
						.find('option')
						.remove()
						.end();

					// if the result is TRUE write a message return by the request
					if (result) {






						$('#companyid').append(result);
						// $("#horse_power").val (result);			  
					}
				}
			);



		});



		$('#companyid').change(function() {


			var lot = $("#lot_no").val();
			var company = $("#companyid").val();

			var pathloc = $("#loc").val() + "get_employee_by_lot";

			//alert(lot);

			$.post(pathloc, {
					'lot': lot,
					'company': company
				},

				// when the Web server responds to the request
				function(result) {

					$('#employeeid')
						.find('option')
						.remove()
						.end();

					// if the result is TRUE write a message return by the request
					if (result) {
						$('#employeeid').append(result);
						// $("#horse_power").val (result);			  
					}
				}
			);



		});



	});
</script>





<div class="content">
	<h1><?php echo $title; ?></h1>
	<?php echo $message; ?>
	<form method="post" action="<?php echo $action; ?>">
		<input type="hidden" id="loc" value="<?php echo base_url(); ?>index.php/request/request/" />
		<div class="data">
			<table border="0" style="width: 400px;">
				<tr>
					<td>Lot&nbsp;No&nbsp;<span style="color:red;"></span></td>
					<td>
						<select name="lot_no" id="lot_no">
							<option value="-1">-&nbsp;-SELECT-&nbsp;-</option>
							<?php
							foreach ($lot_list->result() as $row) {

								echo '<option value="' . $row->lot_no . '">' . $row->lot_no . '</option>';
							}
							?>
						</select><?php echo $this->validation->lot_no_error; ?>
					</td>

				</tr>
				<tr>
					<td>Company&nbsp;<span style="color:red;">*</span></td>
					<td>
						<select name="companyid" id="companyid">
							<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						</select><?php echo $this->validation->companyid_error; ?>
					</td>
				</tr>

				<tr>
					<td width="30">Employee&nbsp;Id</td>
					<td><select name="employeeid" id="employeeid">
							<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						</select><?php echo $this->validation->employeeid_error; ?></td>
				</tr>







				<tr>
					<td><input type="submit" value="Go" /></td>
				</tr>
			</table>
		</div>

	</form>
</div>

</html>