<?php
		if(isset($_POST['submitEmail']))
		{
			$email = $_SESSION['email'];
			$newemail = $_POST['new_email'];
			$db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');

			$sql = "SELECT * FROM user WHERE email = '$email' ";
			$result = $db->prepare($sql);
			$result->execute();

			if($result->rowCount() > 0)
			{
				$sql = "UPDATE user SET email = '$newemail' 
						WHERE email = '$email'";
		 		$req = $db->prepare($sql);
				$req->execute();
				header("Location: http://ntier3/webapp/index.php?action=AccountController&display=");
				$_SESSION['email'] = $newemail;
			}
			else
			{
				echo "error occured";
			}
		}
		else if(isset($_POST['submitPass']))
		{
			$email = $_SESSION['email'];
			$oldpass = $_POST['old_pass'];
			$newpass = $_POST['new_pass'];
			$confirmpass = $_POST['new_confirm'];
			$db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');

			$sql = "SELECT * FROM user WHERE email = '$email' ";
			$result = $db->prepare($sql);
			$result->execute();
			if($result->rowCount() > 0){
				$data = $result->fetchAll();
				if(password_verify($oldpass, $data[0]["password"]))
				{
					if($oldpass !== $newpass){
						if($newpass === $confirmpass){
							$newpass = password_hash($newpass, PASSWORD_DEFAULT);
							$sql = "UPDATE user SET password = '$newpass' 
									WHERE email = '$email'";
					 		$req = $db->prepare($sql);
							$req->execute();
							header("Location: http://ntier3/webapp/index.php?action=AccountController&display=");
						}
						else{
							echo "confirmation and new password not equal";
						}
						
					}
					else{
						echo "same password as former one";
					}
				}
				else
				{
					echo "wrong password";
				}

			}
			
			
			
		}
		else if(isset($_POST['submitDel']))
		{
			$email = $_SESSION['email'];
			$name = $_SESSION['name'];
			$db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');

			$sql = "DELETE FROM user WHERE email = '$email' AND name = '$name'";
			$result = $db->prepare($sql);
			$result->execute();
			session_destroy();
			header("Location: http://ntier3/webapp/index.php?action=");
			
		}


?>