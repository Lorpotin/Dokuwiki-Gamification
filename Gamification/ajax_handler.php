<?php include 'application.php'; ?>

<?php

if(isset($_GET['getaction']))
{
	switch ($_GET['getaction'])
	{
		case 'getAllPoints':
			GetAllWikiPoints();
			break;

		case 'validateUserName':
			GetAllUserNames($_GET['name']);
			break;

		case 'getWikiActivity':
			GetWikiActivity();
			break;

		case 'getOnlineUsers':
			GetPlayersOnlineNow();
			break;

		case 'getProfilePicture':
			GetProfilePicture();
			break;

		case 'getAchievements':
			GetAchievements();
			break;

		case 'getSessionId':
			GetSessionId();
			break;

		case 'getFriendRequests':
			GetFriendRequests();
			break;

		default:
			Error();

		break;
	}
}


if(isset($_POST['postaction']))
{
	switch ($_POST['postaction'])
	{

		case 'login':
			Login($_POST['name'], $_POST['password']);
			break;

		case 'register':
			RegisterNewUser($_POST['name'], $_POST['password'], $_POST['confirmationPw']);
			break;

		case 'logout':
			LogOut();
			break;

		case 'chat':
			HandleChat($_POST['message']);
			break;

		case 'upload':
			SetProfilePicture();
			break;

		case 'updatePersonalSettings':
			UpdateProfile();
			break;

		case 'search':
			SearchUsers();
			break;

		case 'friend':
			FriendSystem();
			break;

		case 'accept':
			FriendSystem();
			break;

		case 'decline':
			FriendSystem();
			break;

		default:
			Error();

		break;
	}
}



?>