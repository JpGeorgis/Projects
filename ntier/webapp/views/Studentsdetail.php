   
<?php if (isset($studentid)) {
    $res=$c->listoneStudent($studentid);
    $row =$res->fetch();



    echo '<h1>'.$row['name'].'</h1>';
    echo '<p>Email : '.$row['email'].'</p>';
    echo '<p>Class : <a href="'; echo URL_BASE.'/index.php?action=ClassesController&method=viewdetail&id='.$row['class'].'';
    echo '">'.$row['Designation'].'</a></p>';


    
}

?>