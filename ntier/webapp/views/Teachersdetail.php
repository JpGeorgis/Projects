   
<?php if (isset($teacherid)) {
    $res=$c->listoneTeacher($teacherid);
    $row =$res->fetch();



    echo '<div class="table-wrapper"><h1>'.$row['name'].'</h1>';
    echo '<p class="details">'.$row['email'].'</p><h1>Courses List</h1><br>';


    $res=$c->getCourses($teacherid);
    echo " <table  class='fl-table'> 
        <thead>
            <tr class='row'>
                <th scope='col'>#</th>
                <th scope='col'>Name</th>
                <th scope='col'>Description</th>
                <th scope='col'>Classes</th>
                <th scope='col'></th>
            </tr>
        </thead>
        <tbody>";
        while($row =$res->fetch()){
            $array = unserialize($row['classes']);
                $array_together = implode(", ", $array);
            echo '<tr class="row">
                    <th scope="row">'.$row['course_id'].'</th>';
            echo '<td>'.$row['course_name'].'</td>';
            echo '<td>'.$row['course_description'].'</td>';
            echo '<td>'.$array_together.'</td>';
            echo "<td><a class='viewbtn' href="; echo URL_BASE.'/index.php?action=CoursesController&method=viewdetail&id='.$row['course_id'].''; 
            echo ">+</a></td>";
            echo"</tr>";
        }
        echo "</tbody>
            </table></div>";
    
}

?>