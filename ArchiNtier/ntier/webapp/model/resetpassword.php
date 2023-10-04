<?php
	if(isset($_POST['submitPass']))
		{
			$email = $_POST['email'];
			$code = mt_rand(100000, 999999);
			/*$message="Need to reset your password ?
					  Use your secret code !
					  $code";
			mail($email, 'Password reset', $message); MARCHE PAS*/
			$db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');

			$sql = "SELECT * FROM user where email = '$email' ";
			$result = $db->prepare($sql);
			$result->execute();

			if($result->rowCount() > 0)
			{
				$newpass = password_hash($newpass, PASSWORD_DEFAULT);
				$sql = "UPDATE user SET password = '$newpass' 
						WHERE email = '$email'";
		 		$req = $db->prepare($sql);
				$req->execute();
				header("Location: http://ntier3/webapp/index.php?action=LoginController");
				
			}
			else
			{
				echo "Unknown user, please sign up";
			}
		}



?>