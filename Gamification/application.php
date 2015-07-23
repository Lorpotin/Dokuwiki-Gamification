<?php
//Return all users and their points in the database




function GetAllWikiPoints()
{
	$db = new PDO('sqlite:userdb.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try
	{
		$query = "SELECT username, points from User ORDER BY points DESC LIMIT 5";
		$result = $db->prepare($query);
		$result->execute();
		if($result)
		{
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$points = $row["points"];
				$username = $row["username"];
				$scoreArray[] = array("username" => $username, "score" => $points);
			}
			echo $jsonFormat = json_encode($scoreArray);
		}
		else
			Error();
	}
	catch(PDOException $e)
	{
	    echo "Connection failed: " .$e->getMessage();
	}

}


function GetActiveUser()
{
	session_start();
	return $_SESSION['login_user'];	
}
function GetSessionId()
{
	session_start();
	return $_SESSION['uid'];
}

function GetAchievements()
{
	session_start();
	$username = $_SESSION['login_user'];
	$db = new PDO("sqlite:userdb.sqlite");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try
    {
    	$query = "SELECT Achievement.achievementID, Achievement.username FROM Achievement INNER JOIN User ON Achievement.username = User.username WHERE User.username = '$username'";
		$result = $db->prepare($query);
		$result->execute();
		if($result)
		{	
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$achievement = $row["achievementID"];
				$returnName = $row["username"];
				$resultArray[] = array("username" => $returnName, "achievementID" => $achievement);
			}
			echo $jsonFormat = json_encode($resultArray);
		}
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " .$e->getMessage();
    }
}

//Get all usernames located in dokuwiki who have made changes -> active users. Used for validating usernames when creating a new account.
function GetAllUserNames($username)
{
	$jsonString = file_get_contents("http://lorpotin.website/Projects/dokuwiki/data.json");
	$parsedJson = json_decode($jsonString, true);
	foreach ($parsedJson as $key => $value) 
	{
		if($value['user'] == $username && $value['user'] != "")
		{
			echo true;
			break;
		}
		else
		{
			echo false;
			break;
		}
	}

}
function validateUserName($username)
{
	$jsonString = file_get_contents("http://lorpotin.website/Projects/dokuwiki/data.json");
	$parsedJson = json_decode($jsonString, true);

	foreach ($parsedJson as $key => $value) 
	{
		if($value['user'] == $username && $value['user'] != "")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

function createAccount($username, $password)
{
	$numOfRows = 0;
	$db = new PDO('sqlite:userdb.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$checkIfExist = "SELECT count(*) FROM User WHERE username='$username'";
	try
	{
		$result = $db->prepare($checkIfExist);
		$result->execute();
		foreach($result as $row)
		{
			$numOfRows = $row[0];
		}
		if($numOfRows > 0)
		{
			if(!isset($_COOKIE['oldUser'])) 
			{      
			    setupCookie("oldUser", $username); 
			}
			echo "accountfound";
		}
	}
	catch(PDOException $e)
	{
	    echo "Connection failed: " .$e->getMessage();
	}
}

function UpdateProfile()
{
	session_start();
	$username = $_SESSION['login_user'];
	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$mail = $_POST["mail"];
	$phone = $_POST["phone"];
	$desc = $_POST["desc"];
	$db = new PDO("sqlite:userdb.sqlite");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try
    {
    	$query = "UPDATE User SET firstname = '$firstname', lastname = '$lastname', email = '$mail', honenumber = '$phone', description = '$desc' WHERE username = '$username'";
    	$db->exec($query);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " .$e->getMessage();
    }
}

function GetProfilePicture()
{
	$username = GetActiveUser();
	$db = new PDO('sqlite:userdb.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try
	{
		$query = "SELECT profilePicture from User where username = '$username'";
		$result = $db->prepare($query);
		$result->execute();
		if($result)
		{
			foreach($result as $row)
			{
				echo $row[0];
			}
		}
		else
			Error();
	}
	catch(PDOException $e)
	{
	    echo "Connection failed: " .$e->getMessage();
	}
}

function RegisterNewUser($username, $password, $confirmationPw)
{
	if($password == "")
	{
		echo "empty";
	}
	else if($password != $confirmationPw)
	{
		echo "match";
	}
	else if(!validateUserName($username))
	{
		echo "usernameError";
	}
	else
	{
		createAccount($username, $password);
	}
}
//Login and set currentlyLoggedIn flag to true
function Login($username, $password)
{
	session_start();
	$numOfRows = 0;
	if($username == "")
	{
		echo "emptyUser";
	}
	else if($password == "")
	{
		echo "emptyPw";
	}
    else
    {
	    $db = new PDO("sqlite:userdb.sqlite");
	    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    try
	    {
	    	$query = "SELECT uid FROM User WHERE username='$username' AND password ='$password'";
			$result = $db->prepare($query);
			$result->execute();
			
			foreach($result as $row)
			{
				$numOfRows = $row[0];
			}
			if($numOfRows > 0)
			{
				$row = $result->fetch(PDO::FETCH_ASSOC);
				$_SESSION["login_user"] = $username;
				$_SESSION["uid"] = $row[0];
				if(!isset($_COOKIE['oldUser'])) 
			    {      
			    	setupCookie("oldUser", $username); 
			    }
			    $query = "UPDATE User SET currentlyLoggedIn = 'true' WHERE username = '$username'";
        		$db->exec($query);
				echo true;
			}
			else
				echo false;
		}
		catch(PDOException $e)
	    {
	        echo "Connection failed: " .$e->getMessage();
	    }
	}
}
//Logout and set the currentlyLoggedIn flag to false.
function LogOut()
{
	session_start();
	if(!empty($_SESSION['login_user']))
	{
		$username = $_SESSION['login_user'];
		$db = new PDO("sqlite:userdb.sqlite");
	    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    try
	    {
	    	$query = "UPDATE User SET currentlyLoggedIn = 'false' WHERE username = '$username'";
        	$db->exec($query);
        	$_SESSION['login_user'] = null;
			$_SESSION['uid'] = null;
			session_destroy();
			echo true;
        }
        catch(PDOException $e)
	    {
	        echo "Connection failed: " .$e->getMessage();
	    }
		
	}
}
//Gets players currentlyLoggedIn flags and echos them to client
function GetPlayersOnlineNow()
{
	$db = new PDO('sqlite:userdb.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try
	{
		$query = "SELECT username from User WHERE currentlyLoggedIn ='true'";
		$result = $db->prepare($query);
		$result->execute();
		if($result)
		{
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$username = $row["username"];
				$scoreArray[] = array("username" => $username);
			}
			echo $jsonFormat = json_encode($scoreArray);
		}
		else
			Error();
	}
	catch(PDOException $e)
	{
	    echo "Connection failed: " .$e->getMessage();
	}
}

function SearchUsers()
{
	
	$q = $_POST["searchword"];
	if($q != "")
	{
		$db = new PDO('sqlite:userdb.sqlite');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try
		{
			$query = "SELECT username from User WHERE username LIKE '%$q%'";
			$result = $db->prepare($query);
			$result->execute();
			if($result)
			{
				while($row = $result->fetch(PDO::FETCH_ASSOC))
				{
					$username = $row["username"];
					$success = "true";
					$scoreArray[] = array("username" => $username, "success" => $success);
				}
				echo $jsonFormat = json_encode($scoreArray);
			}
			else
				Error();

		}
		catch(PDOException $e)
		{
		    echo "Connection failed: " .$e->getMessage();
		}
	}
	
}

function FriendSystem()
{
	session_start();
	$log_username = $_SESSION["login_user"];
	$db = new PDO('sqlite:userdb.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if($_POST['postaction'] == "friend")
	{
		$user = $_POST["user"];
		try
		{
			//Okay. We NEED atleast? i don't know -> five queries in order to fully determine if two persons are friends, and if they have accepted their friend requests

			$query = "SELECT COUNT(id) FROM Friends WHERE user1='$user' AND accepted='1' OR user2='$user' AND accepted='1'";
			$result = $db->prepare($query);
			$result->execute();
			//Fetch number of rows with FETCH_NUM
			$friend_count = $result->fetch(PDO::FETCH_NUM);

			$query = "SELECT COUNT(id) FROM Friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
			$result = $db->prepare($query);
			$result->execute();
			//Fetch number of rows with FETCH_NUM
			$row_count1 = $result->fetch(PDO::FETCH_NUM);

			$query = "SELECT COUNT(id) FROM Friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
			$result = $db->prepare($query);
			$result->execute();
			//Fetch number of rows with FETCH_NUM
			$row_count2 = $result->fetch(PDO::FETCH_NUM);

			$query = "SELECT COUNT(id) FROM Friends WHERE user1='$log_username' AND user2='$user' AND accepted='0' LIMIT 1";
			$result = $db->prepare($query);
			$result->execute();
			//Fetch number of rows with FETCH_NUM
			$row_count3 = $result->fetch(PDO::FETCH_NUM);

			$query = "SELECT COUNT(id) FROM Friends WHERE user1='$user' AND user2='$log_username' AND accepted='0' LIMIT 1";
			$result = $db->prepare($query);
			$result->execute();
			//Fetch number of rows with FETCH_NUM
			$row_count4 = $result->fetch(PDO::FETCH_NUM);

			if (intval($row_count1[0]) > 0 || intval($row_count2[0]) > 0)
			{
				echo "alreadyFriends";
				exit();
			}
			else if (intval($row_count3[0]) > 0)
			{
				echo "pendingFriend";
				exit();
			}
			else if (intval($row_count4[0]) > 0)
			{
				echo "isRequested";
				exit();
			}
			else
			{
				$query = "INSERT INTO Friends (user1, user2) VALUES ('$log_username', '$user')";
				$result = $db->prepare($query);
				$result->execute();
				echo "isSent";
				exit();
			}

		}
		catch(PDOException $e)
		{
		    echo "Connection failed: " .$e->getMessage();
		}
		
	}
	else if($_POST["postaction"] == "accept")
	{
		if($_POST["name"] && $_POST["uid"])
		{
			$user = $_POST["name"];
			$uid = $_POST["uid"];
			$query = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
			$result = $db->prepare($query);
			$result->execute();
			//Fetch number of rows with FETCH_NUM
			$row_count1 = $result->fetch(PDO::FETCH_NUM);

			$query = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
			$result = $db->prepare($query);
			$result->execute();
			//Fetch number of rows with FETCH_NUM
			$row_count2 = $result->fetch(PDO::FETCH_NUM);
			if ($row_count1[0] > 0 || $row_count2[0] > 0) 
			{
				echo "alreadyFriends";
				exit();
			}
			else
			{
				$query = " UPDATE Friends SET accepted ='1' WHERE id = '$uid' AND user1 = '$user' AND user2 = '$log_username' ";
				$result = $db->prepare($query);
				$result->execute();
				echo "acceptOK";
			}
		}
	}

	else if($_POST["postaction"] == "decline")
	{
		if($_POST["name"] && $_POST["uid"])
		{
			$query = " DELETE FROM Friends WHERE id = '$uid' AND user1 = '$user' AND user2 = '$log_username' AND accepted = '0' ";
			$result = $db->prepare($query);
			$result->execute();
			echo "declineOK";
		}
	}
	
}

function GetFriendRequests()
{
	//To do, finish friend requests in php and javascript. HTML should be fine and containers are ready to receive data.
	session_start();
	$resultArray = array();
	$log_username = $_SESSION["login_user"];
	$db = new PDO('sqlite:userdb.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try
	{
		$query = "SELECT * FROM friends WHERE user2 = '$log_username' AND accepted='0'";
		$result = $db->prepare($query);
		$result->execute();
		//Fetch number of rows with FETCH_NUM
		$row_count = $result->fetch(PDO::FETCH_NUM);
		if($row_count[0] < 1)
		{
			echo "noRequests";
		}
		else
		{
			$query = "SELECT * FROM friends WHERE user2 = '$log_username' AND accepted='0'";
			$result = $db->prepare($query);
			$result->execute();
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$id = $row["id"];
				$user1 = $row["user1"];
				$resultArray[] = array("id" => $id, "user1" => $user1);
			}
			echo $jsonFormat = json_encode($resultArray);
		}
	}
	catch(PDOException $e)
	{
	    echo "Connection failed: " .$e->getMessage();
	}
}

function HandleChat($text)
{
	session_start();
	if(isset($_SESSION['login_user']))
	{
	    $fp = fopen("chatLog.html", 'a');
	    fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$_SESSION['login_user']."</b>: ".$text."<br></div>");
	    fclose($fp);
	}
}

//Redirect to mainpage instead of index page if cookie is found(old user is using the app)     
function redirect($url, $statusCode = 303)    
{      
	header('Location: ' . $url, true, $statusCode);      
	die();    
} 
//Save cookies when logging in or registering -> dont show "commercial" page if old user   
function setupCookie($cookieName, $username)
{      
	setcookie($cookieName, $username, time()+60*60*24*365, "/");    
}    



function DebugPrint()
{
	echo "Just a test print? Varför..";
}

function Error()
{
	echo "An error occured!";
}

?>