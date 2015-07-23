<?php

	$target_user = GetActiveUser();
	$target_dir = "uploads/".$target_user."/";
	if (!file_exists($target_dir)) 
	{
    	mkdir($target_dir, 0777, true);
	}
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["fileToUpload"])) 
	{
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) 
	    {
	        echo "File is an image - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } 
	    else 
	    {
	        echo "File is not an image.";
	        $uploadOk = 0;
	    }
	}
	// Check if file already exists
	if (file_exists($target_file)) 
	{
	    echo "Sorry, file already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) 
	{
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) 
	{
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) 
	{
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} 
	else 
	{
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
	    {
	    	$db = new PDO('sqlite:userdb.sqlite');
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try
			{
				$query = "UPDATE User SET profilePicture = '$target_file' WHERE username = '$target_user'";
        		$db->exec($query);
	        	echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	        }
	        catch(PDOException $e)
			{
	    		echo "Connection failed: " .$e->getMessage();
			}
	    } 
	    else 
	    {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}

	function GetActiveUser()
	{
		session_start();
		return $_SESSION['login_user'];	
	}


?>
