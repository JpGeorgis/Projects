 
 <div class="table-wrapper">
 <h1>Courses List</h1>
 <br>
<table class="fl-table">
    <thead>
        <tr class="row">
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Teacher</th>
            <th scope="col">Classes</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $res=$c->listallCourses();
            while($row =$res->fetch()){
                $array = unserialize($row['classes']);
                $array_together = implode(", ", $array);
              echo'
              <tr class="row">
                  <th scope ="row">'.$row['course_id'].'</th>
                  <td>'.$row['course_name'].'</td>
                  <td>'.$row['name'].'</td>
                  <td>'.$array_together.'</td>

                  <td>';
                  echo '<a class="viewbtn" href="'.URL_BASE.'/index.php?action=CoursesController&method=viewdetail&id='.$row['course_id'].'">+</a>';
                  echo'<a class="delButton" href="'.URL_BASE.'/index.php?action=CoursesController&method=delete&id='.$row['course_id'].'">Delete</a>
                  <a class="modifButton" href="'.URL_BASE.'/index.php?action=CoursesController&method=modify&id='.$row['course_id'].'">Modify</a></td>
              </tr>';
      
            }
        ?>
    </tbody>
</table>
<a class='viewbtn' href='<?php echo URL_BASE.'/index.php?action=CoursesController&method=viewcreate';?>' >Add Course</a>
</div>