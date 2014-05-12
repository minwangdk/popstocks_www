<?php
session_start();
if(isset($_SESSION['loginID']))
   echo "Your USERID is " . $_SESSION['loginID'] . "</br> 
   You are logged in as ". $_SESSION['loginName'];
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Gangnam Style</title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="../pop_page.css">
	<script src="../pop_page.js"></script>
	
	
	<! rickshaw>
	

	<link type="text/css" rel="stylesheet" href="chartfiles/graph.css">
	<link type="text/css" rel="stylesheet" href="chartfiles/detail.css">
	<link type="text/css" rel="stylesheet" href="chartfiles/legend.css">
	<link type="text/css" rel="stylesheet" href="chartfiles/extensions.css">

	<script src="chartfiles/d3.v3.js"></script>
	<style type="text/css"></style>
	<script src="chartfiles/rickshaw.js"></script>

    
    
    
</head>
<body>
    
	

<h1 style="text-align: center">Gangnam Style</h1>

<!-- Video or picture -->
<div style="width: 560px; height: 315px; margin: 0 auto;">
<iframe width="560" height="315" src="//www.youtube.com/embed/9bZkp7q19f0" frameborder="0"></iframe>
</div>

<!-- <div class=vidshot>
	<img src="images/pop_pic.jpg" alt="Thumbnail of Gangnam Style"/>
</div>
-->


<!-- Rickshaw Graph -->
<div id="content">
	<div id="chart">
	</div>
</div>

<script>
//popid for gangnam style
var popid = 1;

var tv = 250;

// instantiate our graph!
var graph = new Rickshaw.Graph( {
	element: document.getElementById("chart"),
	width: 450,
	height: 250,
	renderer: 'line',
	series: new Rickshaw.Series.FixedDuration([{ 
		name: 'one', color: 'gold' 
		}], undefined, {
		timeInterval: tv,
		maxDataPoints: 100,
		timeBase: new Date().getTime() / 1000
	}) 
} );

for (i=0; i<80; i++){
	data = {
		one: Math.random() * 50
	};
	graph.series.addData(data);
}

graph.render();
console.log(graph.series, "logged!");



// add some data every so often
var iv = setInterval( function() {
//Get stockprice on interval with ajax	
	function getStockprice() {
		  if (window.XMLHttpRequest) {
		    // code for IE7+, Firefox, Chrome, Opera, Safari
		    xmlhttp=new XMLHttpRequest();
		  } else { // code for IE6, IE5
		    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  
		  xmlhttp.onreadystatechange=function() {
			  if (xmlhttp.readyState===4 && xmlhttp.status===200) {
			   //update graph series with new data
		       var data = {one: +xmlhttp.responseText};
			   graph.series.addData(data);
			   graph.render();
			   document.getElementById("stockprice").innerHTML=xmlhttp.responseText;
			  }
		  }
		  xmlhttp.open("GET","../../getstockprice.php?p="+popid,true);
		  xmlhttp.send();
	}
	getStockprice();
}, tv );


</script>

<p>Stockprice is: <span id="stockprice"></span></br></p>

<script>
function order(buysell)
{

var xmlhttp;
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
    document.getElementById("buysell_echo").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","../../sql_buysell.php?"+buysell,true);
xmlhttp.send();
}
</script>


<!-- Buttons and values. value = pop id (gangnam style id=1) -->
<div class=buttons>
	<form action="">
	<button id="buy_button" type="button" onclick="order('buy=1')">Buy</button>
	<button id="sell_button" type="button" onclick="order('sell=1')">Sell</button>
	</form>
</div>


<div id="buysell_echo">	
</div>




	
</body>
</html>
