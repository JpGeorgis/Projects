<?php

require '_defines.php';
require_once('model/connexion.php');
class loginview
{
	public function public(){
		$contentfile='login.php';
		include VIEWS.DS.'template.php';
	}
}

class LoginController{
	public function viewpublic(){
		$hv= new loginview();
		$hv->public();
	}
}


?>