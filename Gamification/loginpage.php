<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Login</title>
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
  <?php session_start(); ?>

  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <!-- .container is main centered wrapper -->
<div class="container">
  <!-- row is one line in webpage -->
  <div class="row">
    <div class="six columns">
      <!-- Every row has 12 columns |1|2|3|4|5|6|7|8|9|10|11|12|-->
      <h1>Login</h1>
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
  <!-- new row-->
  
  <!-- new row-->
  <div class="row">
    <div id="loginForm" class="six columns">  
      <form name="login" action="" method="post" accept-charset="utf-8">  
        <label id="confLabelName" for="loginname">Your username</label>
        <input id="loginName" class="u-half-width" type="username" name="username" placeholder="username"></li><br>
        <label id="confLabelPw" for="loginpw">Your password</label>
        <input id="loginPw" class="u-half-width" type="password" name="password" placeholder="password"></li><br>
        <input id="login" class="button-primary" type="button" value="Login"></li>
      </form>
    </div>
  </div>
</div>


<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>

