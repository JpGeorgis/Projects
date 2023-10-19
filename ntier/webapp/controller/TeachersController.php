<?php

require '_defines.php';
require_once('model/TeachersConnexion.php');
require_once('model/CoursesConnexion.php');
class TeachersView
{
	public function listall(){
		$c=new TeachersConnexion();
		$contentfile='Teacherslist.php';
		include VIEWS.DS.'template.php';
	}

	public function listone($teacherid){
		$c=new TeachersConnexion();
		$contentfile='Teachersdetail.php';
		include VIEWS.DS.'template.php';
	}

	public function deleteone($teacherid){
		$c=new TeachersConnexion();
		$c->deleteTeacher($teacherid);
		$contentfile='Teacherslist.php';
		include VIEWS.DS.'template.php';
	}

	public function deleteonefromTeacher($courseid,$teacherid){
		$c=new CoursesConnexion();
		$c->deleteCourse($courseid);
		$c=new TeachersConnexion();
		$c->listoneTeacher($teacherid);
		$contentfile='Teachersdetail.php';
		include VIEWS.DS.'template.php';
	}

	public function modifyone($teacherid){
		$c=new TeachersConnexion();
		$contentfile='Teachersmodify.php';
		include VIEWS.DS.'template.php';
	}
	public function onmodifyteacher($teacherid){
		$c=new TeachersConnexion();
		$c->modifyTeacher($teacherid);
		$contentfile='Teacherslist.php';
		include VIEWS.DS.'template.php';
	}
	public function createteacher(){
		$c=new TeachersConnexion();
		$contentfile='Teachersadd.php';
		include VIEWS.DS.'template.php';
	}
	public function oncreateteacher(){
		$c=new TeachersConnexion();
		$c->createTeacher();
		$contentfile='Teacherslist.php';
		include VIEWS.DS.'template.php';
	}
}

class TeachersController{
	public function viewpublic(){
		$hv= new TeachersView();
		$hv->listall();
	}
	public function viewdetail($id){
		$hv= new TeachersView();
		$hv->listone($id);
	}
	public function delete($id){
		$hv= new TeachersView();
		$hv->deleteone($id);
	}
	public function deleteCourse($id,$id2){
		$hv= new TeachersView();
		$hv->deleteonefromTeacher($id,$id2);
	}
	public function modify($id){
		$hv= new TeachersView();
		$hv->modifyone($id);
	}
	public function onmodify($id){
		$hv= new TeachersView();
		$hv->onmodifyteacher($id);
	}

	public function viewcreate(){
		$hv= new TeachersView();
		$hv->createteacher();
	}
	public function oncreate(){
		$hv= new TeachersView();
		$hv->oncreateteacher();
	}
}


?>