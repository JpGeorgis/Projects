<?php if (isset($teacherid)) {
    $res=$c->listoneTeacher($teacherid);
    $row =$res->fetch();



    echo '<div class="modify-wrapper"><h1>'.$row['name'].'</h1>
    <form action="index.php?action=TeachersController&method=onmodify&id='.$teacherid.'" method="POST" class="modifyform">';
    echo '<p>New Name :</p>';
    echo '<input type="text" id="new_name" name="new_name" class="boxreponses">';
    echo '<p>Former Email : '.$row['email'].'</p>';
    echo '<p>New Email :</p>';
    echo '<input type="text" id="new_email" name="new_email" class="boxreponses">';
    echo '<button class="modifButton" name="submit">Modify</button>';
    echo '</form></div>';


    
}

?>