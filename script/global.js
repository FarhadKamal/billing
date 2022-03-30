$(document).ready(function() {

  $('#empID').change(function() { //Change event on account Head
	
    // get the value from the Category field                              
    var empid = $('#empID').val();  
    $.post('http://192.168.1.226/salary/index.php/incrementinf/incrementinf/getEmployeeInf',
      { 'EmpId':empid},

     function(result) {
            
      if (result) {
          $('#employeeinf').html(result);
        }
		}
    );
  });  
  
   $('#newBasic').change(function() { //Change event on account Head
	
    // get the value from the Category field          
	var empid = $('#empID').val(); 	
    var empbasic = $('#newBasic').val();  
    $.post('http://192.168.1.226/salary/index.php/incrementinf/incrementinf/getEmployeeBasic',
      { 'EmpId':empid, 'EmpBasic':empbasic},

     function(result) {
            
      if (result) {
          $('#employeebasic').html(result);
        }
		}
    );
  }); 
  
  $('#accHead').change(function() { //Change event on account Head
	
    // get the value from the Category field                              
    var memid = $('#memid').val();
	var acchead = $('#accHead').val();
    $.post('http://localhost/SECSL/index.php/moneyreceipt/moneyreceipt/listLoanbymemid',
      { 'MemId':memid,'AccHead':acchead },

     function(result) {
            
      if (result) {
          $('#loanreq').html(result);
        }
		}
    );
  });  
  
  $('#MemId').change(function() { //Change event on Member Id
	
    // get the value from the Category field                              
    var memid = $('#MemId').val();
    $.post('http://localhost/SECSL/index.php/loan/loaninvoice/listLoanbymemid',
      { 'MemId':memid},

     function(result) {
            
      if (result) {
          $('#loanreq').html(result);
        }
		}
    );
  });
  
  $('#loanreq').change(function() { //Change event on Loan Req Id
	
    // get the value from the Category field                              
    var LoanReqId = $('#LoanReqId').val();
    $.post('http://localhost/SECSL/index.php/loan/loaninvoice/LoanPaymentAmount',
      { 'ReqId':LoanReqId},

     function(result) {
            
		if (result) {
          $('#loanpaybleamt').html(result);
        }
	}
    );
  });
  
  $('#MemIdForReport').change(function() { //Change event on Member Id
	
    // get the value from the Category field                              
    var memid = $('#MemIdForReport').val();
    $.post('http://localhost/SECSL/index.php/reports/reports/listLoanbymemid',
      { 'MemId':memid},

     function(result) {
            
      if (result) {
          $('#loanreq').html(result);
        }
		}
    );
  });
}
);
