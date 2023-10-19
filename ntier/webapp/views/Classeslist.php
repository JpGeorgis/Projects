
<div class="table-wrapper">
<h1>Classes List</h1>
<br>
<table  class="fl-table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Designation</th>
            <th scope="col">Number of Students</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $res=$c->listallClasses();
            while($row =$res->fetch()){
              echo'
              <tr class="row">
              <th scope ="row">'.$row['class_id'].'</th>
              <td>'.$row['Designation'].'</td>
              <td>'.$row['Num'].'</td><td>';
              echo '<a class="viewbtn" href="'.URL_BASE.'/index.php?action=ClassesController&method=viewdetail&id='.$row['class_id'].'">+</a>';
              echo'<a class="delButton" href="'.URL_BASE.'/index.php?action=ClassesController&method=delete&id='.$row['class_id'].'">Delete</a>
              <a class="modifButton" href="'.URL_BASE.'/index.php?action=ClassesController&method=modify&id='.$row['class_id'].'">Modify</a></td>
              </tr>';
            }
        ?>
    </tbody>
</table>
<a class='viewbtn' href='<?php echo URL_BASE.'/index.php?action=ClassesController&method=viewcreate';?>' >Add Classe</a>
</div>