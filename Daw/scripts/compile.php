<?php
/**
 * Archiver un fichier dans un .phar
 *
 * DOCS:
 *  -> https://www.php.net/manual/en/class.pharfileinfo.php
 *  -> https://www.php.net/manual/en/function.readline
 */
$workspace = readline("Folder to compile: "); // Folder

$entry = "main.php"; // Default entry
$e = readline("Entry file (main.php): "); // Main entry
if(!empty($e)) // if no entry
    $entry = $e; // set main.php as default name

echo(PHP_EOL); // End of line

try
{
    $pharFile = $workspace.'.phar'; // new pharFile name

    // clean up
    if (file_exists($pharFile)) // if file already exists
        unlink($pharFile); // Delete it

    if (file_exists($pharFile . '.gz')) // Delete .gz (backup)
        unlink($pharFile . '.gz');

    // create phar
    $phar = new Phar($pharFile); // Create a new phar object
    $phar->startBuffering(); // Start put int phar
    $defaultStub = $phar->createDefaultStub($entry); // Set default entry
    $phar->buildFromDirectory(__DIR__ . '/'.$workspace); // Take all file in directory $workspace

    $stub = "#!/usr/bin/env php \n" . $defaultStub; // php loader path

    $phar->setStub($stub); // define php loader
    $phar->stopBuffering(); // Stop put in
    $phar->compressFiles(Phar::GZ); // Compress all file into .phar (gz compression)
    chmod(__DIR__ . '/'.$workspace.'.phar', 0770); // set permission to new phar

    echo "$pharFile successfully created" . PHP_EOL; // confirmation
}
catch (Exception $e) // if exception
{
    echo $e->getMessage(); // print it
}
