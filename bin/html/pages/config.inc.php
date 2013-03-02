<?php
include_once "libs/Highchart.php";
include_once "../config.inc.php";

//Get data from db
	$result = $edp_db->query("SELECT vendor FROM models order by vendor");
	foreach($result as $row) {
		if ($row[vendor] != "$last") { 
			echo "$row[vendor] <br>";
		}
		$last = "$row[vendor]";
	}



	



						
$chart = new Highchart();

$chart->chart->renderTo = "container";
$chart->chart->plotBackgroundColor = null;
$chart->chart->plotBorderWidth = null;
$chart->chart->plotShadow = false;
$chart->title->text = "Browser market shares at a specific website, 2010";

$chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %'; }");

$chart->plotOptions->pie->allowPointSelect = 1;
$chart->plotOptions->pie->cursor = "pointer";
$chart->plotOptions->pie->dataLabels->enabled = false;
$chart->plotOptions->pie->showInLegend = 1;

$chart->series[] = array('type' => "pie",
                         'name' => "Browser share",
                         'data' => array(array("Firefox", 45),
                                         array("IE", 26.8),
                                         array('name' => 'Chrome',
                                               'y' => 12.8,
                                               'sliced' => true,
                                               'selected' => true),
                                         array("Safari", 8.5),
                                         array("Opera", 6.2),
                                         array("Others", 0.7)));
$test = $chart->series[0][data];
echo "<pre>";
//var_dump($test);
echo "</pre>";                                        
?>

<html>
  <head>
    <title>Pie with legend</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php
      foreach ($chart->getScripts() as $script) {
         echo '<script type="text/javascript" src="' . $script . '"></script>';
      }
    ?>
  </head>
  <body>
    <div id="container"></div>
    <script type="text/javascript">
    <?php
      echo $chart->render("chart1");
    ?>
    </script>
  </body>
</html>