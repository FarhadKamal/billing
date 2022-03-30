<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859" />
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>style/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>script/calendar.js"></script>
<script language="javascript" src="<?php echo base_url(); ?>script/jquery.js" ></script>

<div class="content" style="width:710; height:400;">
	<h1><?php echo 'Reports Parameter'; ?></h1>
	<form method="post" action="<?php echo $action; ?>" target="_blank">
	<!-- input type="hidden" name="type" value=<?php //echo $type; ?> -->
		<div class="data" >
			<table border="0">
			<?php if ($lotShow=='y') {?>
				<tr>
					<td width="30">Lot&nbsp;No</td>
					<td><input type="text"  name="lot_no"  /></td>
				</tr>
			<?php } ?>
			
			
		
			<?php  if ($sap_id=='y'){?>


				<tr>
					<td width="30">ID:&nbsp;</td>
					<td><input type="text" size="30" name="id" />
					</td>			
				</tr>
			<?php  } ?>
			
			
			<?php  if ($yearID=='y'){?>


				<tr>
					<td width="30">Year:&nbsp;</td>
					<td><input type="text" size="10" name="yearID" />
					</td>			
				</tr>
			<?php  } ?>
						
			<?php
			
			if ($fromdateShow =='y') { ?>
				<tr>
					<td width="30">From&nbsp;(dd-mm-yyyy)</td>
					<td><input type="text" name="from" onclick="displayDatePicker('from');" class="text" value=<?php echo date('d-m-Y',strtotime(date('Y-m-d')));?> />
					<a href="javascript:void(0);" onclick="displayDatePicker('from');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a></td>
				</tr>
			<?php } if ($todateShow =='y') { ?>	
				<tr>
					<td width="30">To&nbsp;(dd-mm-yyyy)</td>
					<td><input type="text" name="to" onclick="displayDatePicker('to');" class="text" value=<?php echo date('d-m-Y',strtotime(date('Y-m-d')));?> />
					<a href="javascript:void(0);" onclick="displayDatePicker('to');"><img src="<?php echo base_url(); ?>style/images/calendar.png" alt="calendar" border="0"></a>
					</td>
				</tr>
			
			<?php }if ($companylist =='y') { ?>	
				<tr>
				<td>Company&nbsp;Name&nbsp;<span style="color:red;">*</span></td>
				<td>
                <select name="company"> 
                    <option value="1,2,3,5,6,7,9,10,18">All</option>
					<option value="1">Pedrollo nk Limited</option>
					<option value="2">Polyex Print Limited</option>
					<option value="3">Pragati Corporation</option>
					<option value="5">Poly Tape Ltd</option>
					<option value="6">GEP Holdings Ltd</option>
					<option value="7">PNL Holdings Ltd</option>
					<option value="9">Halda Fisheries Ltd</option>
					<option value="10">Polyex Laminate Ltd</option>
					<option value="18">AZNEO Limited</option>
                    </select>
				</td>
				</tr>
			
			<?php }

			?>	
			
			<?php  if ($companyShow=='y') {?>
				<tr>
					<td width="30">Company&nbsp;Name</td>
					<td><select name="company" id="company_id">
					<option  value=""><?php echo '---SELECT---';?></option>
					<?php foreach ($company_list->result() as $row) { ?>
					<option  value="<?php echo $row->iId; ?>"><?php echo $row->vCompany; ?></option>
					<?php }?></select></td>
				</tr>
			<?php } ?>
			
			
			
			
			<?php if ($locShow =='y') { ?>	
				<tr>
				<td>Location&nbsp;Name&nbsp;<span style="color:red;">*</span></td>
				<td>
                <select name="loc"> 
					<option value="1">Chittagong</option>
					<option value="2">Dhaka</option>
					<option value="3">Mohakhali</option>		
                </select>
				</td>
				</tr>
			
			<?php } ?>
			

				
			<?php  if ($complete_status=='y'){?>


				<tr>
					<td width="30">Complete Status:&nbsp;</td>
					<td>
						<select name="complete_status">
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</td>			
				</tr>
			<?php  } ?>	
			
			
			<tr>
				<td></td>
				<td colspan="4">
					<input type="radio" name="reportType" value="1" checked />HTML
					<input type="radio" name="reportType" value="3"/>XLS
				</td>
			</tr>
			
				<tr>
					<td>&nbsp;</td>
					<td colspan="4"><input type="submit" value="Ok"/></td>
				</tr>
			</table>
		</div>
	</form>
</div>
</html>