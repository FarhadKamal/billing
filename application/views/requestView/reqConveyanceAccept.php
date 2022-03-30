<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
<div class="content">
	<h1><?php echo $title; ?></h1>
	<?php echo $message; ?>
	<?php echo form_open_multipart($action); ?>
	<div class="data">
		<table border="0" style="width: 400px;">
			<tr>
				<td>Lot&nbsp;No&nbsp;<span style="color:red;"></span></td>
				<td>
					<input type="text" name="lot_no" readonly value="<?php echo $this->validation->lot_no ?>" />

			</tr>
			<tr>
				<td>Business&nbsp;Area&nbsp;<span style="color:red;">*</span></td>
				<td>
					<select name="Company">
						<option value="6">Common</option>
						<?php
						foreach ($area->result() as $row) {
							if ($row->id == $this->validation->Company) {
								echo '<option value="' . $row->id . '" selected="selected">' . $row->area . '</option>';
							} else {
								echo '<option value="' . $row->id . '">' . $row->area . '</option>';
							}
						}
						?>
					</select><?php echo $this->validation->Company_error; ?>
				</td>
			</tr>
			</tr>

			<tr>
				<td>Cost&nbsp;Centre&nbsp;<span style="color:red;">*</span></td>
				<td>
					<select name="CostCentre">
						<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<?php
						foreach ($costcentre->result() as $row) {
							if ($row->iId == $this->validation->CostCentre) {
								echo '<option value="' . $row->iId . '" selected="selected">' . $row->vCostCentre . '</option>';
							} else {
								echo '<option value="' . $row->iId . '">' . $row->vCostCentre . '</option>';
							}
						}
						?>
					</select><?php echo $this->validation->CostCentre_error; ?>
				</td>
			</tr>

			<tr>
				<td>Internal&nbsp;Order&nbsp;<span style="color:red;">*</span></td>
				<td>
					<select name="internal_order">
						<option value="">-&nbsp;-SELECT-&nbsp;-</option>
						<?php
						foreach ($internal_order->result() as $row) {
							echo '<option value="' . $row->Order . '">' . $row->Order . '&nbsp;###&nbsp;' . $row->Description . '</option>';
						}
						?>
					</select><?php echo $this->validation->internal_order_error; ?>
				</td>
			</tr>



			<tr>
				<td><input type="submit" value="Save" /></td>
			</tr>
		</table>
	</div>

	<?php echo form_close(); ?>
</div>

</html>