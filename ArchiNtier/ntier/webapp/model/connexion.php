<?php 
if (session_status() === PHP_SESSION_NONE){session_start();} 
if(isset($_POST['submitIn']))
{
	$email = $_POST['email_login'];
	$pass = $_POST['pass_login'];

	$db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');

	$sql = "SELECT * FROM user where email = '$email' ";
	$result = $db->prepare($sql);
	$result->execute();

	if($result->rowCount() > 0)
	{
		$data = $result->fetchAll();//recupere une liste de toutes les lignes resultats de la requete
		$res=$data[0];//recupere le 1er enregistrement de la bdd (normalement que 1)
		//echo $res['name'];

		if(password_verify($pass, $data[0]["password"]))
		{
			//echo "Successfully connected";
			$_SESSION['email'] = $email;
			$_SESSION['type'] = $res['type'];
			$_SESSION['name'] = $res['name'];
			header("Location: http://ntier3/webapp/index.php?action=");

		}
		else
		{
			echo "Wrong password";
		}
	}
	else
	{
		echo "Unknown user, please sign up";
	}
	
}
else if(isset($_POST['submitUp']))
{
	$email = $_POST['email_up'];
	$pass = $_POST['pass_up'];
	$name = $_POST['name_up'];
	if(array_key_exists('type', $_POST)){
		$switch = 'on';

	}
	else{
		$switch = 'off';
	}

	$db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');

	$sql = "SELECT * FROM user where email = '$email' ";
	$result = $db->prepare($sql);
	$result->execute();

	if($result->rowCount() > 0)
	{
		echo "User already registered";
	}
	else
	{
		if($switch === 'on'){
			$type = 'student';
		}
		else{
			$type = 'teacher';
		}
		$pass = password_hash($pass, PASSWORD_DEFAULT);
		$sql = "INSERT INTO user (email, password, name, type) VALUES ('$email', '$pass', '$name', '$type')";
		$req = $db->prepare($sql);
		$req->execute();
		header("Location: http://ntier3/webapp/index.php?action=LoginController");
	}
}

?>