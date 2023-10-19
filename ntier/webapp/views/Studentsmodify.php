
<?php if (isset($studentid)) {
    $res=$c->listoneStudent($studentid);
    $row =$res->fetch();



    echo '<div class="modify-wrapper"><h1>'.$row['name'].'</h1>
    <form action="index.php?action=StudentsController&method=onmodify&id='.$studentid.'&id2='.$classid.'" method="POST" class="modifyform">';
    echo '<p>New Name :</p>';
    echo '<input type="text" id="new_name" name="new_name" class="boxreponses">';
    echo '<p>Former Email : '.$row['email'].'</p>';
    echo '<p>New Email :</p>';
    echo '<input type="text" id="new_email" name="new_email" class="boxreponses">';
    echo '<p>Class : <a href="'; echo URL_BASE.'/index.php?action=ClassesController&method=viewdetail&id='.$row['class'].'">'.$row['Designation'].'</a></p>';
    ?>
    <select name="new_class" id="listederoulante">
        <option value="">--Please choose a class--</option>
        <?php  
            $res=$d->listallClasses();
            while($row =$res->fetch())
                echo '<option value="'.$row['class_id'].'">'.$row['Designation'].'</option>';
        ?>
    </select>
    <br>
    <?php
    echo' <button class="modifButton" name="submit">Modify</button></form></div>';


    
}

?>