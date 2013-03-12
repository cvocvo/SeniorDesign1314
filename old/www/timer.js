
var SESSION_TIME = 60*60*2;

function initTimer()
{
	update();
	
	var timeID = setTimeout("initTimer()", 60000);
}


function update()
{
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	xmlhttp.onreadystatechange=function()
	{
  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
    	{
    		var response = xmlhttp.responseText;
			if(response != null)
			{
				var stats = JSON.parse(response);
				
				updateClock(stats['lastsession']);
				updateMachines(stats['machines']);
				updateRadios(stats['radios']);
				updatePorts(stats['ports']);
			}
    	}
  	}	
	xmlhttp.open("GET","userstats.php",true);
	xmlhttp.send();
}

function updatePorts(ports)
{
	var text = "&nbsp;|&nbsp;Client Port: " + ports['cport'] + " Attack Port: " + ports['aport'];
	var inner = document.getElementById("clock").innerHTML;
	document.getElementById("clock").innerHTML = inner + text;
}

function updateRadios(radios)
{
	var text = "";

	for(x in radios)
	{
		text = text + x + ": " + radios[x] + "&nbsp;";	
	}
	var inner = document.getElementById("clock").innerHTML;	
	document.getElementById("clock").innerHTML = inner + "&nbsp;|&nbsp;" + text;
}

function updateMachines(machines)
{
	if(machines['attack'])
	{
		var img = document.getElementById("attack_img");
		img.setAttribute("src", "images/machine_on.png");
	}
	else
	{
		var img = document.getElementById("attack_img");
		img.setAttribute("src", "images/machine_off.png");
	}
	if(machines['client'])
	{
		var img = document.getElementById("client_img");
		img.setAttribute("src", "images/machine_on.png");
	}
	else
	{
		var img = document.getElementById("client_img");
		img.setAttribute("src", "images/machine_off.png");
	}
}

function updateClock(oldDate)
{
	

	var curDate = new Date().getTime();
	curDate = Math.floor(curDate/1000);
	var dif = curDate - oldDate;
	dif = SESSION_TIME - dif;	
		
	if(dif < 300 && dif >= 0)
	{
		var renew = confirm("Less Than 5 Minutes Until Shutdown. Renew Session?");
		
		if(renew)
		{
			if (window.XMLHttpRequest)
  			{// code for IE7+, Firefox, Chrome, Opera, Safari
  				xmlhttp=new XMLHttpRequest();
  			}
			else
  			{// code for IE6, IE5
  				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  			}
			xmlhttp.onreadystatechange=function()
			{
  				if (xmlhttp.readyState==4 && xmlhttp.status==200)
    			{
    				var response = xmlhttp.responseText;
    			}
  			}	
			xmlhttp.open("GET","renewsession.php",true);
			xmlhttp.send();
			dif = SESSION_TIME;
		}
		
	}
	else if(dif < 0)
	{
		dif = 0;
	}
	var display = "" + dif;	
	document.getElementById("clock").innerHTML="&nbsp;" + display.toHHMM();
	
}


String.prototype.toHHMM = function () {
    sec_numb    = parseInt(this);
    var hours   = Math.floor(sec_numb / 3600);
    var minutes = Math.floor((sec_numb - (hours * 3600)) / 60);
    var seconds = sec_numb - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    var time    = hours+':'+minutes; //+':'+seconds;
    return time;
}

