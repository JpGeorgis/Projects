<?php 
require '_defines.php';
require_once('model/disconnect.php');
class disconnectview
{
	public function public(){
		$contentfile = 'homepublic.php';
		include VIEWS.DS.'template.php';
	}
}

class DisconnectController{
	public function viewpublic(){
		$dv = new disconnectview();
		$dv->public();
	}
}


?>