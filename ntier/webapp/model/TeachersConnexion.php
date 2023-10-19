<?php

class TeachersConnexion
{
  public function listallTeachers(){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT id, name, email FROM user
            WHERE type = 'teacher'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }

  public function listoneTeacher($teacherid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT name, email FROM user  
            WHERE id='{$teacherid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function getCourses($teacherid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT course_id, course_name, course_description, classes FROM courses
            WHERE teacher = '$teacherid'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 

  }
  public function deleteTeacher($teacherid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "DELETE FROM user  
            WHERE id='{$teacherid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function modifyTeacher($teacherid){
    if(isset($_POST['submit'])){
      $name = $_POST['new_name'];
      $mail = $_POST['new_email'];
    }
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "UPDATE user  
            SET name='$name', email='$mail'
            WHERE id='{$teacherid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function createTeacher(){
    if(isset($_POST['submit'])){
      $name = $_POST['new_name'];
      $mail = $_POST['new_email'];
      $pass = $_POST['new_pass'];
      $pass=password_hash($pass,PASSWORD_DEFAULT);
    }
    
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "INSERT INTO user (email, name, type,password) VALUES ('$mail', '$name', 'teacher','$pass')";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }
}

?>