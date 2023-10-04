<?php

namespace makeController;

// clear state cache -> usefully when we're working with file
clearstatcache();

// Script
echo("Make a new controller".PHP_EOL);
$name = ucfirst(readline("Controller name: "));

$file = fopen("controllers/".$name."Controller.php", "w") or die("Unable to open file!");
$content = require('newController.php');
$content = str_replace("[NAME]", $name, $content);

fwrite($file, $content);
fclose($file);

echo("It's done !");
echo(PHP_EOL);
