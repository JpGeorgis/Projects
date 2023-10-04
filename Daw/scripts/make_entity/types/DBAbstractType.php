<?php

namespace MakeEntity\types;

abstract class DBAbstractType
{
    private string $name;
    private string $entityName;

    public function getName(): string
    {
        $words = explode("_", $this->name);
        $name = "";
        foreach ($words as $word)
                $name .= ucfirst($word);
        return lcfirst($name);
    }

    public function getSqlName(): string
    {
        return $this->name;
    }

    public function setName(string $name): DBAbstractType
    {
        $this->name = $name;
        return $this;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function setEntityName(string $entityName): DBAbstractType
    {
        $this->entityName = $entityName;
        return $this;
    }

    public abstract function getGetter(): string;
    public abstract function getSetter(): string;
    public abstract function getDefinition(): string;
    public abstract function getInit(): string;
    public abstract function toSql(): string;
    public abstract function createSql(): string;

    public function getInitLines(): string
    {
        return '$entity->set'.ucfirst($this->getName()).'($item["'.$this->getSqlName().'"]);'.PHP_EOL;
    }

}