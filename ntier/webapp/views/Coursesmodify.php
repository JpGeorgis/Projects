<?php if (isset($courseid)) {
    $res=$c->listoneCourse($courseid);
    $row =$res->fetch();



    echo '<div class="modify-wrapper"><h1>'.$row['course_name'].'</h1>
    <form action="index.php?action=CoursesController&method=onmodify&id='.$courseid.'" method="POST" class="modifyform">';
    echo '<p>New Name :</p>';
    echo '<input type="text" id="new_name" name="new_name" class="boxreponses">';
    echo '<p>Former Description :<br> '.$row['course_description'].'</p>';
    echo '<p>New Description :</p>';
    echo '<input type="text" id="new_description" name="new_description" class="boxreponses">';
    echo "<p>Teacher : <a href=";echo URL_BASE.'/index.php?action=TeachersController&method=viewdetail&id='.$row['id'].''; 
    echo '> '.$row['name'].'</a></p>';
    ?>
    <select name="teachers" id="listederoulante">
	    <option value="">--Please choose a teacher--</option>
	    <?php  
	    	$res=$c->getTeachers();
	    	while($row =$res->fetch())
	    	    echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
	    ?>
	</select>
    <?php
    $res=$c->listoneCourse($courseid);
            
    ?>
    <p>Former Classes : <?php while($row =$res->fetch()){
                $array = unserialize($row['classes']);
                $array_together = implode(", ", $array);
                echo $array_together.' ';
            }?></p>
    
	<div class="checkboxes">
		<?php  
	    	$res=$c->getClasses();
	    	$class=1;
	    	while($row =$res->fetch()){

				echo '<div class="checkbox-wrapper-33">
					<label class="checkbox" for="'.$class.'">
						<input class="checkbox__trigger visuallyhidden" type="checkbox" value="'.$row['Designation'].'" id="'.$class.'" name="'.$class.'"/>
						<span class="checkbox__symbol">
						<svg aria-hidden="true" class="icon-checkbox" width="28px" height="28px" viewBox="0 0 28 28" version="1" xmlns="http://www.w3.org/2000/svg">
							<path d="M4 14l8 7L24 7"></path>
						</svg>
						</span>
						<p class="checkbox__textwrapper">'.$row['Designation'].'</p>
					</label>
				</div>';
				
	    		$class++;
	    	}
	    	
	    ?>
        </div>
        <br>
    <?php 
    echo '<button class="modifButton" name="submit">Modify</button>';
    echo '</form></div>';


    
}

?>
