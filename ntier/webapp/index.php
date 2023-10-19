<?php
if (session_status() === PHP_SESSION_NONE){session_start();} 
if (isset($_GET['action']) && $_GET['action'] !== '')
{
    if(file_exists('controller/'.$_GET['action'].'.php'))
    {
      
        require('controller/'.$_GET['action'].'.php');
        if (isset($_GET['method']))
        {
            $method=$_GET['method'];
        }
        else
        {
            $method='viewpublic';
        }
        if (isset($_GET['id']))
        {
            $id=$_GET['id'];
        }
        else
        {
            $id=null;
        }
        if (isset($_GET['id2']))
        {
            $id2=$_GET['id2'];
        }
        else
        {
            $id2=null;
        }
        $vc=new $_GET['action'];
        if (!is_null($id) && !is_null($id2))
            $vc->$method($id,$id2);
        else if (!is_null($id)) 
            $vc->$method($id);
        else 
            $vc->$method();

    }
    else
        http_response_code(404);
}
else 
{
    require('controller/HomeController.php');
    $hc = new HomeController();
    if(isset($_SESSION['name'])){
        if($_SESSION['type']=== 'admin'){
            $hc->viewadmin();
        }
        else{
            $hc->viewconnected();
        }
    }
    else{
        $hc->viewpublic();
    }
}


?>
