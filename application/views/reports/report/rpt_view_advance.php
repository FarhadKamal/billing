<?php

    function convert_number($number) 

	{ 
    
	 if($number<0) {
	 $number=$number*(-1);
	 return "Employee have to return ".$number; }
	
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
</head>
<body> 
<?php } elseif($options == '3') {
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Salary.xls;");
header("Content-Type: application/ms-excel");
header("Pragma: no-cache");
header("Expires: 0");
}?>

	<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>View Advance</b></td></tr> 
	</table>
<?php 
if($results->result()){

foreach ($results->result() as $mydata):  

$netpay=$mydata->amount;
$loc="";
			if($mydata->loc==1)$loc="Chittagong Head Office";
			else if($mydata->loc==2) $loc="Dhaka Office";
			else if($mydata->loc==3) $loc="Mohakhali Office";
endforeach; 
?>
	<table border="1"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >

		
		<tr>
				<td  align="left" ><b>Advance&nbsp;ID:&nbsp;</b>&nbsp;<b><?php echo $mydata->id ?></b>&nbsp;&nbsp;
				<b>SAP&nbsp;ID:&nbsp;</b>&nbsp;<b><?php echo $mydata->sap_id ?></b></td>
				<td  align="left" ><b>Location:&nbsp;</b></td>
				<td  align="left" colspan=3><b><?php echo  $loc ?></b></td>
		
		<tr>
				<td  align="left" ><b>Employee&nbsp;ID:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->req_by ?></b></td>
	
				<td  align="left" ><b>Employee&nbsp;Name:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->empName ?></b></td>
		</tr>
		
		
		
		<tr>
				<td  align="left" ><b>Advance&nbsp;Date:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->advance_date ?></b></td>
	
				<td  align="left" ><b>Company&nbsp;Name:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->vCompany ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Description:&nbsp;</b></td>
				<td  align="left" colspan =3><b><?php echo $mydata->advance_description ?></b></td>
		</tr>

		

		<tr>
				<td  align="left" ><b>Payment&nbsp;Type:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->advance_type ?></b></td>
	
			
				<td  align="left" colspan=2><b>Amount:&nbsp;<?php echo $mydata->amount ?></b></td>
		</tr>
		<?php if($mydata->advance_type=='Cheque'){ ?>
		<tr>
				<td  align="left" ><b>Cheque&nbsp;Name:&nbsp;</b></td>
				<td  align="left" colspan=3><b><?php echo $mydata->cheque_name ?></b></td>
		</tr>
			<?php } ?>
		<tr>
				<td  align="left" ><b>Net&nbsp;Pay:&nbsp;</b></td>
				<td  align="left"><b><?php echo $netpay ?></b></td>
	
				<td  align="left" colspan=2><b><?php echo convert_number($netpay); ?>&nbsp;taka&nbsp;only</b></td>
		</tr>
	</table>
	
                                                                                                                          					

	<?php }





?>
<br/>
<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Advance Details</b></td></tr> 
	</table>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>SL</b></td>
			<td align="Center"><b>Details</b></td>
			<td align="Center"><b>Amount&nbsp;BDT</b></td>
			<td align="Center"><b>Documents</b></td>		
		</tr> 
	<?php 
	echo $table3;
	?>

	</table>
	
<?php 
if($results2->result()){



?>
<br/><br/>
<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Action Steps</b></td></tr> 
	</table>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>sl</b></td>
			<td align="Center"><b>user</b></td>
			<td align="Center"><b>action</b></td>
			<td align="Center"><b>comment</b></td>
			<td align="Center"><b>date</b></td>
		</tr> 
	<?php 
	$sl=0;

	foreach ($results2->result() as $mydata): 
	$sl=$sl+1;
	?>	

		<tr>
		<td align="center"><b><?php echo $sl ?></b></td>
		<td align="center"><b><?php echo $mydata->displayname ?></b></td>
		<td align="center"><b><?php echo $mydata->str_action ?></b></td>
		<td align="center"><b><?php echo $mydata->remarks ?></b></td>
		<td align="center"><b><?php echo $mydata->action_date ?></b></td>
		</tr>	
	<?php  endforeach; ?>

	</table>
	

	<?php }
	
	
	if($results3->result()){



?>
<br/><br/>
<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Account Posting</b></td></tr> 
	</table>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>sl</b></td>
			<td align="Center"><b>Account&nbsp;Head</b></td>
			<td align="Center"><b>Vendor</b></td>
			<td align="Center"><b>Remarks</b></td>
			<td align="Center"><b>Amount</b></td>
		</tr> 
	<?php 
	$sl=0;

	foreach ($results3->result() as $mydata): 
	$sl=$sl+1;
	?>	

		<tr>
		<td align="center"><b><?php echo $sl ?></b></td>
		<td align="center"><b><?php echo $mydata->account_head ?></b></td>
		<td align="center"><b><?php echo $mydata->vendor ?></b></td>
		<td align="center"><b><?php echo $mydata->remarks ?></b></td>
		<td align="center"><b><?php echo $mydata->amount ?></b></td>
		</tr>	
	<?php  endforeach; ?>

	</table>
	

	<?php }




	if($options == '2'){?>		
	</body>
</html>
<?php }?>