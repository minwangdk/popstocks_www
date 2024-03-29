<?
session_start();  
if(isset($_SESSION['loginID']))
   echo "Your USERID is " . $_SESSION['loginID'] . "</br> 
   You are logged in as ". $_SESSION['loginName'];

?>


<!doctype html>
<html>
<head>
    
    <title>Popstocks</title>
	<link rel="shortcut icon" href="favicon.ico"/>
	
    <!-- rickshaw  -->
    <link type="text/css" rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css">
	<link type="text/css" rel="stylesheet" href="src/css/graph.css">
	<link type="text/css" rel="stylesheet" href="src/css/detail.css">
	<link type="text/css" rel="stylesheet" href="src/css/legend.css">
	<link type="text/css" rel="stylesheet" href="css/extensions.css?v=2">

	<script src="vendor/d3.v3.js"></script>

	<script src="vendor/jquery.min.js"></script>
	<script>
		jQuery.noConflict();
	</script>
	<script src="vendor/jquery-ui.min.js"></script>
	<script src="js/extensions.js"></script>
	
	<script src="src/js/Rickshaw.js"></script>
	<script src="src/js/Rickshaw.Class.js"></script>
	<script src="src/js/Rickshaw.Compat.ClassList.js"></script>
	<script src="src/js/Rickshaw.Graph.js"></script>
	<script src="src/js/Rickshaw.Graph.Renderer.js"></script>
	<script src="src/js/Rickshaw.Graph.Renderer.Area.js"></script>
	<script src="src/js/Rickshaw.Graph.Renderer.Line.js"></script>
	<script src="src/js/Rickshaw.Graph.Renderer.Bar.js"></script>
	<script src="src/js/Rickshaw.Graph.Renderer.ScatterPlot.js"></script>
	<script src="src/js/Rickshaw.Graph.Renderer.Stack.js"></script>
	<script src="src/js/Rickshaw.Graph.RangeSlider.js"></script>
	<script src="src/js/Rickshaw.Graph.RangeSlider.Preview.js"></script>
	<script src="src/js/Rickshaw.Graph.HoverDetail.js"></script>
	<script src="src/js/Rickshaw.Graph.Annotate.js"></script>
	<script src="src/js/Rickshaw.Graph.Legend.js"></script>
	<script src="src/js/Rickshaw.Graph.Axis.Time.js"></script>
	<script src="src/js/Rickshaw.Graph.Behavior.Series.Toggle.js"></script>
	<script src="src/js/Rickshaw.Graph.Behavior.Series.Order.js"></script>
	<script src="src/js/Rickshaw.Graph.Behavior.Series.Highlight.js"></script>
	<script src="src/js/Rickshaw.Graph.Smoother.js"></script>
	<script src="src/js/Rickshaw.Fixtures.Time.js"></script>
	<script src="src/js/Rickshaw.Fixtures.Time.Local.js"></script>
	<script src="src/js/Rickshaw.Fixtures.Number.js"></script>
	<script src="src/js/Rickshaw.Fixtures.RandomData.js"></script>
	<script src="src/js/Rickshaw.Fixtures.Color.js"></script>
	<script src="src/js/Rickshaw.Color.Palette.js"></script>
	<script src="src/js/Rickshaw.Graph.Axis.Y.js"></script>
	


    
    
	<style type="text/css">
		div.thumbnail {text-align: center}
		#thumb {height: 100px}
	</style>
	

</head>
<body>
    
	
<!-- Frontpage with sitenav eg. Top10, Trending, Diving. New IPO's with new Pops that are auctioned. -->
<h1 style="text-align: center">Popcorn Stocks</h1>


<div class=thumbnail>	
<a href="pops/Gangnam_Style/pop_page.php"><img id="thumb" src="pops/Gangnam_Style/images/pop_pic.jpg" alt="Thumbnail of Gangnam Style" /></a>
</div>

<div class=register>
<a href="user_create_form.php"> New player </a>
</div> 

<div class=login>
<a href="user_login_form.php"> Login </a>
</div> 



<!--Rickshaw graph-->


<div id="content">

	<form id="side_panel">
		<h1>Top pops</h1>
		<section><div id="legend"></div></section>
		<section>
			<div id="renderer_form" class="toggler">
				<input type="radio" name="renderer" id="area" value="area" checked>
				<label for="area">area</label>
				<input type="radio" name="renderer" id="bar" value="bar">
				<label for="bar">bar</label>
				<input type="radio" name="renderer" id="line" value="line">
				<label for="line">line</label>
				<input type="radio" name="renderer" id="scatter" value="scatterplot">
				<label for="scatter">scatter</label>
			</div>
		</section>
		<section>
			<div id="offset_form">
				<label for="stack">
					<input type="radio" name="offset" id="stack" value="zero" checked>
					<span>stack</span>
				</label>
				<label for="stream">
					<input type="radio" name="offset" id="stream" value="wiggle">
					<span>stream</span>
				</label>
				<label for="pct">
					<input type="radio" name="offset" id="pct" value="expand">
					<span>pct</span>
				</label>
				<label for="value">
					<input type="radio" name="offset" id="value" value="value">
					<span>value</span>
				</label>
			</div>
			<div id="interpolation_form">
				<label for="cardinal">
					<input type="radio" name="interpolation" id="cardinal" value="cardinal" checked>
					<span>cardinal</span>
				</label>
				<label for="linear">
					<input type="radio" name="interpolation" id="linear" value="linear">
					<span>linear</span>
				</label>
				<label for="step">
					<input type="radio" name="interpolation" id="step" value="step-after">
					<span>step</span>
				</label>
			</div>
		</section>
		<section>
			<h6>Smoothing</h6>
			<div id="smoother"></div>
		</section>
		<section></section>
	</form>

	<div id="chart_container">
		<div id="chart"></div>
		<div id="timeline"></div>
		<div id="preview"></div>
	</div>

</div>

<script>

// set up our data series with 150 random data points

var seriesData = [ [], [], [], [], [], [], [], [], [] ];
var random = new Rickshaw.Fixtures.RandomData(150);

for (var i = 0; i < 150; i++) {
	random.addData(seriesData);
}

var palette = new Rickshaw.Color.Palette( { scheme: 'classic9' } );

// instantiate our graph!

var graph = new Rickshaw.Graph( {
	element: document.getElementById("chart"),
	width: 450,
	height: 250,
	renderer: 'area',
	stroke: true,
	preserve: true,
	series: [
		{
			color: palette.color(),
			data: seriesData[0],
			name: 'Gangnam Style'
		}, {
			color: palette.color(),
			data: seriesData[1],
			name: 'Justin Bieber'
		}, {
			color: palette.color(),
			data: seriesData[2],
			name: 'Barack Obama'
		}, {
			color: palette.color(),
			data: seriesData[3],
			name: 'Cats'
		}, {
			color: palette.color(),
			data: seriesData[4],
			name: 'Miley Cyrus'
		}, {
			color: palette.color(),
			data: seriesData[5],
			name: 'Friday Friday'
		}, {
			color: palette.color(),
			data: seriesData[6],
			name: 'Batman'
		}
	]
} );

graph.render();

var preview = new Rickshaw.Graph.RangeSlider( {
	graph: graph,
	element: document.getElementById('preview'),
} );

var hoverDetail = new Rickshaw.Graph.HoverDetail( {
	graph: graph,
	xFormatter: function(x) {
		return new Date(x * 1000).toString();
	}
} );

var annotator = new Rickshaw.Graph.Annotate( {
	graph: graph,
	element: document.getElementById('timeline')
} );

var legend = new Rickshaw.Graph.Legend( {
	graph: graph,
	element: document.getElementById('legend')

} );

var shelving = new Rickshaw.Graph.Behavior.Series.Toggle( {
	graph: graph,
	legend: legend
} );

var order = new Rickshaw.Graph.Behavior.Series.Order( {
	graph: graph,
	legend: legend
} );

var highlighter = new Rickshaw.Graph.Behavior.Series.Highlight( {
	graph: graph,
	legend: legend
} );

var smoother = new Rickshaw.Graph.Smoother( {
	graph: graph,
	element: document.querySelector('#smoother')
} );

var ticksTreatment = 'glow';

var xAxis = new Rickshaw.Graph.Axis.Time( {
	graph: graph,
	ticksTreatment: ticksTreatment,
	timeFixture: new Rickshaw.Fixtures.Time.Local()
} );

xAxis.render();

var yAxis = new Rickshaw.Graph.Axis.Y( {
	graph: graph,
	tickFormat: Rickshaw.Fixtures.Number.formatKMBT,
	ticksTreatment: ticksTreatment
} );

yAxis.render();


var controls = new RenderControls( {
	element: document.querySelector('form'),
	graph: graph
} );

// add some data every so often

var messages = [
	"Changed home page welcome message",
	"Minified JS and CSS",
	"Changed button color from blue to green",
	"Refactored SQL query to use indexed columns",
	"Added additional logging for debugging",
	"Fixed typo",
	"Rewrite conditional logic for clarity",
	"Added documentation for new methods"
];

setInterval( function() {
	random.removeData(seriesData);
	random.addData(seriesData);
	graph.update();

}, 2000 );

function addAnnotation(force) {
	if (messages.length > 0 && (force || Math.random() >= 0.95)) {
		annotator.add(seriesData[2][seriesData[2].length-1].x, messages.shift());
		annotator.update();
	}
}

addAnnotation(true);
setTimeout( function() { setInterval( addAnnotation, 6000 ) }, 6000 );

var previewXAxis = new Rickshaw.Graph.Axis.Time({
	graph: preview.previews[0],
	timeFixture: new Rickshaw.Fixtures.Time.Local(),
	ticksTreatment: ticksTreatment
});

previewXAxis.render();

</script>






	
</body>
</html>