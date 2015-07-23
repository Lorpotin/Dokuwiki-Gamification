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

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">

  <!-- SCRIPTS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>

</head>
<body>
  <?php      
      print_r($_COOKIE["oldUser"]);
      //setupCookie("oldUser");    
      if(!isset($_COOKIE['oldUser'])) 
      {      
        redirect('indexpage.php');    
      }
      else
      {      
        redirect('homepage.php');    
      }    
      function redirect($url, $statusCode = 303)    
      {      
        header('Location: ' . $url, true, $statusCode);      
        die();    
      }    
      /*function setupCookie($cookieName)
      {      
        setcookie($cookieName, "", time()+3600);    
      }*/    
    ?>
<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>

