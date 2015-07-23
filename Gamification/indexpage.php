<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Gamification</title>
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
  <link rel="stylesheet" href="css/indexCustom.css">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">

  <!-- SCRIPTS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>

</head>
<body>
 <?php session_start(); ?>
  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <!-- .container is main centered wrapper -->

<div class ="section welcome">
  <div class="container">
    <div class="row">
      <div class="one-half column">
        <h3>Expand your wiki experience by gamification</h3>
        <button id="learnMore" class="button button-primary indexbutton">Learn More</button>
      </div>
      <div class="one-half column coins">
        <img class="coin" src="images/coin1.png">
        <img class="coin" src="images/coin1.png">
      </div>
    </div>
  </div>
</div>

<div class="section register">
  <div class="container">
    <div class="row">
      <div class="twelve columns">
        <h3>Start tracking your dokuwiki activity today!</h3>
        <button id="goToRegisterPage" class="button-primary indexbutton">Register</button>
      </div>
    </div>
  </div>
</div>

<div class="section infos">
  <div class="container">
    <div class="row">
      <div class="one-third column info">
        <h3 class="title">Leaderboards</h3>
        <h6>Compete with others!</h5>
      </div>
      <div class="one-third column info">
        <h3 class="title">Achievements</h3>
        <h6>Hunt achievements!</h5>
      </div>
      <div class="one-third column info">
        <h3 class="title">Badges</h3>
        <h6>Gain badges!</h5>
      </div>
    </div>
  </div>
</div>
<div class="section skip">
  <div class="container">
    <div class="row">
      <div class="twelve columns skip">
        <a class="skip" href="homepage.php">Click here to go to the homepage!</a>
      </div>
    </div>
  </div>
</div>


<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>

