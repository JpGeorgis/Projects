<?php

namespace MakeEntity\types;

require_once("DBAbstractType.php");

class DBDate extends DBAbstractType
{

    public function getGetter(): string
    {
        $pattern = require("patterns/getterPattern.php");
        $replace = array(
            "[TYPE]" => "DateTime",
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
            "[TYPE]" => "DateTime",
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
        return "private DateTime $".$this->getName().";";
    }

    public function getInit(): string
    {
        return '$this->'.$this->getName()." = new DateTime();";
    }

    public function toSql(): string
    {
        return '$this->get'.ucfirst($this->getName()).'()->format("Y-m-d H:i:s")';
    }

    public function getInitLines(): string
    {
        $lines = '$rawDate = $item["'.$this->getSqlName().'"];'.PHP_EOL;
        $lines .= "\t\t".'$date = DateTime::createFromFormat("Y-m-d H:i:s", $rawDate'.');'.PHP_EOL;
        $lines .= "\t\t".'$entity->set'.ucfirst($this->getName()).'($date);'.PHP_EOL;
        return $lines;
    }

    public function createSql(): string
    {
        return "\t`".$this->getSqlName().'` datetime NOT NULL,'."\n";
    }

}