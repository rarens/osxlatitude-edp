<?php
include_once "libs/Highchart.php";

include_once "../config.inc.php";

//Get data from db
$result = $edp_db->query("SELECT `vendor`, COUNT(*) AS `count` FROM `models` GROUP BY `vendor` ORDER BY `count` DESC");

$vendors = array();
$total = 0;
// Loop the result and add it to $vendors
foreach($result as $row) {
	$tmp = $row;
	unset($tmp[0], $tmp[1]);
	$vendors[] = $tmp;
	$total += $row['count'];
}
// Calculate the percentage per vendor
foreach($vendors as $i => $vendor) {
	if($total != 0) {
		$c = round(($vendor['count']/$total)*100, 1);
		$vendors[$i]['share'] = round($c, 0);
	} else {
		$vendors[$i]['share'] = 0;
	}
}


$chart = new Highchart();

$chart->chart->renderTo = "container";
$chart->chart->plotBackgroundColor = '#F4F4F4';
$chart->chart->plotBorderWidth = '0px';
$chart->chart->plotShadow = false;
$chart->title->text = "We currently have $total systems in EDP";

$chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';}");

$chart->plotOptions->pie->allowPointSelect = 1;
$chart->plotOptions->pie->cursor = "pointer";
$chart->plotOptions->pie->dataLabels->enabled = 1;
$chart->plotOptions->pie->dataLabels->color = "#000000";
$chart->plotOptions->pie->dataLabels->connectorColor = "#000000";

$chart->plotOptions->pie->dataLabels->formatter = new HighchartJsExpr("function() {
    return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %'; }");





// Make the array for the chart
$vendor_chart_data = array();
foreach($vendors as $vendor) {
	$vendor_chart_data[] = array(
		'name' => $vendor['vendor'],
		'y' => $vendor['share'],
		'sliced' => $vendor['vendor'] == "Dell" ? TRUE : FALSE,
	);
}
$vendor_chart = array(
	'type' => "pie",
	'name' => "Vendor share",
	'data' => $vendor_chart_data,
);


$chart->series[] = $vendor_chart;


      foreach ($chart->getScripts() as $script) {
         echo '<script type="text/javascript" src="' . $script . '"></script>';
      }
    ?>
<p><span class='graytitle'>Build from model database</span>
	<ul class="pageitem">
		<li class="textbox"><p>EDP's internal database contains 'best practice' schematics for 50+ systems - this makes it easy to choose the right configuration - however - you always have the option to adjust the schematics before doing a build.<br>
		<br>Doing a build means that EDP will copy a combination of kexts, dsdt and plists needed to boot your system.
		<br>To start a new build, you can choose the option 'Build from model database' in the menu on the left side.</p>
		</li>
	</ul>
</p>
	<br><br>

    <div id="container"></div>
    <script type="text/javascript">
    <?php
      echo $chart->render("chart1");
    ?>
    
 //Hack: Modify color of pie chart border   
 $(document).ready(function() {
$( "rect" ).each(function( index ) {
  if($(this).attr("fill")=="#FFFFFF") { 
	  $(this).attr("fill", "#F4F4F4");
  }
});
 });
 
 //Hack hide the watermark
 document.getElementById('watermark').style.display = 'none';
 
 
    </script>
