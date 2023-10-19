<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('VIEWS', ROOT.DS.'views');
define('URL_BASE', 'http://'.$_SERVER['SERVER_NAME'].(($_SERVER['SERVER_PORT'] == '80')?'':':'.$_SERVER['SERVER_PORT']).((dirname($_SERVER['SCRIPT_NAME']) == DS)?'':dirname($_SERVER['SCRIPT_NAME'])) );
?>