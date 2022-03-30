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
	<tr><td align="Center" ><b>Bill Processing System</b></td></tr> 
	</table>
<?php 
if($results->result()){

foreach ($results->result() as $mydata):  


$netpay=$mydata->amount-$mydata->advance-$mydata->vds-$mydata->tds-$mydata->tot_auth_deduction-$mydata->general_deduction;

$loc="";
			if($mydata->loc==1)$loc="Chittagong Head Office";
			else if($mydata->loc==2) $loc="Dhaka Office";
			else if($mydata->loc==3) $loc="Mohakhali Office";
endforeach; 
?>
	<table border="1"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >

		
		<tr>
				<td  align="left" ><b>Location:&nbsp;</b></td>
				<td  align="left" colspan=3><b><?php echo  $loc ?></b></td>
		
		
		<tr>
		<tr>
				<td  align="left" ><b>Billing&nbsp;ID:&nbsp;</b></td>
				<td  align="left" ><b><?php echo $mydata->id ?></b></td>
				<td  align="left" ><b>Sap&nbsp;ID:&nbsp;</b></td>
				<td  align="left" ><b><?php echo $mydata->sap_id ?></b></td>
		
		<tr>
				<td  align="left" ><b>Employee&nbsp;ID:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->created_by ?></b></td>
	
				<td  align="left" ><b>Employee&nbsp;Name:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->empName ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Employee&nbsp;Designation:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->vDesignation ?></b></td>
				<td  align="left" ><b>Employee&nbsp;Grade:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->vGrade ?></b></td>
		</tr>
		
		
		
		<tr>
				<td  align="left" ><b>Billing&nbsp;Date:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->bill_date ?></b></td>
	
				<td  align="left" ><b>Company&nbsp;Name:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->vCompany ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Description:&nbsp;</b></td>
				<td  align="left" colspan =3><b><?php echo $mydata->bill_description ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Vendor&nbsp;Code:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->vendor_code ?></b></td>
	
				<td  align="left" ><b>Vendor&nbsp;Name:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->vendor_name ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>PO&nbsp;No:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->po_no ?></b></td>
	
				<td  align="left" ><b>PO&nbsp;Date:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->po_date ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>GR&nbsp;No:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->gr_no ?></b></td>
	
				<td  align="left" ><b>GR&nbsp;Date:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->gr_date ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>IV&nbsp;No:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->iv_no ?></b></td>
	
				<td  align="left" ><b>IV&nbsp;Date:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->iv_date ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>TDS&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->tds ?></b></td>
	
				<td  align="left" ><b>VDS&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->vds ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>General&nbsp;Deduction&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->general_deduction ?></b></td>
	
				<td  align="left" ><b>General&nbsp;Deduction&nbsp;Note&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->general_deduction_note ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Deduction&nbsp;by&nbsp;Authority:&nbsp;</b></td>
				<td  align="left" colspan=3><b><?php echo $mydata->tot_auth_deduction ?></b></td>	
		</tr>
		<tr>
				<td  align="left" ><b>Asset&nbsp;No:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->asset_no ?></b></td>
				
				<td  align="left" ><b>Payment&nbsp;Type:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->payment_type ?></b></td>
		</tr>
		<?php if($mydata->payment_type=='Cheque'){  ?>
		<tr>
				<td  align="left" ><b>Cheque&nbsp;Name:&nbsp;</b></td>
				<td  align="left"  colspan =3><b><?php echo $mydata->suggested_cheque ?></b></td>
	
			
		</tr>
		<?php } ?>

		<tr>
				<td  align="left" ><b>Advance:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->advance ?></b></td>
	
				<td  align="left" ><b>Amount:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->amount ?></b></td>
		</tr>
		
		<tr>
				<td  align="left" ><b>Net&nbsp;Pay:&nbsp;</b></td>
				<td  align="left"><b><?php echo $netpay ?></b></td>
	
				<td  align="left" colspan=2><b><?php echo convert_number($netpay); ?>&nbsp;taka&nbsp;only</b></td>
		</tr>
	</table>
	<br/><br/>
	<table border="1"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
		<tr>
				<td  align="left" ><b>Supervised:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->superviseName ?></b></td>
				<td  align="left" ><b>Comment&nbsp;:</b></td>
				<td  align="left" width="40%"><b><?php echo $mydata->supervise_comment ?></b></td>
		</tr>
		<!--
		<tr>
				<td  align="left" ><b>Approved&nbsp;By:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->c ?></b></td>
				<td  align="left" ><b>Comment&nbsp;:</b></td>
				<td  align="left"><b><?php echo $mydata->auth_comment ?></b></td>
		</tr> -->
		<tr>
				<td  align="left" ><b>Approved&nbsp;By:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->d ?></b></td>
				<td  align="left" ><b>Comment:</b></td>
				<td  align="left"><b><?php echo $mydata->high_auth_comment ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Finance&nbsp;Head:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->g ?></b></td>
				<td  align="left" ><b>Comment:</b></td>
				<td  align="left"><b><?php echo $mydata->finance_head_comment ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>CEO:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->f ?></b></td>
				<td  align="left" ><b>Comment:</b></td>
				<td  align="left"><b><?php echo $mydata->ceo_comment ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Director:&nbsp;</b></td>
				<td  align="left"><b><?php if($mydata->super_authority_by!="")echo "Imran  Khan" ?></b></td>
				<td  align="left" ><b>Comment:</b></td>
				<td  align="left"><b><?php echo $mydata->super_auth_comment ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Audit:&nbsp;</b></td>
				<td  align="left"><b><?php echo $mydata->h ?></b></td>
				<td  align="left" ><b>Comment:</b></td>
				<td  align="left"><b><?php echo $mydata->audit_comment ?></b></td>
		</tr>
		<tr>
				<td  align="left" ><b>Account:&nbsp;</b></td>
				<td  align="left"><b>Account Department</b></td>
				<td  align="left" ><b>Comment:</b></td>
				<td  align="left"><b><?php echo $mydata->account_comment ?></b></td>
		</tr>
	</table>



                                                                                                                                  					

	<?php }


if($results2->result()){



?>
<br/>
<table border="0"  CELLPADDING="0" CELLSPACING="0" width="80%" align="center" valign="top" >
	<tr><td align="Center" ><b>Cost Allocation</b></td></tr> 
	</table>
	<table border="1"   CELLPADDING="0" CELLSPACING="0" width="80%" align="center" align="center" >	
			
		<tr>
			<td align="Center" width="1%"><b>SL</b></td>
			<td align="Center"><b>Company&nbsp;</b></td>
			<td align="Center"><b>Business&nbsp;Area&nbsp;</b></td>
			<td align="Center"><b>Account&nbsp;Head&nbsp;</b></td>
			<td align="Center"><b>Profit&nbsp;Center&nbsp;</b></td>
			<td align="Center"><b>Cost&nbsp;Centre&nbsp;</b></td>
			<td align="Center"><b>Internal&nbsp;Order&nbsp;</b></td>
			<td align="Center"><b>Amount&nbsp;BDT</b></td>	
			<td align="Center"><b>Remark</b></td>
		</tr> 
	<?php 
	$sl=0;

	foreach ($results2->result() as $mydata2): 
	$sl=$sl+1;
	?>	

		<tr>
		<td align="center"><b><?php echo $sl ?></b></td>
		<td align="center"><b><?php echo $mydata2->vCompany ?></b></td>
		<td align="center"><b><?php echo $mydata2->area ?></b></td>
		<td align="center"><b><?php echo $mydata2->profit_center ?></b></td>
		<td align="center"><b><?php echo $mydata2->account_head ?></b></td>
		<td align="center"><b><?php echo $mydata2->cost_id."&nbsp;##&nbsp;".$mydata2->cost_text ?></b></td>
		<td align="center"><b><?php echo $mydata2->Order."&nbsp;".$mydata2->Description ?></b></td>
		<td align="center"><b><?php echo $mydata2->divide_amount ?></b></td>
		<td align="center"><b><?php echo $mydata2->remark ?></b></td>
		</tr>	
	<?php  endforeach; ?>

	</table>
	

	<?php }



echo "<br/><div align='center'>".$documents."</div>";







?>
<br/>

<div align="center">
<a href="<?php echo base_url() ?>index.php/reports/reports/view_details_history/<?php echo $mydata->id ?>" target="about_blank2" class="view">view history</a>	<br/>
<a href="<?php echo base_url() ?>index.php/reports/reports/view_action_bill/<?php echo $mydata->id ?>" target="about_blank3" class="view">view step</a>
</div>

<?php








	if($options == '2'){?>		
	</body>
</html>
<?php }?>