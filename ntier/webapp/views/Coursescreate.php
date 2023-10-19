<br><br>
<div class="createcourse">
<form action="index.php?action=CoursesController&method=oncreate" method="POST">
	<h1 id="h1Course" >Create Course</h1>
	<input type="text" placeholder="Name" name="courseName"/>
	<input type="text" placeholder="Description" name="courseDescription"/>
	<p>Teacher : </p>
	<select name="teachers" id="listederoulante">
	    <option value="">--Please choose a teacher--</option>
	    <?php  
	    	$res=$c->getTeachers();
			while($row =$res->fetch())
	    		echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
	    ?>
	</select>
	<p>Classes : </p>
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
	<br><br><br>
	<button class="modifButton" name="submit">create</button>
</form>
</div>


