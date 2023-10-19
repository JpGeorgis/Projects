
<?php if (isset($classid)) {
    $res=$c->listoneClass($classid);
    $row =$res->fetch();



    echo '<div class="modify-wrapper"><h1>Modify '.$row['Designation'].'</h1>';
    echo '<form action="index.php?action=ClassesController&method=onmodify&id='.$classid.'" method="POST" class="modifyform">
            <p>Name of Class : </p>
            <input class="boxreponses" type="text" name="new_name" id="new_name"/>
            <p class="details" >Number of Students : '.$row['Num'].'</p>
            <button class="modifButton" name="submit">Modify</button>
        </form></div>';
    echo '<div class="table-wrapper"><h1>Students List</h1>';

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
            echo ">+</a><a class='delButton' href='".URL_BASE.'/index.php?action=StudentsController&method=delete&id='.$row['id']."&id2=".$classid."'>Delete</a>
            <a class='modifButton' href='".URL_BASE.'/index.php?action=StudentsController&method=modify&id='.$row['id']."&id2=".$classid."'>Modify</a></td>";
            echo"</tr>";
        }
        echo "</tbody>
            </table>
            <a class='viewbtn' href="; echo URL_BASE.'/index.php?action=StudentsController&method=viewcreate&id='.$classid.''; echo ">Add Students</a></div>";
    
}

?>