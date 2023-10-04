<?php
if (session_status() === PHP_SESSION_NONE){session_start();} 
if (isset($_GET['action']) && $_GET['action'] !== '')
{
    if(file_exists('controller/'.$_GET['action'].'.php'))
    {
        require('controller/'.$_GET['action'].'.php');
        $vc=new $_GET['action']();
        $vc->viewpublic();
    }
    else
        http_response_code(404);
}
else 
{
    require('controller/HomeController.php');
    $hc = new HomeController();
    if(isset($_SESSION['name'])){
        $hc->viewconnected();
    }
    else{
        $hc->viewpublic();
    }
}


?>
