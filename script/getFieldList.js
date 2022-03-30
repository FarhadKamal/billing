var xmlHttp;

//==================================================================//
//                                                                  //
//                                                                  //
//==================================================================//
	
	function showDATA(equip_id)
	{ 
		alert(equip_id);
		if (window.XMLHttpRequest)
		{
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlHttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject)
		{
			// code for IE6, IE5
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else
		{
			alert("Your browser does not support XMLHTTP!");
			return;
		}
		
		if(equip_id=="")	
		{
			alert(equip_id);
			return;
		}
		else
		{
			//alert(equip_id);
			//var url="Forms/equipment/getEquipmentIdList.php";
			//url=url+"?equipId="+equip_id;
			//alert(url);
			//xmlHttp.onreadystatechange=stateChanged;
			//xmlHttp.open("GET",url,true);	
			//xmlHttp.send(null);
			 $.post('http://localhost/SECSL/index.php/moneyreceipt/moneyreceipt/listLoanbymemid',
		      { 'schedule':schedule },

		     function(result) {
		            
		      if (result) {
		          $('#testdiv').html(result);
		        }
				}
		    );
		}
	}
	
	function stateChanged() 
	{ 
		if (xmlHttp.readyState==4)
		{
			//alert(xmlHttp.responseText);
			document.getElementById("eqipmentList").innerHTML=xmlHttp.responseText;
		}
	}