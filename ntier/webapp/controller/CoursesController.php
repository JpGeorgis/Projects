<?php

require '_defines.php';
require_once('model/CoursesConnexion.php');
class CoursesView
{
	public function listall(){
		$c=new CoursesConnexion();
		$contentfile='Courseslist.php';
		include VIEWS.DS.'template.php';
	}

	public function listone($courseid){
		$c=new CoursesConnexion();
		$contentfile='Coursesdetail.php';
		include VIEWS.DS.'template.php';
	}
	public function createcourse(){
		$c=new CoursesConnexion();
		$contentfile='Coursescreate.php';
		include VIEWS.DS.'template.php';
	}
	public function oncreatecourse(){
		$c=new CoursesConnexion();
		$c->createcourse();
		$contentfile='Courseslist.php';
		include VIEWS.DS.'template.php';
	}
	public function deleteone($courseid){
		$c=new CoursesConnexion();
		$c->deleteCourse($courseid);
		$contentfile='Courseslist.php';
		include VIEWS.DS.'template.php';
	}
	public function modifyone($courseid){
		$c=new CoursesConnexion();
		$contentfile='Coursesmodify.php';
		include VIEWS.DS.'template.php';
	}
	public function onmodifycourse($courseid){
		$c=new CoursesConnexion();
		$c->modifyCourse($courseid);
		$contentfile='Courseslist.php';
		include VIEWS.DS.'template.php';
	}
}

class CoursesController{
	public function viewpublic(){
		$hv= new CoursesView();
		$hv->listall();
	}
	public function viewdetail($id){
		$hv= new CoursesView();
		$hv->listone($id);
	}
	public function viewcreate(){
		$hv= new CoursesView();
		$hv->createcourse();
	}
	public function oncreate(){
		$hv= new CoursesView();
		$hv->oncreatecourse();
	}
	public function delete($id){
		$hv= new CoursesView();
		$hv->deleteone($id);
	}
	public function modify($id){
		$hv= new CoursesView();
		$hv->modifyone($id);
	}
	public function onmodify($id){
		$hv= new CoursesView();
		$hv->onmodifycourse($id);
	}
}


?>