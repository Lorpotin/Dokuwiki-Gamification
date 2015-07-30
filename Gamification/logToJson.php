<?php

$jsonKeys = array("date", "ip", "type", "id", "user", "sum", "extra");
$splittedLines = array();
$valuesToKeys = array();
$outPutArray = array();



foreach(file('http://lorpotin.website/Projects/dokuwiki/_dokuwiki.changes') as $line) {
   $splittedLines = preg_split("/[\t]/", $line);
   //print_r($splittedLines);
   $valuesToKeys = array_combine($jsonKeys, $splittedLines);
   //print_r($valuesToKeys);
   array_push($outPutArray, $valuesToKeys);
   //print_r($outPutArray);
}

print_r($outPutArray);

calculatePoints($outPutArray);

//Testataan nyt vain pisteiden laskua
/*
$fp = fopen("data.json", 'w');
fwrite($fp, json_encode($outPutArray));
fclose($fp);

$db = new PDO('sqlite:userdb.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);*/


function calculatePoints($outPutArray)
{

	//Juu elikkä, neljä arrayta. Yks joka toimii assosiaationa key => value parien muodostamiseen (pointsArrayKeys).
	var_dump("testi2");
	$pointsArrayKeys = array("user", "points", "type");
	$pointsArray = array();
	$resultArray = array();
	$valuesToKeys = array();

	$points = "";
	$names = "";
	$type = "";

	foreach($outPutArray as $value)
	{
		if($value["user"] == $value["user"] && $value["type"] == "C")
		{
			$points += 2;
			array_push($pointsArray, $value["user"], $points, $value["type"]);
			$valuesToKeys = array_combine($pointsArrayKeys, $pointsArray);
			array_push($resultArray, $valuesToKeys);
			$pointsArray = array();
			$valuesToKeys = array();
			$points = 0;
			
		}

		if($value["user"] == $value["user"] && $value["type"] == "E")
		{
			$points += 1;
			array_push($pointsArray, $value["user"], $points, $value["type"]);
			$valuesToKeys = array_combine($pointsArrayKeys, $pointsArray);
			array_push($resultArray, $valuesToKeys);
			$pointsArray = array();
			$valuesToKeys = array();
			$points = 0;
		}
		
		
		/*if($sub["type"] == "C")
		{
			$points += 1;
		}
		if($sub["type"] == "E")
		{
			$points += 1;
		}*/
	}
	$finalArray = array();
	foreach($resultArray as $value)
	{
		$userid = $value['user'];
		if(isset($finalArray[$userid]))
		    $index = ((count($finalArray[$userid]) - 1)) +1;
		else
		    $index = 1;

		$finalArray[$userid]['user'] = $userid;
		$finalArray[$userid]['points'] += $value['points'];
		$finalArray[$userid]['type' . $index] = $value['type'];        
		
	}
	$finalArray = array_values($finalArray);
	var_dump($finalArray);

}

function calculateAchievements($outPutArray)
{
	//Do some magic to generate achievements
}


?>