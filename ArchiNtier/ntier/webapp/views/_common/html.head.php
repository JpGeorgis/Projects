<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Georget Joris, Widolff Brice">
<title>Archiweb - TDs - Webapp <?php if (isset($pagetitle)) echo ' - '.$pagetitle;?></title>
<link rel="stylesheet" href="<?php echo URL_BASE.'./views/_common/css/login.css';?>"/>
<link rel="stylesheet" href="<?php echo URL_BASE.'./views/_common/css/navbar.css';?>"/>
<link rel="stylesheet" href="<?php echo URL_BASE.'./views/_common/css/home.css';?>"/>
<?php if (session_status() === PHP_SESSION_NONE){session_start();} ?>