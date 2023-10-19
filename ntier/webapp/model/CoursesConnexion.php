<?php

class CoursesConnexion
{
  public function listallCourses(){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT course_id, course_name, name, classes FROM courses
            INNER JOIN user ON teacher = id";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }

  public function listoneCourse($courseid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT course_id, course_name, course_description, name, email, id, classes FROM courses 
            INNER JOIN user ON teacher = id 
            WHERE course_id='{$courseid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function getTeachers(){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT name FROM user
            WHERE type = 'teacher'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }

  public function getClasses(){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT Designation FROM class";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }
  public function getTeacher($n){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT id FROM user
            WHERE name = '$n'";
    $result = $db->prepare($sql);
    $result->execute();
    $row=$result->fetch();
    return $row['id'];
  }
  public function createCourse(){
    if(isset($_POST['submit'])){
      $name = $_POST['courseName'];
      $desc = $_POST['courseDescription'];
      $class=1;
      $tab = array();
      while(isset($_POST[$class])){
        $tab[$class]=$_POST[$class];
        $class++;
      }
      $serialize = serialize($tab);
      $a=$_POST['teachers'];
      $teacher=$this->getTeacher($a);
    }
    
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "INSERT INTO courses (course_name, course_description, teacher, classes) VALUES ('$name', '$desc', '$teacher', '$serialize')";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }
  
  public function deleteCourse($courseid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "DELETE FROM courses  
            WHERE course_id='{$courseid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function modifyCourse($courseid){
    if(isset($_POST['submit'])){
      $name = $_POST['new_name'];
      $desc = $_POST['new_description'];
    }
    $class=1;
      $tab = array();
      while(isset($_POST[$class])){
        $tab[$class]=$_POST[$class];
        $class++;
      }
      $serialize = serialize($tab);
    $a=$_POST['teachers'];
    $teacher=$this->getTeacher($a);
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "UPDATE courses  
            SET course_name='$name', course_description='$desc',teacher='$teacher',classes='$serialize'
            WHERE course_id='{$courseid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }
}

?>
