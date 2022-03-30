<?php

    function convert_number($number) 
{ 
    if (($number < 0) || ($number > 999999999)) 
    { 
    throw new Exception("Number is out of range");
    } 

    $Gn = floor($number / 100000);  /* Lak (giga) */ 
    $number -= $Gn * 100000; 
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;               /* Ones */ 

    $res = ""; 

    if ($Gn) 
    { 
        $res .= convert_number($Gn) . " Lakh"; 
    } 

    if ($kn) 
    { 
        $res .= (empty($res) ? "" : " ") .convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") .convert_number($Hn) . " Hundred"; 
    } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
<head>
<title><?php echo $page_title;?></title>
</head>
<body> 

	<div class="content"> 
		<div class="data">
			<table border="1"  CELLPADDING="4" CELLSPACING="0" width="50%" align="center" valign="top">
				<?php  ?>
				<tr>
				
				<td align="Center" colspan="4"><b><?php echo ' Conveyance Bill ';?></b></td>
				</tr>
				<tr align="Center">			
					<td align="Center" colspan="4"><b>Applied By</b></td>
				</tr>
				
				
				
				<?php
					foreach ($results->result() as $mydata): 
				?> 
				<tr>
					<td align="left" width="20%"><b><?php echo 'Employee&nbsp;Id&nbsp;:'; ?></b></td><td align="left"  width="20%"><?php echo $mydata->EmpId; ?></td>
					<td align="left"><b><?php echo 'Name&nbsp;:'; ?></b></td><td align="left"><?php echo $mydata->vEmpName; ?></td>
				</tr>
				<tr>	
					<td align="left"><b><?php echo 'Designation&nbsp;:'; ?></b></td><td align="left"><?php echo $mydata->Designation; ?></td>
					<td align="left"><b><?php echo 'Department&nbsp;:'; ?></b></td><td align="left"><?php echo $mydata->DeptName; ?></td>
				</tr>
				
				<tr align="Center">			
					<td align="Center" colspan="4"><b>Approved By</b></td>
				</tr>
				<tr align="Center">			
					<td align="left"><b><?php echo 'Name&nbsp;:'; ?></b></td><td align="left"><?php echo $mydata->dname; ?></td>
					<td align="left"><b><?php echo 'Department&nbsp;:'; ?></b></td><td align="left"><?php echo $mydata->ddes; ?></td>
				</tr>
				<tr align="Center">			
					<td align="left"><b><?php echo 'Name&nbsp;:'; ?></b></td><td align="left">Head of Finance</td>
					<td align="left"><b><?php echo 'Department&nbsp;:'; ?></b></td><td align="left">Head of Finance</td>
				</tr>
				
				
				<tr align="Center">
				
				<td align="Center" colspan="4"><b>Paticulars</b></td>
				</tr>
				<tr align="Center">				
					<td align="Center" colspan="4">
						<table  border="1">
							<tr>
								<td>Purpose:&nbsp;<?php echo $mydata->purpose ?></td>
								<td>From:&nbsp;<?php echo $mydata->vfrom ?></td><td>To:&nbsp;<?php echo $mydata->vto ?></td>
								<td>Transport Mode:&nbsp;<?php echo $mydata->trans_mode ?></td>
							</tr>
							<tr>
								<td>Amount:&nbsp;<?php echo $mydata->amount?>   </td><td ><?php echo convert_number($mydata->amount)." Only"; ?></td>
								<td >Updown:&nbsp;<?php echo $mydata->updown; ?></td>
								<td align="Center"><b>Applied Date:</b>&nbsp;<?php echo $mydata->created_date ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr align="Center">
					<td align="Center"><b>Company:</b>&nbsp;<?php echo $mydata->vCompany ?></td>
					<td align="Center"><b>Business Area:</b>&nbsp;<?php echo $mydata->area ?></td>
					<td align="Center"><b>Cost Center:</b>&nbsp;<?php echo $mydata->vCostCentre ?></td>
					<td align="Center"><b>Internal Order:</b>&nbsp;<?php echo $mydata->internal_order ?></td>
					
				</tr>
				
				<?php endforeach; ?>
			</table>
		</div>
	</div>
	
	</body>
</html>
