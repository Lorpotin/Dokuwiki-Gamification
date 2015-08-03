<?php include 'helpers/query.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Your profile page!</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link href='//fonts.googleapis.com/css?family=Raleway:400,300,600' rel='stylesheet' type='text/css'>

   <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">
  <link rel="stylesheet" href="css/custom.css">
  <link type="text/css" href="js/toastmaster/src/main/resources/css/jquery.toastmessage.css" rel="stylesheet"/>
  

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">

  <!-- SCRIPTS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
  <script type="text/javascript" src="js/toastmaster/src/main/javascript/jquery.toastmessage.js"></script>

</head>
<body>

  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <!-- .container is main centered wrapper -->
<div class="container">
  <!-- row is one line in webpage -->
  <div class="row">
    <div class="two columns">
      <!-- Every row has 12 columns |1|2|3|4|5|6|7|8|9|10|11|12|-->
      <?php if(isset($_SESSION["login_user"])){ ?>
      <h1><?php echo $_SESSION['login_user']; ?></h1>

      <?php }else {?>
       <h1>Your profile.</h1>
      <?php } ?>
    </div>
    <div id="welcome" class="four columns">
      <?php if(isset($_SESSION["login_user"])){ ?>
      <?php }else {?>
        <p> </p>
      <?php } ?>
    </div>
    <div class="one column">
      <button id="homeBtn" class="button-primary">Home</button>
    </div>
    <div class="one column">
      <button id="profileBtn" class="button-primary">Profile</button>
    </div>
    <div class="one column">
      <button id="regBtn" class="button-primary">Register</button>
    </div>
    <?php if(isset($_SESSION["login_user"])){
    ?>
    <div class="one column">
      <button id="logoutBtn" class="button-primary">Logout</button>
    </div>
    <?php }else {?>
    <div class="one column">
      <button id="loginBtn" class="button-primary">Log in</button>
    </div>
    <?php } ?>
  </div>
  <!-- new row-->
  <div class="row">
    <hr>
  </div>
  <?php if(isset($_SESSION["login_user"])){
  ?>
  <!-- new row-->
  <div class="row">
    <div id="settingsToggle" class="six columns">
      <div id="mySettingsToggle" class="section mySettingsToggle">
        <p><?php echo $scoreArray[0]["firstname"]; echo " "; echo $scoreArray[0]["lastname"];  ?></p> 
        <p><?php echo $scoreArray[0]["email"];  ?> </p>
        <p><?php echo $scoreArray[0]["honenumber"];  ?> </p>
        <p><?php echo $scoreArray[0]["description"];  ?> </p>
       
        <button id="settingsBtn" class="button-primary">Change your personal settings</button>
      </div>
      <div id="hiddenSettings" class="section settingsInput">
        <input id="firstname" type="text" name="Firstname" placeholder="Firstname"><br>
        <input id="lastname" type="text" name="Lastname" placeholder="Lastname"><br>
        <input id="mail" type="text" name="E-Mail" placeholder="E-Mail"><br>
        <input id="phone" type="text" name="Phone" placeholder="Phone"><br>
        <input id="desc" type="text" name="Website" placeholder="Description"><br>
         <div class ="dnd" id="filedrag">Drag & Drop image here 
          <form style="visibility:hidden" method="post" enctype="multipart/form-data" id="yourregularuploadformId">
            <input id="imageInput" type="file" name="fileToUpload" multiple="multiple">
          </form>
        </div> 
        <input id="saveButton" type="button" value="Save" class="button-primary">
        <input id="cancelButton" type="button" value="Cancel" class="button-primary">
      </div>
    </div>
     <div id="myInfo1" class="section myInfo1">
      <div id="welkum" class="six columns">
        <h2 id="points" style="color: #33C3F0"><?php echo $scoreArray[0]["score"]; echo " points"; ?></h2>
        <h2 style="color: #33C3F0">Achievements</h2>
        <p id="achHeader"></p>
        <h2 style="color: #33C3F0">Your friends </h2><h2><?php 
        
        //Lets go through the resultArray which consists of logged in user and users that have accepted his friend requests
        if($friendsFound)
        {
          foreach ($resultArray as $index => $data) 
          {

              //Check if either of users are the correct user
              if ($data['user1'] == $_SESSION["login_user"] || $data['user2'] == $_SESSION["login_user"]) 
              {
                  //When true we individually check if user1 or user 2 is the logged in user, and replace that with an empty line. You cant be friends with yourself!
                  if ($resultArray[$index]["user1"] == $_SESSION["login_user"])
                  {
                      $resultArray[$index]["user1"] = null;
                  }
                  if ($resultArray[$index]["user2"] == $_SESSION["login_user"])
                  {
                      $resultArray[$index]["user2"] = null;
                  }
                  
              }
          }

          //Get profile pictures and points for each friends too with the sql select clause.
          for($i = 0; $i < count($resultArray); $i++)
          {
              if($resultArray[$i]["user1"] != "")
              {
                  $profilePictureArray = array();
                  $username = $resultArray[$i]['user1'];
                  $query = " SELECT profilePicture, points FROM User WHERE username = '$username' ";
                  $result = $db->prepare($query);
                  $result->execute();
                  if($result)
                  {
                      while($row = $result->fetch(PDO::FETCH_ASSOC))
                      {
                         $profilePic = $row["profilePicture"];
                         $points = $row["points"];
                         $profilePictureArray[] = array("profilePicture" => $profilePic, "points" => $points);
                      }
                  }
                  else
                    echo "No image found!";

                  echo "<a href=user.php?u=".$resultArray[$i]['user1'].">".$resultArray[$i]['user1']."</a> ";
                  echo "<img class='profilePicClass' src=".$profilePictureArray[0]["profilePicture"].">";
                  //echo "<h2>".$profilePictureArray[0]["points"]." points</h2>";
                  echo "<br>";
              }
              if($resultArray[$i]["user2"] != "")
              {
                  $profilePictureArray = array();
                  $username = $resultArray[$i]['user2'];
                  $query = " SELECT profilePicture, points FROM User WHERE username = '$username' ";
                  $result = $db->prepare($query);
                  $result->execute();
                  if($result)
                  {
                      while($row = $result->fetch(PDO::FETCH_ASSOC))
                      {
                         $profilePic = $row["profilePicture"];
                         $points = $row["points"];
                         $profilePictureArray[] = array("profilePicture" => $profilePic, "points" => $points);
                      }
                  }
                  else
                    echo "No image found!";


                  echo "<a href=user.php?u=".$resultArray[$i]['user2'].">".$resultArray[$i]['user2']."</a> ";
                  echo "<img class='profilePicClass' src=".$profilePictureArray[0]["profilePicture"].">";
                  //echo "<h2>".$profilePictureArray[0]["points"]." points</h2>";
                  echo "<br>";
              }
          } 
        }


        ?></h2>
      </div>
    </div>
  </div>

  <!-- new row-->

   
  <?php }else {?>
  <!-- new row-->
  <div class="row">
    <div id="welcome" class="twelve columns">
      <h1>Login to view your personal profile, or register now to start tracking your dokuwiki progress!</h1>
    </div>
  </div>
  <?php } ?>
</div>


<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>

