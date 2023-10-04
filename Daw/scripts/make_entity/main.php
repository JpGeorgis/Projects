<?php

namespace MakeEntity;

use MakeEntity\types\DBType;

require("types/DBType.php");
require("Entity.php");
require("Repository.php");
require("SQLTranslator.php");


// clear state cache -> usefully when we're working with file
clearstatcache();

// Script
echo("Entity tools maker".PHP_EOL);
$entityName = readline("Entity name: ");

$properties = array();
$propertiesNames = array();
$end = false;

// Create property
while(($name = readline("Property name (blank to stop): ")) && !$end)
{
    if(empty($name)) {
        $end = true;
        continue;
    }

    if(strtolower($name) === "id") {
        echo("ID is reserved name.".PHP_EOL);
        continue;
    }

    if(in_array($name, $propertiesNames)) {
        echo("Property $name already in properties.".PHP_EOL);
        continue;
    }

    while(!($property = DBType::getType(strtolower(readline("Property type (string, int, date): "))))) {
        echo("Invalid type.". PHP_EOL);
    }

    $propertiesNames[] = $name;
    $property->setName($name);
    $properties[] = $property;
}

// Write :

$entity = new Entity($entityName, $properties);
foreach($properties as $property)
    $property->setEntityName(ucfirst($entity->getName()));
$repository = new Repository($entity);

$entity->write("models/entities/");
$repository->write("models/repositories/");


$sqlFile = new SQLTranslator($entity);
$sqlFile->export("");
print("Bye !");
