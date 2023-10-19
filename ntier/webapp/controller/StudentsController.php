<?php

require '_defines.php';
require_once('model/StudentsConnexion.php');
require_once('model/ClassesConnexion.php');
class StudentsView
{
	
	public function listone($studentid){
		$c=new StudentsConnexion();
		$contentfile='Studentsdetail.php';
		include VIEWS.DS.'template.php';
	}

	public function deleteone($studentid,$classid){
		$c=new StudentsConnexion();
		$c->deleteStudent($studentid);
		$c=new ClassesConnexion();
		$c->delStudent($classid);
		$c->listoneClass($classid);
		$contentfile='Classesmodify.php';
		include VIEWS.DS.'template.php';
	}


	public function modifyone($studentid,$classid){
		$c=new StudentsConnexion();
		$d=new ClassesConnexion();
		$contentfile='Studentsmodify.php';
		include VIEWS.DS.'template.php';
	}
	public function onmodifystudent($studentid,$classid){
		$c=new StudentsConnexion();
		$c->modifyStudent($studentid);
		$c=new ClassesConnexion();
		$c->listoneClass($classid);
		$contentfile='Classesmodify.php';
		include VIEWS.DS.'template.php';
	}

	public function createstudent($id){
		$c=new StudentsConnexion($id);
		$contentfile='Studentsadd.php';
		include VIEWS.DS.'template.php';
	}
	public function oncreatestudent($id){
		$c=new StudentsConnexion($id);
		$classid=$id;
		$c->createStudent($classid);
		$c=new ClassesConnexion();
		$c->addStudent($classid);
		$contentfile='Classesmodify.php';
		include VIEWS.DS.'template.php';
	}
}

class StudentsController{

	public function viewdetail($id){
		$hv= new StudentsView();
		$hv->listone($id);
	}
	public function delete($id,$id2){
		$hv= new StudentsView();
		$hv->deleteone($id,$id2);
	}
	public function modify($id,$id2){
		$hv= new StudentsView();
		$hv->modifyone($id,$id2);
	}
	public function onmodify($id,$id2){
		$hv= new StudentsView();
		$hv->onmodifystudent($id,$id2);
	}
	public function viewcreate($id){
		$hv= new StudentsView();
		$hv->createstudent($id);
	}
	public function oncreate($id){
		$hv= new StudentsView();
		$hv->oncreatestudent($id);
	}
}


?>