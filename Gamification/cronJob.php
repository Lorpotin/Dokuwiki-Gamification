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
fclose($fp);

$db = new PDO('sqlite:userdb.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);*/


function calculatePoints($outPutArray)
{

	//Juu elikkä, neljä arrayta. Yks joka toimii assosiaationa key => value parien muodostamiseen (pointsArrayKeys). Valuestokeys on väliaikainen, johon yhdistetään pointsarray pointsarraykeys
	//taulukon mukaiseen assosiaatioon. Tämä koko roska laitetaan vielä resultArray:hin, jossa lopullinen taulukko. Seuraavassa foreach silmukassa taulukkoa muokataan vielä lisää.
	//Tarkoituksena on saada taulukko jossa on yhden nimen alla pisteet ja muutoksien tyypit. Näin ollen saadusta taulukosta on yksinkertaista päivittää tietokantaa.
	// ["user"]=>
    //string(5) "lorpo"
    //["points"]=>
    //int(12)
    //["type1"]=>
    //string(1) "C"
    //^ Tuohon tyyliin.

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
		if($value["type"] == "E")
		{
			$finalArray[$userid]["etype"] += 1;
		}
		if($value["type"] == "C")
		{
			$finalArray[$userid]["ctype"] += 1;
		}
	}
	$finalArray = array_values($finalArray);
	var_dump($finalArray);

	updateDataBase($finalArray);

}


function updateDataBase($finalArray)
{
	//Haetaan tietokannasta jo löytyvät nimet ensin. Täten tiedetään onko käyttäjä rekisteröitynyt jo. Datan keruu alkaa vasta kun käyttäjä rekisteröitynyt.
	$db = new PDO('sqlite:userdb.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try
    {
      $query = "SELECT username FROM User";
      $result = $db->prepare($query);
      $result->execute();
      if($result)
      {
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
           $username = $row["username"];
           $existingDataArray[] = array("username" => $username);
        }
      }
      else
        echo "error";
    }
    catch(PDOException $e)
    {
      echo "Connection failed: " .$e->getMessage();
    }    

    //Seuraavaksi pitäs verrata finalArray ja existingDataArray. Jos finalArrayssa oleva nimi löytyy existingArraystä -> UPDATE. Jos ei löydy, ei tehä mitään.
    //Käyttäjä "alustetaan" tietokantaan rekisteröitymisen yhteydessä.


	$query = "INSERT INTO User (username, points) values ";

    $valuesArr = array();
    foreach($finalArray as $value){

        $username = $row['user'];
        $points = (int)$row['points'];

        $valuesArr[] = "('$username', '$points')";
    }

    $query .= implode(',', $valuesArr);

}


?>