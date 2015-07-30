<?php
  namespace gchart;

?>

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

  <!-- start of actual page -->

</head>
<body>
 <?php 
    
    require_once(__DIR__ . "/gChartPhp/gchart/gChartInit.php");
    include 'helpers/query.php'
 ?>
  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <!-- .container is main centered wrapper -->
  <div class="one column">
    <button id="notificationBtn" style="background-color:transparent; border-color:transparent;"> <img src="images/notification.png"/></button>
    <div class="notificationDisplay" id="notificationResults"></div>
  </div>
<div class="container">
  <!-- row is one line in webpage -->

  <div class="row">
     <?php if(isset($_SESSION["login_user"])){
      ?>
    
    <div class="two columns">
      <img id="profilePicture" src= <?php echo $scoreArray[0]["profilePicture"]; ?> >
    </div>
     <?php } ?>
    <div class="four columns">
      <!-- Every row has 12 columns |1|2|3|4|5|6|7|8|9|10|11|12|-->
      <input class="searchBar" id="search" type="text" placeholder="Search for people">
      <div class="searchDisplay" id="searchResults"></div>
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
      <button id="logoutBtn" class="button-primary">Log Out</button>
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
  
  
  <!-- new row-->
  <div class="row">
    <div class="twelve columns">
      <!-- <button id="updDb" class="button-primary">Update Database</button> -->
    </div>
  </div>
  <div class="row">
    <div id="welcome" class="six columns">
      <h2>Leaderboards</h2>
        <ol class="top10list">
          

        </ol>
    </div>
    <div id="welcome" class="six columns">
      <h2>Wiki activity</h2>
        <!-- <img class="loader" src="images/loading1.gif"> -->
        <p id="graphId">
          
        </p>
    </div>
     <div class="section buttons">
      <div id="buttons1" class="six columns">
        <p> </p>
      </div>
      <div id="buttons2" class="six columns">
        <button id="1monthBtn" class="button-primary">1 month</button>
        <button id="3monthBtn" class="button-primary">3 months</button>
        <button id="12monthBtn" class="button-primary">12 months</button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="section online">
      <div class="six columns">
        <h3 id="onlineHeader">No people currently online</h3>
          <ul id="onlineList">

          </ul>
      </div>
    </div>
    <div class="section chat">
      <div class="six columns">
        <h3>Chat</h3>
          <div id="chatbox">
          <?php
            if(file_exists("chatLog.html") && filesize("chatLog.html") > 0)
            {
              $handle = fopen("chatLog.html", "r");
              $contents = fread($handle, filesize("chatLog.html"));
              fclose($handle);
              echo $contents;
            }
          ?></div>
         
          <div id="message">
             <?php if(isset($_SESSION["login_user"])){
            ?>
              <input type="text" id="usermsg" />
              <input type="submit" id="submitmsg" class="button-primary" value="send"/>
            <?php }else {?>
            <h3>Log in to use the chat!</h3>
            <?php } ?>
          </div>
          
      </div>
    </div>
    
  </div>
  <!-- section classes to edit whole row through css -->
  
</div>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>

