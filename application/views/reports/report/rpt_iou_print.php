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
<?php if($options == '2'){?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Report</title>
<script>
window.print();
</script>
</head>
<body> 
<?php } elseif($options == '3') {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Salary.xls;");
header("Content-Type: application/ms-excel");
header("Pragma: no-cache");
header("Expires: 0");
}?>

<?php 
if($results->result()){

foreach ($results->result() as $mydata):  

$netpay=$mydata->amount;

endforeach; 
?>
	<table border="0"  CELLPADDING="0" CELLSPACING="0" width="30%"  valign="top" >
		<tr>

				<td>Print Date:&nbsp;<?php echo date('l jS \of F Y h:i:s A'); ?>
				<br/>
				Company:&nbsp;<?php echo $mydata->vCompany; ?>
				<br/>
				Employee&nbsp;ID:&nbsp;<?php echo $mydata->req_by; ?>       
				<br/>
				Employee:&nbsp;<?php echo $mydata->vEmpName; ?>       
				<br/>
				Designation:&nbsp;<?php echo $mydata->vDesignation; ?>
				<br/>
				Department:&nbsp;<?php echo $mydata->vDepartment; ?>
				<br/> I received tk.&nbsp;<b><?php echo $netpay ?></b><b>&nbsp;(<?php echo convert_number($netpay); ?>&nbsp;taka&nbsp;only) on account of&nbsp;<?php echo $mydata->purpose ?></b></td>
		</tr>
	</table>
	<table border="0" width="30%">
		<tr>
			<td height="20">
			</td>
		</tr>
		<tr>
			<td >
				<p align="center" ><?php echo '____________________';?></p>
			</td>
		</tr>
		<tr>
			<td >
				<p align="center" ><?php echo 'Signature';?></p>
			</td>
		</tr>
	</table>



                                                                                                                                  					

	<?php }





	if($options == '2'){?>		
	</body>
</html>
<?php }?>