   
<?php if (isset($courseid)) {
    $res=$c->listoneCourse($courseid);
    $row =$res->fetch();



    echo '<h1>'.$row['course_name'].'</h1>';
    echo '<p>'.$row['course_description'].'</p>';
    echo "<p>Teacher : <a href=";echo URL_BASE.'/index.php?action=TeachersController&method=viewdetail&id='.$row['id'].''; 
    echo '> '.$row['name'].'</a></p>';
    echo '<p>Email : '.$row['email'].'</p>';
}

?>