<?php

session_start();
$friendsFound = false;
if(isset($_SESSION["login_user"]))
{
    $db = new PDO('sqlite:userdb.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try
    {
      $username = $_SESSION["login_user"];
      $query = "SELECT username, firstname, lastname, email, honenumber, description, points, profilePicture FROM User WHERE username ='$username'";
      $result = $db->prepare($query);
      $result->execute();
      if($result)
      {
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
          $username = $row["username"];
          $firstname = $row["firstname"];
          $lastname = $row["lastname"];
          $mail = $row["email"];
          $number = $row["honenumber"];
          $description = $row["description"];
          $score = $row["points"];
          $picture = $row["profilePicture"];
          $scoreArray[] = array("username" => $username, "firstname" => $firstname, "lastname" => $lastname, "email" => $mail, "honenumber" => $number, "description" => $description, "score" => $score, "profilePicture" => $picture);
        }
      }
      else
        Error();
    }
    catch(PDOException $e)
    {
      echo "Connection failed: " .$e->getMessage();
    }    

    try
    {
        $resultArray = array();
        $username = $_SESSION["login_user"];
        $query = "SELECT user1, user2 FROM Friends WHERE User2 = '$username' OR User1 = '$username' AND accepted = '1' ";
        $result = $db->prepare($query);
        $result->execute();
        if($result)
        {
          $friendsFound = true;
          while($row = $result->fetch(PDO::FETCH_ASSOC))
          {
              $user1 = $row["user1"];
              $user2 = $row["user2"];
              $resultArray[] = array("user1" => $user1, "user2" => $user2);
          }
        }
        else
          $friendsFound = false;
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " .$e->getMessage();
    }  
}
  
    

?>