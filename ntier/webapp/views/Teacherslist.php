 
 <div class='table-wrapper'>
 <h1>Teachers List</h1>
 <br>
<table class='fl-table'>
    <thead>
        <tr class='row'>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $res=$c->listallTeachers();
            while($row =$res->fetch()){
              echo'
              <tr class="row">
              <th scope ="row">'.$row['id'].'</th>
              <td>'.$row['name'].'</td>
              <td>'.$row['email'].'</td><td>';
              echo '<a class="viewbtn" href="'.URL_BASE.'/index.php?action=TeachersController&method=viewdetail&id='.$row['id'].'">+</a>
              <a class="delButton" href="'.URL_BASE.'/index.php?action=TeachersController&method=delete&id='.$row['id'].'">Delete</a>
              <a class="modifButton" href="'.URL_BASE.'/index.php?action=TeachersController&method=modify&id='.$row['id'].'">Modify</a></td>
              </tr>';
      
            }
        ?>
    </tbody>
</table>
<a class='viewbtn' href='<?php echo URL_BASE.'/index.php?action=TeachersController&method=viewcreate';?>' >Add Teacher</a>
</div>