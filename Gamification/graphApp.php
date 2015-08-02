<?php namespace gchart; 
	  require_once(__DIR__ . "/gChartPhp/gchart/gChartInit.php");
	?>

<?php

if(isset($_GET['getaction']))
{
	switch ($_GET['getaction'])
	{
		case 'getWikiActivity':
			GetWikiActivity($_GET['timePeriod']);
			break;

		default:
			Error();

		break;
	}
}



function GetWikiActivity($timePeriod)
{
	//Get all timestamps from dokuwiki, and make a graph around one/three/twelve months of wiki activity
	//Timestamps are available in the custom json file
	$timestampArray = array();
	$monthArray = array();
	$threeMonthArray = array();
	$twelveMonthArray = array();
	//print_r($timePeriod);

	$jsonString = file_get_contents("http://lorpotin.website/Projects/dokuwiki/data.json");
	$parsedJson = json_decode($jsonString, true);
	foreach ($parsedJson as $key => $value) 
	{
		array_push($timestampArray, $value['date']);
	}
	//Get current time
	$time = time();
	
	//Go through all timestamps in json file. Check difference depending on time wanted
	//If smaller than 2592000 seconds, less than one month!
	//If smaller than X seconds, less than 3 months!
	//If smaller than X seconds, less than a year!
	for($i = 0; $i < count($timestampArray); $i++)
	{
	    if($timePeriod == "one" && ($time - $timestampArray[$i] < 2592000)) 
	    {

	        array_push($monthArray, $timestampArray[$i]);
	        //print_r($monthArray);
	    }
	    else if($timePeriod == "two" && ($time - $timestampArray[$i] < 7776000))
	    {
	    	array_push($threeMonthArray, $timestampArray[$i]);
	    	//print_r($threeMonthArray);
	    }
	    else if($timePeriod == "three" && ($time - $timestampArray[$i] < 31104000))
	    {
	    	array_push($twelveMonthArray, $timestampArray[$i]);
	    	//print_r($twelveMonthArray);
	    }
	}

	if($timePeriod == "one")
	{
		$lineChart = new gLineChart(500,300);
		$lineChart->addDataSet(array(count($monthArray), count($monthArray)));
		$lineChart->setLegend(array("posts"));
		$lineChart->setColors(array("ff3344"));
		$lineChart->setVisibleAxes(array('x','y'));
		$lineChart->setDataRange(0, count($twelveMonthArray) + 10);
		$lineChart->addAxisRange(0, 1, 30, 1);
		$lineChart->addAxisRange(1, 0, count($monthArray));
		$lineChart->setGridLines(33,10);
		//Muutetaan stringi sellaiseksi, että selain ymmärtää sitä -> muuttaa &(ampersand)-merkit -> &amp;
	    echo htmlspecialchars_decode($lineChart->getUrl());      
	}
	else if($timePeriod == "two")
	{
		$lineChart = new gLineChart(500,300);
		$lineChart->addDataSet(array(count($threeMonthArray), count($threeMonthArray)));
		$lineChart->setLegend(array("posts"));
		$lineChart->setColors(array("ff3344"));
		$lineChart->setVisibleAxes(array('x','y'));
		$lineChart->setDataRange(0, count($twelveMonthArray) + 10);
		$lineChart->addAxisRange(0, 1, 3, 1);
		$lineChart->addAxisRange(1, 0, count($threeMonthArray));
		$lineChart->setGridLines(33,10);
		//Muutetaan stringi sellaiseksi, että selain ymmärtää sitä -> muuttaa &(ampersand)-merkit -> &amp;
	    echo htmlspecialchars_decode($lineChart->getUrl());      
	}
	else if($timePeriod == "three")
	{
		$lineChart = new gLineChart(500,300);
		$lineChart->addDataSet(array(count($twelveMonthArray), count($twelveMonthArray)));
		$lineChart->setLegend(array("posts"));
		$lineChart->setColors(array("ff3344"));
		$lineChart->setVisibleAxes(array('x','y'));
		$lineChart->setDataRange(0, count($twelveMonthArray) + 10);
		$lineChart->addAxisRange(0, 1, 12, 1);
		$lineChart->addAxisRange(1, 0, count($twelveMonthArray));
		$lineChart->setGridLines(33,10);
		//Muutetaan stringi sellaiseksi, että selain ymmärtää sitä -> muuttaa &(ampersand)-merkit -> &amp;
	    echo htmlspecialchars_decode($lineChart->getUrl());      
	}
    
}

?>