<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css"
	rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css"
	rel="stylesheet" type="text/css" />
<script type="text/javascript"
	src="<?php echo base_url(); ?>script/calendar.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>script/jquery.js">
</script>
<script type="text/javascript">
	$(document).ready(function() {

		if ($('#contractual_checkbox').is(':checked'))
			$("#contractual_text").removeAttr("disabled");
		else $("#contractual_text").attr("disabled", "disabled");

		if ($('#chequeTarget').val() == "Cheque") {

			$('#trcheque').show();
		} else $('#trcheque').hide();


		$('#chequeTarget').change(function() {
			if ($('#chequeTarget').val() == "Cheque") {

				$('#trcheque').show();
			} else $('#trcheque').hide();
		});

		$('#contractual_checkbox').click(function() {

			if ($(this).is(':checked'))
				$("#contractual_text").removeAttr("disabled");
			else $("#contractual_text").attr("disabled", "disabled");


		});
		$('#auth_deduct').bind("keyup change", function(e) {
			var deduct = 0;

			try {
				deduct = $('#auth_deduct').val();
			} catch (err) {

				deduct = 0;
			}

			$('#pay_amount').val($('#net_amount').val() - deduct);
		});

	});
</script>
<style type="text/css">
	.container {
		list-style: none;
		padding: 5px 0 4px 0;
		margin: 0 0 0 10px;
		font: 0.90em arial;
		border: 1px solid #ccc;
		border-top: none;
	}
</style>
<div class="content"><input type="hidden" id="loc"
		value="<?php echo base_url(); ?>index.php/work/work/" />
	<h1><?php echo $title; ?>
	</h1>
	<?php echo $message; ?>
</div>
<div class="content">
	<div class="data">
		<?php echo form_open_multipart($action); ?>
		<table border="0" style="width: 1000px;	font: 0.90em arial;">
			<tr>
				<td>Location&nbsp;<span style="color:red;">*</span></td>
				<td>
					<select name="loc">
						<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<option value="1" <?php if ($this->validation->loc == 1) {
    echo "selected";
} ?>>Chittagong
							Head Office
						</option>
						<option value="2" <?php if ($this->validation->loc == 2) {
    echo "selected";
} ?>>Dhaka
							Office
						</option>
						<option value="3" <?php if ($this->validation->loc == 3) {
    echo "selected";
} ?>>Mohakhali
							Office
						</option>
					</select><?php echo $this->validation->loc_error; ?>
				</td>
			</tr>
			<tr>
				<td align="left">Billing&nbsp;Date:&nbsp;<span style="color:red;">*</span></td>
				<td align="left" align="left">
					<input type="text" name="bill_date" onclick="displayDatePicker('bill_date');" class="text"
						value="<?php echo $this->validation->bill_date; ?>" />
					<a href="javascript:void(0);" onclick="displayDatePicker('bill_date');"><img
							src="<?php echo base_url(); ?>style/images/calendar.png"
							alt="calendar" border="0"></a>
					<?php echo $this->validation->bill_date_error; ?>
				</td>
				<td>Company&nbsp;Name&nbsp;<span style="color:red;">*</span></td>
				<td>
					<select name="Company">
						<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<?php
                        foreach ($company->result() as $row) {
                            if ($row->iId == $this->validation->Company) {
                                echo '<option value="' . $row->iId . '" selected="selected">' . $row->vCompany . '</option>';
                            } else {
                                echo '<option value="' . $row->iId . '">' . $row->vCompany . '</option>';
                            }
                        }
                        ?>
					</select><?php echo $this->validation->Company_error; ?>
				</td>
			</tr>

			<tr>
				<td align="left">Description&nbsp;<span style="color:red;">*</span></td>
				<td align="left">
					<textarea rows=3 name="bill_description"
						cols=40><?php echo $this->validation->bill_description; ?></textarea>
					<?php echo $this->validation->bill_description_error; ?>
				</td>

				<td align="left">Total&nbsp;Amount&nbsp;</td>
				<td align="left" align="left">
					<input type="text" name="amount" id="contractual_text" class="text"
						value="<?php echo $this->validation->amount; ?>" />
					&nbsp;Contractual&nbsp;<input name="contractual_status" id="contractual_checkbox" value="1" <?php if ($this->validation->contractual_status == 1) {
                            echo "checked";
                        } ?>
					type="checkbox" />
				</td>
			</tr>


			<tr>
				<td>Payment&nbsp;Method&nbsp;<span style="color:red;">*</span>&nbsp;</td>
				<td>
					<select name="payment_type" id="chequeTarget">
						<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<option <?php if ($this->validation->payment_type == "Cash") {
                            print("selected");
                        } ?>
							value="Cash">Cash
						</option>
						<option <?php if ($this->validation->payment_type == "Cheque") {
                            print("selected");
                        } ?>
							value="Cheque">Cheque
						</option>
						<option <?php if ($this->validation->payment_type == "DD") {
                            print("selected");
                        } ?>
							value="DD">DD
						</option>
						<option <?php if ($this->validation->payment_type == "TT") {
                            print("selected");
                        } ?>
							value="TT">TT
						</option>
						<option <?php if ($this->validation->payment_type == "Pay-Order") {
                            print("selected");
                        } ?>
							value="Pay-Order">Pay Order
						</option>
					</select>
					<?php echo $this->validation->payment_type_error; ?>
				</td>

				<?php

                if ($entry == 1) { ?>

				<td>Reporting&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;</td>
				<td><select name="ReportingOfficer">
						<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<?php
                            foreach ($reportingOfficerList->result() as $row) {
                                if ($row->id == $this->validation->ReportingOfficer) {
                                    echo '<option value="' . $row->id . '" selected="selected">' . $row->Name . '--' . $row->Designation . '</option>';
                                } else {
                                    echo '<option value="' . $row->id . '">' . $row->Name . '--' . $row->Designation . '</option>';
                                }
                            }
                            ?>
					</select><?php echo $this->validation->ReportingOfficer_error; ?>
				</td> <?php  } ?>
			</tr>
			<tr id="trcheque">

				<td align="left">Cheque&nbsp;Name</td>
				<td align="left" align="left">
					<input type="text" name="suggested_cheque" size="40" class="text"
						value="<?php echo $this->validation->suggested_cheque; ?>" />
					<?php echo $this->validation->suggested_cheque_error; ?>
				</td>
			</tr>


			<td align="left"><input type="submit" value="Save" /></td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<?php if ($openmodel == "yes") {
                                ?>

		<div class="content" valign="top" align="left">
			<div class="data">
				<?php echo $table2; ?>
			</div>
		</div>


		<?php echo form_open_multipart($action4); ?>
		<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">
			<tr>
				<td align="left">
					Referance&nbsp;No&nbsp;<input type="text" name="req_no" />
				</td>
				<td align="left">
					Pump&nbsp;Model&nbsp;
					<select name="pump_model">
						<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<?php
                            foreach ($pump->result() as $row) {
                                if ($row->model_no == "Miscellaneous") {
                                    echo '<option value="' . $row->model_no . '" selected>' . $row->model_no . '</option>';
                                } else {
                                    echo '<option value="' . $row->model_no . '">' . $row->model_no . '</option>';
                                }
                            } ?>
					</select>
				</td>
				<td align="left">
					Quantity&nbsp;<input type="text" size="4" name="pump_qty" />
				</td>

				<td align="left">
					<?php if ($this->validation->contractual_status == 0) {	?>
					Amount&nbsp;<input type="text" value="0.00" name="pump_amount" />
					<?php } ?>
					<input type="submit" value="add" />
				</td>

			</tr>
		</table>
		<?php echo form_close(); ?>



		<div class="content" valign="top" align="left">
			<div class="data">
				<?php echo $table; ?>
			</div>
		</div>
		<!--
		<?php echo form_open_multipart($action2); ?>
		<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">
			<tr>
				<td>
					<select name="pid">
						<?php

                foreach ($list_particular->result() as $row) {
                    echo '<option value="' . $row->id . '">' . $row->model_no . '</option>';
                } ?>
					</select>
				</td>
				<td align="left">
					Referance&nbsp;No&nbsp;<input type="text" size="50" name="referance_no" />
					<input type="submit" value="add" />
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>


		-->







		<div class="content" valign="top" align="left">
			<div class="data">
				<?php echo $table3; ?>
			</div>
		</div>

		<?php //Document

            echo form_open_multipart($action5); ?>
		<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">
			<tr>
				<td align="left">
					Document&nbsp;Name&nbsp;<input type="text" size="50" name="doc_file" />
					<input type="hidden" value="" name="requisition" />
					<input type="submit" value="add" />
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>



		<?php

            if ($entry == 1) {
                echo form_open_multipart($action3); ?>
		<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">
			<tr>
				<td align="left">
					<input type="submit" value="Final Submit" />
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>

		<?php
            }
                                if ($recommend == 1) {
                                    echo form_open_multipart($action3);

                                    $azcomp = array(6, 14, 18);
                                    $comp = array(1, 3, 5, 7, 10, 2, 9, 8);
                                    $hcomp = array(4, 12);
                                    $novocomp = array(22, 23); ?>

		<table border="0" style="font: 0.90em arial;" bgcolor="#8CC5FF">

			<?php if ($usercode != "audit"     and  in_array($this->validation->Company, $azcomp)   and  $this->validation->az_status == 0) { ?>

			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<option value="audit"> Audit </option>
					</select>
				</td>
			</tr>

			<?php } elseif ($usercode != "audit"     and  in_array($this->validation->Company, $hcomp)   and  $this->validation->az_status == 0) {
                                        $utrack = strtolower($this->session->userdata('username')); ?>

			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<?php if ($utrack == 1085) { ?>
						<option value="audit">Audit</option>
						<?php } else { ?>

						<option value="1085">Subikash Chakraborty</option>
						<?php  } ?>
					</select>
				</td>
			</tr>

			<?php
                                    } elseif ($usercode != "audit"     and  in_array($this->validation->Company, $novocomp)   and  $this->validation->az_status == 0) {
                                        $utrack = strtolower($this->session->userdata('username')); ?>

			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<?php if ($utrack == 2571) { ?>
						<option value="audit">Audit</option>
						<?php } else { ?>

						<option value="2571">Mohammed Nurul Absar Chowdhury</option>
						<?php  } ?>
					</select>
				</td>
			</tr>

			<?php
                                    } elseif ($usercode != "audit"     and  (in_array($this->validation->Company, $novocomp) or in_array($this->validation->Company, $hcomp) or in_array($this->validation->Company, $azcomp))   and  $this->validation->az_status == 1) { ?>

			<?php $utrack = strtolower($this->session->userdata('username'));  ?>

			<?php if ($this->validation->amount > 50000  and  $utrack == 2023) { ?>




			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<option value="director">Director</option>
					</select>
				</td>
			</tr>


			<?php } elseif ($this->validation->amount > 50000  and  $utrack == 2346 and  in_array($this->validation->Company, array(4, 12))) { ?>

			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<option value="director">Director</option>
					</select>
				</td>
			</tr>



			<?php } elseif ($this->validation->amount > 50000  and  $utrack == 2346 and  in_array($this->validation->Company, array(2, 8, 14, 9))) { ?>

			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<option value="director">Director</option>
					</select>
				</td>
			</tr>

			<?php } elseif ($this->validation->amount > 50000  and  $utrack == 2405 and  in_array($this->validation->Company, array(6))) { ?>

			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<option value="director">Director</option>
					</select>
				</td>
			</tr>



			<?php } elseif ($this->validation->amount > 50000  and  $utrack == 2405 and  in_array($this->validation->Company, array(18))) { ?>
			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<option value="director">Director</option>
					</select>
				</td>
			</tr>

			<?php  } else { ?>

			<tr>
				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;
					<select name="ReportingOfficer">
						<option value="account">Account</option>
					</select>
				</td>
			</tr>
			<?php } ?>

			<?php } elseif ($usercode != "audit"  and  in_array($this->validation->Company, $comp)) { ?>
			<tr>

				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;


					<?php if (($usercode == 3 and $this->validation->amount <= 10000) or ($usercode == 2 and $this->validation->amount <= 50000)  or $usercode == 1) { ?>

					<select name="ReportingOfficer">
						<option value="audit">audit</option>
						<option value="3">Director</option>
					</select>

					<?php } else { ?>
					<select name="ReportingOfficer" required>
						<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<?php
                                        foreach ($reportingOfficerList2->result() as $row) {
                                            echo '<option value="' . $row->id . '">' . $row->Name . '--' . $row->Designation . '</option>';
                                        }
                                        ?>
					</select>
				</td>
				<?php }



                                $net_amount = floatval($this->validation->amount) - floatval($this->validation->tds) - floatval($this->validation->vds) - floatval($this->validation->advance) -  floatval($this->validation->tot_auth_deduction);


                        ?>




			</tr>
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td>Net Amount:</td>
							<td><input type='text' readonly
									value="<?php echo $net_amount; ?>"
									id="net_amount"></td>
							<td>Deduction:</td>
							<td><input type='number' value="0" id="auth_deduct" name="auth_deduct"></td>
							<td>Payable Amount:</td>
							<td><input type='text' readonly
									value="<?php echo $net_amount; ?>"
									id="pay_amount"></td>
						</tr>
						<table>
				</td>
			</tr>
			<?php } ?>



			<?php if ($usercode == "audit"   and  in_array($this->validation->Company, $comp)) {
                            $net_amount = floatval($this->validation->amount) - floatval($this->validation->tds) - floatval($this->validation->vds) - floatval($this->validation->advance);

                            $pay_amount = $net_amount - floatval($this->validation->tot_auth_deduction); ?>
			<tr>

				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;<select
						name="ReportingOfficer">
						<option value="account">Account</option>
						<option value="claimer">Claimer</option>
						<option value="director">Director</option>
						<option value="dep">Department</option>
					</select>
				</td>


			</tr>
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td>Net Amount:</td>
							<td><input type='text' readonly
									value="<?php echo $net_amount; ?>">
							</td>
							<td>Authority Deduction:</td>
							<td><input type='text' readonly
									value="<?php echo $this->validation->tot_auth_deduction; ?>">
							</td>
							<td>Payable Amount:</td>
							<td><input type='text' readonly
									value="<?php echo $pay_amount; ?>">
							</td>
						</tr>
						<table>
				</td>
			</tr>
			<?php
                        } elseif ($usercode == "audit" and  (in_array($this->validation->Company, $novocomp) or  in_array($this->validation->Company, $hcomp) or in_array($this->validation->Company, $azcomp))) {
                            $net_amount = floatval($this->validation->amount) - floatval($this->validation->tds) - floatval($this->validation->vds) - floatval($this->validation->advance);

                            $pay_amount = $net_amount - floatval($this->validation->tot_auth_deduction); ?>
			<tr>

				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;<select
						name="ReportingOfficer">
						<?php if ($this->validation->Company == 6) {  ?>
						<option value="fin">Finance Head (GEP)</option>
						<?php } else { ?>
						<option value="fin">Finance Head</option>
						<?php } ?>
						<option value="claimer">Claimer</option>
						<option value="dep">Department Head</option>
					</select>
				</td>


			</tr>
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td>Net Amount:</td>
							<td><input type='text' readonly
									value="<?php echo $net_amount; ?>">
							</td>
							<td>Authority Deduction:</td>
							<td><input type='text' readonly
									value="<?php echo $this->validation->tot_auth_deduction; ?>">
							</td>
							<td>Payable Amount:</td>
							<td><input type='text' readonly
									value="<?php echo $pay_amount; ?>">
							</td>
						</tr>
						<table>
				</td>
			</tr>
			<?php
                        } elseif (in_array($this->validation->Company, array(21, 24))) {
                            $net_amount = floatval($this->validation->amount) - floatval($this->validation->tds) - floatval($this->validation->vds) - floatval($this->validation->advance);

                            $pay_amount = $net_amount - floatval($this->validation->tot_auth_deduction); ?>
			<tr>

				<td colspan="2">Forward&nbsp;To&nbsp;<span style="color:red;">*</span>&nbsp;<select
						name="ReportingOfficer">
						<?php if ($this->session->userdata('username') == 2405) { ?>
						<option value="account">Account</option>
						<?php } else { ?>
						<option value="fin">Finance Head</option>
						<option value="claimer">Claimer</option>
						<?php } ?>
					</select>
				</td>


			</tr>
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td>Net Amount:</td>
							<td><input type='text' readonly
									value="<?php echo $net_amount; ?>">
							</td>
							<td>Authority Deduction:</td>
							<td><input type='text' readonly
									value="<?php echo $this->validation->tot_auth_deduction; ?>">
							</td>
							<td>Payable Amount:</td>
							<td><input type='text' readonly
									value="<?php echo $pay_amount; ?>">
							</td>
						</tr>
						<table>
				</td>
			</tr>
			<?php
                        } ?>






			<tr>
				<td>Recommendation&nbsp;</td>
				<td>
					<textarea cols="40" rows="5" name="comment"></textarea>
				</td>
			</tr>
			<tr>
				<td align="left">
					<input type="submit" name="btnsubmit" value="Final Submit" />
					<?php
                    if ($entry==0) {
                        if ($this->session->userdata('authlevel')==6) {
                            echo anchor('bill/supervisebill/resubmitEmp/' . $billId, 'Resubmit&nbsp;to&nbsp;Employee', array('class' => 'delete'));
                        }
                    } ?>
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>




		<?php
                                }
                            }
        ?>




	</div>

</html>