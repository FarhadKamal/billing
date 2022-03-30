<?php
/**
 * This file is essentially a stripped down version of /views/invoices/view.php
 * Any changes you make to that formatting, you may consider adding to this.
 */
 $v_Tax = 0;
$v_Others = 0;
 
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $page_title;?></title>
</head>
<body>
	<?php $header=0; $SL=1; foreach ($results->result() as $mydata):
?>
			<?php if ($header==0) {
			
				$totalBasic=0; $totalHA=0; $totalCA=0; $totalMA=0; $totalAllowance=0;
				$totalPF=0;$totalPFLoan=0;$totalGL=0;$totalLWP=0;$totalInTAX=0;
				$totalGross=0;$totalBankDeduction=0;$totalCashGross=0;$totalDeductionCashGross=0;
			?>
			<table border="1"  CELLPADDING="0" CELLSPACING="0" width="100%" align="center" valign="top" height="60%">
			<tr>
				<td align="center" colspan="4"><font size="4"><b><span><?php echo $company->vCompany;?></b></font></span></td>
			</tr>
			<tr>
				<td align="center" colspan="4"><font size="3"><span><?php echo 'Pay Slip - '.$salaryMonth;?></font></span></td>
			</tr>	
			<tr>
				<td align="left" width="15%"><font size="2"><b>Employee Id&nbsp;:</b></font></td>
				<td align="left" width="40%"><font size="2"><?php echo $mydata->vEmpId;?></font></td>
				<td align="left" ><font size="2"><b>Date&nbsp;of&nbsp;Joining&nbsp;:</b></font></td>
				<td align="left" width="30%"><font size="2"><?php echo '&nbsp;';?></font></td>
			<tr>
			<tr>
				<td align="left"><font size="2"><b>Name&nbsp;:</b></font></td>
				<td align="left"><font size="2"><?php echo $mydata->vEmpName;?></font></td>
				<td align="left"><font size="2"><b>Designation&nbsp;:</b></font></td>
				<td align="left"><font size="2"><?php echo $mydata->vDesignation;?></font></td>
			<tr>
			<tr><td align="left"><font size="2"><b>Grade&nbsp;:</b></font></td>
				<td align="left"><font size="2"><?php echo $mydata->vGrade;?></font></td>
				<td align="left"><font size="2"><b>Department&nbsp;:</b></font></td>
				<td align="left"><font size="2"><?php echo $mydata->vDepartment;?></font></td>
			</tr>
			<?php $header=1;} ?>
			<?php
				if ($mydata->dBasic ==0) $salary=$mydata->dsubTotal; else $salary=$mydata->dBasic;
				$totalBasic=$totalBasic+$salary;
				$totalHA=$totalHA+$mydata->dHA;
				$totalCA=$totalCA+$mydata->dCA;
				$totalMA=$totalMA+$mydata->dMA;
				$totalAllowance=$totalAllowance+$mydata->dAllowance;
				//Gross
				$totalGross=$totalGross+$salary+$mydata->dHA+$mydata->dCA+$mydata->dMA+$mydata->dAllowance;
				//Deduction
				$totalPF=$totalPF+$mydata->dPF;
				$totalPFLoan=$totalPFLoan+$mydata->dPFLoan;
				$totalGL=$totalGL+$mydata->dGLoan;
				$totalLWP=$totalLWP+$mydata->dLWP;
				
				//Gross
				$totalBankDeduction=$totalBankDeduction+$mydata->dPF+$mydata->dPFLoan+$mydata->dGLoan+$mydata->dLWP+$mydata->dTAX+$mydata->dOthers;
				$netBankAmount=$totalGross-$totalBankDeduction;
				//$netCashAmount=$totalGross-$totalDeductionGross;
				if ($mydata->vType =='bank') {
					$v_Tax = $mydata->dTAX;
					$v_Others = $mydata->dOthers;
				?>
			<tr><td colspan="2" align="center">
				<table border="1"  CELLPADDING="0" CELLSPACING="0" width="100%" height="50%">
					<tr><td><?php echo $SL; ?></td><td align="center"><font size="2"><?php echo 'BANK'; ?></font></td></tr>
					<tr><td><font size="2">Basic&nbsp;Pay</font></td><td align="right"><font size="2"><?php echo $salary; ?></font></td></tr>
					<tr><td><font size="2">House&nbsp;Rent&nbsp;Allownace</font></td><td align="right"><font size="2"><?php echo $mydata->dHA; ?></font></td></tr>
					<tr><td><font size="2">Conveyance&nbsp;Allowance</font></td><td align="right"><font size="2"><?php echo $mydata->dCA; ?></font></td></tr>
					<tr><td><font size="2">Medical&nbsp;Allowance</font></td><td align="right"><font size="2"><?php echo $mydata->dMA; ?></font></td></tr>
					<tr><td><font size="2">Manage./Exec.&nbsp;Allowance</font></td><td align="right"><font size="2"><?php echo $mydata->dAllowance; ?></font></td></tr>
					<tr><td><font size="2"><b>Gross&nbsp;Salary</b></font></td><td align="right"><b><font size="2"><?php echo $totalGross; ?></font></b></td></tr>
					
					<tr><td><font size="2">PF&nbsp;Contribution</font></td><td align="right"><font size="2"><?php echo $mydata->dPF; ?></font></td></tr>
					<tr><td><font size="2">PF&nbsp;Loan</font></td><td align="right"><font size="2"><?php echo $mydata->dPFLoan; ?></font></td></tr>
					<tr><td><font size="2">Generel&nbsp;Loan</font></td><td align="right"><font size="2"><?php echo $mydata->dGLoan; ?></font></td></tr>
					<tr><td><font size="2">LWP</font></td><td align="right"><font size="2"><?php echo $mydata->dLWP; ?></font></td></tr>
					<tr><td><font size="2">Income&nbsp;TAX</font></td><td align="right"><font size="2"><?php echo $mydata->dTAX; ?></font></td></tr>
					<tr><td><font size="2">Others&nbsp;</font></td><td align="right"><font size="2"><?php echo $mydata->dOthers; ?></font></td></tr>
					<tr><td><font size="2"><b>Total&nbsp;Deduction</font></b></td><td align="right"><font size="2"><b><?php echo $totalBankDeduction; ?></b></font></td></tr>
					<tr><td><font size="2"><b>Net&nbsp;Payable&nbsp;Amount</font></b></td><td align="right"><font size="2"><b><?php echo $netBankAmount; ?></b></font></td></tr>
				</table>
				</td>
				<?php } else if ($mydata->vType =='cash'){
				//Gross
				$totalCashGross=$salary+$mydata->dHA+$mydata->dCA+$mydata->dMA+$mydata->dAllowance;
				
				//Gross
				$totalCashDeduction=$mydata->dPF+$mydata->dPFLoan+$mydata->dGLoan+$mydata->dLWP;
				$totalDeduction=$totalBankDeduction;
				
				$netcashAmount=$totalCashGross-$totalCashDeduction;
				?>
				<td colspan="2" align="center"><table border="1"  CELLPADDING="0" CELLSPACING="0" width="100%" height="50%">
					<tr><td align="center"><font size="2"><?php echo 'CASH'; ?></font></td><td align="center"><font size="2"><?php echo 'TOTAL AMOUNT'; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo $salary; ?></font></td><td align="right"><font size="2"><?php echo $totalBasic; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo $mydata->dHA; ?></font></td><td align="right"><font size="2"><?php echo $totalHA; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo $mydata->dCA; ?></font></td><td align="right"><font size="2"><?php echo $totalCA; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo $mydata->dMA; ?></font></td><td align="right"><font size="2"><?php echo $totalMA; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo $mydata->dAllowance; ?></font></td><td align="right"><font size="2"><?php echo $totalAllowance; ?></font></td></tr>
					<tr><td align="right"><font size="2"><b><?php echo $totalCashGross; ?></font></b></td><td align="right"><font size="2"><b><?php echo $totalGross; ?></font></b></td></tr>
					
					<tr><td align="right"><font size="2"><?php echo $mydata->dPF; ?></font></td><td align="right"><font size="2"><?php echo $totalPF; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo $mydata->dPFLoan; ?></font></td><td align="right"><font size="2"><?php echo $totalPFLoan; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo $mydata->dGLoan; ?></font></td><td align="right"><font size="2"><?php echo $totalGL; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo $mydata->dLWP; ?></font></td><td align="right"><font size="2"><?php echo $totalLWP; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo '0.00'; ?></font></td><td align="right"><font size="2"><?php echo $v_Tax; ?></font></td></tr>
					<tr><td align="right"><font size="2"><?php echo '0.00'; ?></font></td><td align="right"><font size="2"><?php echo $v_Others; ?></font></td></tr>
					<tr><td align="right"><font size="2"><b><?php echo $totalCashDeduction; ?></font></b></td><td align="right"><font size="2"><b><?php echo $totalDeduction; ?><b></font></td></tr>
					<tr><td align="right"><font size="2"><b><?php echo $netcashAmount; ?></font></b></td><td align="right"><b><font size="2"><?php echo $totalGross-$totalDeduction; ?><b></font></td></tr>
					</table>
				</td></tr>
				
					<tr><td colspan="2" align="left" height="85" valign="bottom"><img src="<?php echo base_url(); ?>/images/manager.jpg" width="80" height="70"><?php echo '</br>Signature&nbsp;of&nbsp;Manager';?></td><td align="right" colspan="2" valign="bottom">Signature&nbsp;of&nbsp;Employee</td></tr>
					<tr><td height="12"></td></tr>
					</table>
				<?php $header=0;}?>
	<?php $SL= $SL+1; 
		endforeach; ?>
</body>
</html>