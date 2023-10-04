<?php

namespace MakeEntity\types;

require_once("DBDate.php");
require_once("DBString.php");
require_once("DBInt.php");

class DBType
{
    public static function getType(string $name): bool|DBAbstractType
    {
        return match ($name) {
            "string"|"str" => new DBString(),
            "int" => new DBInt(),
            "date" => new DBDate(),
            default => false,
        };
    }
}