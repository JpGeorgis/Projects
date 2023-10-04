<?php

namespace MakeEntity\types;

require_once("DBAbstractType.php");


class DBInt extends DBAbstractType
{

    public function getGetter(): string
    {
        $pattern = require("patterns/getterPattern.php");
        $replace = array(
            "[TYPE]" => "int",
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
            "[TYPE]" => "int",
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
        return "private int $".$this->getName().";";
    }

    public function getInit(): string
    {
        return '$this->'.$this->getName()." = 0;";
    }

    public function toSql(): string
    {
        return '$this->get'.ucfirst($this->getName()).'()';
    }

    public function createSql(): string
    {
        return "\t`".$this->getSqlName().'` int(11) NOT NULL,'."\n";
    }

    public function getInitLines(): string
    {
        return '$entity->set'.ucfirst($this->getName()).'(intval($item["'.$this->getSqlName().'"]));'.PHP_EOL;
    }

}