<?php if (isset($classid)) {
    $res=$c->listoneClass($classid);
    $row =$res->fetch();



    echo '<div class="table-wrapper"><h1>'.$row['Designation'].'</h1>';
    echo '<p class="details" >Number of Students : '.$row['Num'].'</p><h1>Students List</h1>';


    $res=$c->getStudents($classid);
    echo "
        <table class='fl-table'> 
        <thead>
            <tr class='row'>
                <th scope='col'>#</th>
                <th scope='col'>Name</th>
                <th scope='col'>Email</th>
                <th scope='col'></th>
            </tr>
        </thead>
        <tbody>";
        while($row =$res->fetch()){
           
            echo '<tr class="row">
                    <th scope="row">'.$row['id'].'</th>';
            echo '<td>'.$row['name'].'</td>';
            echo '<td>'.$row['email'].'</td>';
            echo "<td><a class='viewbtn' href="; echo URL_BASE.'/index.php?action=StudentsController&method=viewdetail&id='.$row['id'].''; 
            echo ">+</a></td>";
            echo"</tr>";
        }
        echo "</tbody>
            </table></div>";
    
}

?>