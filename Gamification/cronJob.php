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
	//Kun tiedetään käyttäjän tyyppimuutoksien määrä, voidaan niistä laskea saavutukset käyttäjälle, johon oma funktio alempana.
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
//Tällä funktiolla katotaan, onko käyttäjällä jo tietty saavutus tietokannassa. Jos on, emme laita sitä sinne uudestaan. Muuten meillä on nopeasti tietokannassa tuhansia rivejä,
//koska tämä scripti pyörii kuitenkin jatkuvasti taustalla. Joka tapauksessa performanssiongelmia luvassa.. :)
function getExistingAchievements($username, $achievementID)
{
	$db = new PDO('sqlite:userdb.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $numOfRows = 0;
    try
    {
		$query = " SELECT * FROM Achievement WHERE username = '$username' AND achievementID = '$achievementID' ";
		$result = $db->prepare($query);
		$result->execute();
		foreach($result as $row)
		{
			$numOfRows = $row[0];
		}
		if($numOfRows > 0)
		{
			return true;
		}
		else
			return false;
    }
    catch(PDOException $e)
    {
    	echo "Connection failed: " .$e->getMessage();
    }    
}

function updateDataBase($finalArray)
{
	
    //Käyttäjä "alustetaan" tietokantaan rekisteröitymisen yhteydessä. Itseasiassa ei välttämättä tätä tarkistusta edes tarvita, koska UPDATE etsii tietokannasta nimellä WHERE
    //Lausekkeen takia.
    //*********ACHIEVEMENT ID:T*********
    /*
    1	Ensimmäinen julkaisu
	2	15 julkaisua
	3	50 julkaisua
	4	Ensimmäinen muokkaus (minor)
	5	15 muokkausta (minor)
	6	100 muokkausta (minor)
	7	500 muokkausta (minor)
	8	Ensimmäinen laaja muokkaus
	9	10 laajaa muokkausta
	10	100 laajaa muokkausta
	11	500 laajaa muokkausta
	12	Ensimmäinen sisäänkirjautuminen
	13	10 sisäänkirjautumista
	14	100 sisäänkirjautumista
	15	1000 sisäänkirjautumista
	16	Profiilin tiedot päivitetty ja profiilikuva asetettu
	17	Ensimmäinen kaveri lisätty
	18	100 pistettä saavutettu
	19	1000 pistettä saavutettu
	20	Kaikki saavutukset saavutettu
	21	Veteraani (liittynyt yli vuosi sitten)
	*/
	//*********ACHIEVEMENT ID:T*********

	
	$db = new PDO('sqlite:userdb.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	//Tällä saa jo pisteet päivitettyä. Sit vie saavutukset. Osa joudutaan tekemään korkeammalla sovelluskerroksella?, koska sovelluksen sisäisiä saavutuksia.
    foreach($finalArray as $value)
    {
    	$username = $value["user"];
    	$points = $value["points"];
    	try
    	{
    		$query = "UPDATE User SET points = '$points' WHERE username = '$username'";
    		$result = $db->prepare($query);
    		$result->execute();
      	}
      	catch(PDOException $e)
    	{
      		echo "Connection failed: " .$e->getMessage();
    	}    
    }

    for($i = 0;  $i < count($finalArray); $i++) 
    {
    	//var_dump($finalArray[$i]["etype"]);
    	//var_dump($username);
    	if($finalArray[$i]["etype"] >= 1)
    	{
    		$username = $finalArray[$i]["user"];
    		//Tarkastetaan joka nimen kohdalla löytyykö henkilöltä jo kyseinen saavutus. Kokonaislukuna syötettävä parametri funktiolle on ylläolevan taulukon mukainen achievementID.
    		if(!getExistingAchievements($username, 8))
    		{
    			try
	    		{
		    		$query = "INSERT INTO Achievement (username, achievementID) VALUES ('$username', '8')";
		    		$result = $db->prepare($query);
		    		$result->execute();
	      		}
		      	catch(PDOException $e)
		    	{
		      		echo "Connection failed: " .$e->getMessage();
		    	}    
    		}
    		else
    			var_dump("eipäs lisätä.");
    		
    	}
    	if($finalArray[$i]["etype"] >= 10)
    	{
    		$username = $finalArray[$i]["user"];
    		if(!getExistingAchievements($username, 9))
    		{
	    		try
	    		{
		    		$query = "INSERT INTO Achievement (username, achievementID) VALUES ('$username', '9')";
		    		$result = $db->prepare($query);
		    		$result->execute();
	      		}
		      	catch(PDOException $e)
		    	{
		      		echo "Connection failed: " .$e->getMessage();
		    	}    
		    }
		    else
    			var_dump("eipäs lisätä.");
    	}
    	if($finalArray[$i]["etype"] >= 100)
    	{
    		
    	}
    	if($finalArray[$i]["etype"] >= 500)
    	{
    		
    	}
    	if($finalArray[$i]["ctype"] >= 1)
    	{
    		$username = $finalArray[$i]["user"];
    		if(!getExistingAchievements($username, 1))
    		{
	    		try
	    		{
		    		$query ="INSERT INTO Achievement (username, achievementID) VALUES ('$username', '1')";
		    		$result = $db->prepare($query);
		    		$result->execute();
	      		}
		      	catch(PDOException $e)
		    	{
		      		echo "Connection failed: " .$e->getMessage();
		    	}    
		    }
		    else
    			var_dump("eipäs lisätä.");
    	}
    	if($finalArray[$i]["ctype"] >= 15)
    	{
    		$username = $finalArray[$i]["user"];
    		if(!getExistingAchievements($username, 2))
    		{
	    		try
	    		{
		    		$query = "INSERT INTO Achievement (username, achievementID) VALUES ('$username', '2')";
		    		$result = $db->prepare($query);
		    		$result->execute();
	      		}
		      	catch(PDOException $e)
		    	{
		      		echo "Connection failed: " .$e->getMessage();
		    	}    
		    }
		    else
    			var_dump("eipäs lisätä.");
    	}
    	if($finalArray[$i]["ctype"] >= 50)
    	{
    		
    	}
    	
    }

	
}


?>