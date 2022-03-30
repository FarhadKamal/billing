    // Impede Seleção
    document.onselectstart = function() { return false; }
    
    // Menu Lateral
    if (document.getElementById){
    document.write('<style type="text/css">\n')
    document.write('.options{display: none;}\n')
    document.write('</style>\n')
    }
    function SwitchMenu(obj){
    	if(document.getElementById){
    	var el = document.getElementById(obj);
    	var ar = document.getElementById("masterdiv").getElementsByTagName("span");
    		if(el.style.display != "block"){
    			for (var i=0; i<ar.length; i++){
    				if (ar[i].className=="options")
    				ar[i].style.display = "none";
    			}
    			el.style.display = "block";
    		}else{
    			el.style.display = "none";
    		}
    	}
    }
