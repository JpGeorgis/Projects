<?php

class StudentsConnexion
{
  public function listallStudents(){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT id, name, email, Designation FROM user
            INNER JOIN class ON class_id= class
            WHERE type = 'student'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }

  public function listoneStudent($studentid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT name, email, Designation,class FROM user  
            INNER JOIN class ON class_id= class
            WHERE id='{$studentid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function deleteStudent($studentid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "DELETE FROM user  
            WHERE id='{$studentid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function modifyStudent($studentid){
    if(isset($_POST['submit'])){
      $name = $_POST['new_name'];
      $mail = $_POST['new_email'];
      $class = $_POST['new_class'];
    }
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "UPDATE user  
            SET name='$name', email='$mail',class='$class'
            WHERE id='{$studentid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }
  public function createStudent($id){
    if(isset($_POST['submit'])){
      $name = $_POST['new_name'];
      $mail = $_POST['new_email'];
      $pass = $_POST['new_pass'];
      $pass=password_hash($pass,PASSWORD_DEFAULT);
    }
    
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "INSERT INTO user (email, name, type,password,class) VALUES ('$mail', '$name', 'student','$pass','$id')";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }
}

?>