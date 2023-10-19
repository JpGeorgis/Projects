<div class="table-wrapper">
    <table class="fl-table">
            <tr class="row">
                <td class="table_t1">Name :</td>
                <td class="table_t2"><?php echo $_SESSION['name']; ?></td>
                <td></td>
            </tr>
            <tr class="row">
                <td class="table_t1">Email :</td>
                <td class="table_t2" id="email"><?php echo $_SESSION['email']; ?></td>
                <td><a href="<?php echo URL_BASE.'/index.php?action=AccountController&display=first';?>" class="modifButton" id="edit_email">Modify</a></td>
            </tr>
            <?php if($_GET['display']==='first'){
                        echo '<tr class="row"><form action="index.php?action=AccountController&display=" method="POST">
                                <td class="table_t1">Your new email address: </td>
                                <td> <input class="boxreponses" type="email" name="new_email" id="new_email"/></td>
                                <td><button class="modifButton" id="submitEmail" name="submitEmail">Submit</button></td>
                            </form></tr>';}
            ?>
            <tr class="row">
                <td class="table_t1">Password :</td>
                <td class="table_t2">***********</td>
                <td><a href="<?php echo URL_BASE.'/index.php?action=AccountController&display=second';?>" class="modifButton" id="edit_password">Modify</a></td>
            </tr>
            <?php if($_GET['display']==='second'){

                    echo '<tr class="row"><form action="index.php?action=AccountController&display=" method="POST">
                                <td class="table_t1">Former password : </td>
                                <td><input class="boxreponses" type="password" name="old_pass" id="old_password"/></td>
                                <td></td>
                            </tr>
                            <tr class="row">
                                <td class="table_t1">New password : </td>
                                <td><input class="boxreponses" type="password" name="new_pass" id="new_password"/></td>
                                <td></td>
                            </tr>
                            <tr class="row">
                                <td class="table_t1">Confirmation : </td>
                                <td><input class="boxreponses" type="password" name="new_confirm" id="new_confirm"/></td>
                                <td><button class="modifButton" id="submitPass" name="submitPass">Submit</button></td>
                            </form></tr>';}
            ?>
            <tr class="row">
                <td class="table_t1">Account type :</td>
                <td class="table_t2"><?php echo $_SESSION['type']; ?></td>
                <td></td>
            </tr>
            <tr class="row"><form action="index.php?action=AccountController&display=" method="POST">
                <td class="table_t1">Delete Account :</td>
                <td></td>
                <td>
                    <a href="<?php echo URL_BASE.'/index.php?action=AccountController&display=third';?>" id="delete-button" class="delButton">Delete</a>
                    <?php if($_GET['display']==='third')  {
                                echo '<button id="submitDel" name="submitDel" class="delButton">Confirm</button>';}?>
                </td>
            </form></tr>

    </table>
</div>