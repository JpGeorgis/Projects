<?php 
require '_defines.php';
require_once('model/resetpassword.php');
class Passview
{
	public function public(){
		$contentfile = 'password.php';
		include VIEWS.DS.'template.php';
	}
}

class PassController{
	public function viewpublic(){
		$pv = new Passview();
		$pv->public();
	}
}
