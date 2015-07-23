<?php

session_start();

$firstname = "";
$lastname = "";
$mail = "";
$number = "";
$description = "";
$score = "";
$picture = "";
$u = "";
$resultArray = array();

// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"]))
{
    $u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} 
else 
{
    header("location: index.php");
    exit();	
}

//$scoreArray[] = array();

$db = new PDO('sqlite:userdb.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try
{
	$query = "SELECT firstname, lastname, email, honenumber, description, points, profilePicture FROM User WHERE username ='$u'";
	$result = $db->prepare($query);
	$result->execute();
    if(!$row = $result->fetch(PDO::FETCH_ASSOC))
    {
        $success = false;
    }
    else
        $success = true;
    
    if($success)
    {
        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        $mail = $row["email"];
        $number = $row["honenumber"];
        $description = $row["description"];
        $score = $row["points"];
        $picture = $row["profilePicture"];
        //$scoreArray[] = array("firstname" => $firstname, "lastname" => $lastname, "email" => $mail, "honenumber" => $number, "description" => $description, "points" => $score, "profilePicture" => $picture);	
    }
    else
    {
        header("Location: homepage.php");
        exit();
        

    }
}
catch(PDOException $e)
{
    echo "Connection failed: " .$e->getMessage();
}
try
{
  $query = "SELECT Achievement.achievementID FROM Achievement INNER JOIN User ON Achievement.username = User.username WHERE User.username = '$u'";
  $result = $db->prepare($query);
  $result->execute();
  if($result)
  {
    $achievementsFound = true;
    while($row = $result->fetch(PDO::FETCH_ASSOC))
    {
      $achievement = $row["achievementID"];
      $resultArray[] = array("achievementID" => $achievement);
    }
  }
  else
  {
    $achievementsFound = false;
  }
}
catch(PDOException $e)
{
    echo "Connection failed: " .$e->getMessage();
}

?>

<?php
  $isFriend = false;
  if(isset($_SESSION['login_user']))
  {
    $log_username = $_SESSION['login_user'];
     if($u != $_SESSION['login_user'])
  {
      $db = new PDO('sqlite:userdb.sqlite');
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try
      {
          $query = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
          $result = $db->prepare($query);
          $result->execute();
          if(!$row = $result->fetch(PDO::FETCH_ASSOC))
          {
              $success = false;
          }
          else
              $success = true;

          if($success)
          {
              $isFriend = true;
          }
          //var_dump($success);
          //var_dump($isFriend);
    
      }
      catch(PDOException $e)
      {
          echo "Connection failed: " .$e->getMessage();
      }

  }
  }
 
?>

  
<?php

  if($isFriend == true)
  {
      $friend_button = '<button class="button-primary" disabled>You are friends!</button>';
  }
  else
    $friend_button = '<button onclick="friendRequest()" class="button-primary">Request As a Friend</button>';

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
  <link type="text/css"  href="js/toastmaster/src/main/resources/css/jquery.toastmessage.css" rel="stylesheet"/>

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">

  <!-- SCRIPTS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
  <script type="text/javascript" src="js/toastmaster/src/main/javascript/jquery.toastmessage.js"></script>

  <!-- Place this javascript function here, so we can easily access the $_GET['u'] variable -->
  <script type="text/javascript">
   function friendRequest()
   {
      var action = "friend";
      var user = "<?php echo $_GET['u']; ?>";
      $.ajax(
          {
          type: "POST",
          url: "ajax_handler.php",
          data: "postaction="+action+"&user="+user,
          cache: "false",
          success: function(returnData)
          {
              console.log(returnData);
              if(returnData == "\r\nalreadyFriends")
              {
                  StickyToast("You are already friends!");
              }
              if(returnData == "\r\npendingFriend")
              {
                  console.log("asd");
                  StickyToast("You already have a pending friend request sent!");
              }
              if(returnData == "\r\nisRequested")
              {
                  StickyToast("You are already requested to be friends!");
              }
              if(returnData == "\r\nisSent")
              {
                  StickyToast("Friend request sent.");
              }
          }
      }); 
   }

   function StickyToast(variable) 
   {
      console.log("asd");
      $().toastmessage('showToast', {
         text     :  variable,
         sticky   :  false,
         stayTime :  1500,
         position : 'top-right',
         type     : 'success',
         closeText: '',
         close    : function () 
         {

         }
      });
   }
   </script>

</head>
<!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<body>

<div class="container">
  <!-- row is one line in webpage -->
  <div class="row">
    <div class="six columns">
      <!-- Every row has 12 columns |1|2|3|4|5|6|7|8|9|10|11|12|-->
      <h1><?php echo $_GET["u"]; echo "'s profile";
      ?></h1> 

    </div>
    <div id="welcome" class="one columns">
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
  <div class="row">
    <hr>
  </div>
  <div class="row">
    <div class="section profileinformation">
      <div class="twelve columns">
        <img id="profilePicture" src= <?php echo $picture ?> >
        <p><?php echo $firstname; echo " "; echo $lastname; ?></p>
        <p><?php echo $mail; ?></p>
        <p><?php echo $number; ?></p>
        <p><?php echo $description; ?></p>
        <p style="font-size: 42px; color: red"><?php echo $score; echo " points"; ?></p>
        <p><?php if($achievementsFound) { for($i = 0; $i < count($resultArray); ++$i)
        {
          $path = "images/Achievements/Badge".$resultArray[$i]["achievementID"].".png";
          ?>
            <img id="profilePicture" src=<?php echo $path; ?> >
          <?php
        }
        }
        ?> </p>
        <?php if(isset($_SESSION["login_user"]) && $u != $_SESSION['login_user'])
        {
        ?>
          <p> <span id="friendBtn"><?php echo $friend_button; ?> </span> </p>
        <?php 
        }
        ?>
      </div>
    </div>
  </div>
</div>


<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
