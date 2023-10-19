<?php 
require '_defines.php';

class homeview
{
	public function public(){
		$contentfile = 'homepublic.php';
		include VIEWS.DS.'template.php';
	}

	public function connected(){
		$contentfile = 'homeconnected.php';
		include VIEWS.DS.'template.php';
	}

	public function admin(){
		$contentfile = 'homeadmin.php';
		include VIEWS.DS.'template.php';
	}
}

class HomeController{
	public function viewpublic(){
		$hv = new homeview();
		$hv->public();
	}

	public function viewconnected(){
		$hv = new homeview();
		$hv->connected();
	}

	public function viewadmin(){
		$hv = new homeview();
		$hv->admin();
	}
}


?>