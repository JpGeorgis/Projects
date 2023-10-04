<?php

namespace MakeEntity;

use MakeEntity\types\DBDate;
use MakeEntity\types\DBInt;
use MakeEntity\types\DBString;

class SQLTranslator
{

    private Entity $entity;

    function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    function export(string $path): void
    {
        $template = require("patterns/createPattern.php");

        $properties = "";

        foreach($this->entity->getProperties() as $property) {
            $properties.=$property->createSql();
        }

        $replace = array(
            "[ENTITY_NAME]" => $this->entity->toSqlName(),
            "[PROPERTIES]" => $properties
        );

        foreach($replace as $key => $value)
            $template = str_replace($key, $value, $template);
        $file = fopen($path.$this->entity->getName().".sql", "w") or die("Unable to open file!");

        fwrite($file, $template);
        fclose($file);
    }

}