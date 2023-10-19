<?php
require '_defines.php';
require_once('model/ClassesConnexion.php');
class ClassesView
{
	public function listall(){
		$c=new ClassesConnexion();
		$contentfile='Classeslist.php';
		include VIEWS.DS.'template.php';
	}

	public function listone($classid){
		$c=new ClassesConnexion();
		$contentfile='Classesdetail.php';
		include VIEWS.DS.'template.php';
	}

	public function deleteone($classid){
		$c=new ClassesConnexion();
		$c->deleteClass($classid);
		$contentfile='Classeslist.php';
		include VIEWS.DS.'template.php';
	}

	public function modifyone($classid){
		$c=new ClassesConnexion();
		$contentfile='Classesmodify.php';
		include VIEWS.DS.'template.php';
	}

	public function onmodifyclass($classid){
		$c=new ClassesConnexion();
		$c->modifyClass($classid);
		$contentfile='Classeslist.php';
		include VIEWS.DS.'template.php';
	}
	public function createclass(){
		$c=new ClassesConnexion();
		$contentfile='Classesadd.php';
		include VIEWS.DS.'template.php';
	}
	public function oncreateclass(){
		$c=new ClassesConnexion();
		$c->createClass();
		$contentfile='Classeslist.php';
		include VIEWS.DS.'template.php';
	}
}

class ClassesController{
	public function viewpublic(){
		$hv= new ClassesView();
		$hv->listall();
	}
	public function viewdetail($id){
		$hv= new ClassesView();
		$hv->listone($id);
	}
	public function delete($id){
		$hv= new ClassesView();
		$hv->deleteone($id);
	}
	public function modify($id){
		$hv= new ClassesView();
		$hv->modifyone($id);
	}

	public function onmodify($id){
		$hv= new ClassesView();
		$hv->onmodifyclass($id);
	}
	public function viewcreate(){
		$hv= new ClassesView();
		$hv->createclass();
	}
	public function oncreate(){
		$hv= new ClassesView();
		$hv->oncreateclass();
	}
}


?>