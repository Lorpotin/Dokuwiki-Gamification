<?php
$jsonKeys = array("date", "ip", "type", "id", "user", "sum", "extra");
$splittedLines = array();
$valuesToKeys = array();
$outPutArray = array();


foreach(file('http://lorpotin.website/Projects/dokuwiki/_dokuwiki.changes') as $line) {
   $splittedLines = preg_split("/[\t]/", $line);
   $valuesToKeys = array_combine($jsonKeys, $splittedLines);
   array_push($outPutArray, $valuesToKeys);
}

print_r($outPutArray);

$fp = fopen("data.json", 'w');
fwrite($fp, json_encode($outPutArray));
fclose($fp);

$db = new PDO('sqlite:userdb.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


function calculatePoints($outPutArray)
{
	$pointsArrayKeys = array("user", "points");
	$pointsArray = array();
	for($i = 0; $i < count($outPutArray); $i++)
	{

	}
	//return $pointsArray;
}

function calculateAchievements($outPutArray)
{
	//Do some magic to generate achievements
}


?>