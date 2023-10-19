<?php

class ClassesConnexion
{
  public function listallClasses(){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT class_id, Designation, Num FROM class";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }

  public function listoneClass($classid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT Designation, Num FROM class  
            WHERE class_id='{$classid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function getStudents($classid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "SELECT id, name, email FROM user
            WHERE class = '$classid'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function deleteClass($classid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "DELETE FROM class  
            WHERE class_id='{$classid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }

  public function modifyClass($classid){
    if(isset($_POST['submit'])){
      $name = $_POST['new_name'];
    }
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "UPDATE class  
            SET Designation='$name'
            WHERE class_id='{$classid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result; 
  }
  public function createClass(){
    if(isset($_POST['submit'])){
      $name = $_POST['new_name'];
    }
    
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "INSERT INTO class (Designation, num) VALUES ('$name', '0')";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }
  public function addStudent($classid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "UPDATE class  
            SET num=num+1
            WHERE class_id='{$classid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }
  public function delStudent($classid){
    $db = new PDO('mysql:host=localhost;dbname=loginsystem','root','');
    $sql = "UPDATE class  
            SET num=num-1
            WHERE class_id='{$classid}'";
    $result = $db->prepare($sql);
    $result->execute();
    return $result;
  }
}

?>