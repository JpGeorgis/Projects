<?php

require '_defines.php';
require_once('model/account.php');
class accountview
{
	public function public(){
		$contentfile='account.php';
		include VIEWS.DS.'template.php';
	}
}

class AccountController{
	public function viewpublic(){
		$hv= new accountview();
		$hv->public();
	}
}


?>