<div class="modify-wrapper"><h1>Add Student</h1>
    <form action="index.php?action=StudentsController&method=oncreate&id=<?php echo $id?>" method="POST" class="modifyform">
    <p>Name :</p>
    <input type="text" id="new_name" name="new_name" class="boxreponses">
    <p>Email :</p>
    <input type="text" id="new_email" name="new_email" class="boxreponses">
    <p>Password :</p>
    <input type="text" id="new_pass" name="new_pass" class="boxreponses">
    <button class="modifButton" name="submit">Add</button>
    </form></div>