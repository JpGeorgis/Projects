<?php

namespace MakeEntity\types;

require_once("DBAbstractType.php");


class DBString extends DBAbstractType
{

    public function getGetter(): string
    {
        $pattern = require("patterns/getterPattern.php");
        $replace = array(
            "[TYPE]" => "string",
            "[MAJ_NAME]" => ucfirst($this->getName()),
            "[NAME]" => $this->getName()
        );

        foreach($replace as $key => $value)
            $pattern = str_replace($key, $value, $pattern);

        return $pattern;
    }

    public function getSetter(): string
    {
        $pattern = require("patterns/setterPattern.php");
        $replace = array(
            "[TYPE]" => "string",
            "[MAJ_NAME]" => ucfirst($this->getName()),
            "[NAME]" => $this->getName(),
            "[ENTITY_NAME]" => $this->getEntityName()
        );

        foreach($replace as $key => $value)
            $pattern = str_replace($key, $value, $pattern);

        return $pattern;
    }

    public function getDefinition(): string
    {
        return "private string $".$this->getName().";";
    }

    public function getInit(): string
    {
        return '$this->'.$this->getName().' = "";';
    }

    public function toSql(): string
    {
        return '$this->get'.ucfirst($this->getName()).'()';
    }
    public function createSql(): string
    {
        return "\t`".$this->getSqlName().'` varchar(255) NOT NULL,'."\n";
    }
}