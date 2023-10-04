<?php

namespace MakeEntity;

class Entity
{
    private string $name;
    private array $properties;

    public function __construct(string $name, array $properties)
    {
        $this->properties = $properties;
        $this->name = $name;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getName(): string
    {
        $words = explode("_", $this->name);
        $name = "";
        foreach ($words as $word)
            $name .= ucfirst($word);
        return $name;
    }

    public function toSqlName(): string
    {
        return $this->name;
    }

    private function getMethodsString(): string {
        $methods = '';
        foreach ($this->properties as $property) {
            $methods .= $property->getGetter();
            $methods .= $property->getSetter();
        }
        return $methods;
    }

    private function getDefinitionsString(): string {
        $declares = '';
        foreach ($this->properties as $property) {
            $declares .= "\t".$property->getDefinition().PHP_EOL;
        }
        return $declares;
    }

    private function getDeleteQuery(): string {
        return "DELETE FROM ".$this->toSqlName()." WHERE id=?";
    }

    private function getCreateQuery(): string {
        $query = "INSERT INTO " . $this->toSqlName() . " (";
        foreach ($this->properties as $p)
            $query .= $p->getSqlName().", ";
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        $query .= str_repeat("?, ", count($this->properties));
        $query = substr($query, 0, -2);
        $query .= ")";
        return $query;
    }

    private function getCreateParams(): string {
        $query = "";
        foreach ($this->properties as $p)
            $query .= $p->toSql().", ";
        return substr($query, 0, -2);
    }

    private function getUpdateQuery(): string {
        $query = "UPDATE ".$this->toSqlName()." SET ";
        foreach ($this->properties as $p)
            $query .= $p->getSqlName()."=?, ";
        $query = substr($query, 0, -2);
        $query .= " WHERE id=?";
        return $query;
    }

    private function getUpdateParams(): string {
        return $this->getCreateParams().', $this->getId()';
    }

    public function write(string $path): void {
        $template = require("patterns/entityPattern.php");

        $replace = array(
            "[ENTITY_NAME]" => $this->getName(),
            "[ENTITY METHODS]" => $this->getMethodsString(),
            "[ENTITY_PROPERTIES]" => $this->getDefinitionsString(),
            "[QUERY_DELETE]" => $this->getDeleteQuery(),
            "[QUERY_CREATE]" => $this->getCreateQuery(),
            "[CREATE_PARAMS]" => $this->getCreateParams(),
            "[QUERY_UPDATE]" => $this->getUpdateQuery(),
            "[UPDATE_PARAMS]" => $this->getUpdateParams()
        );

        foreach($replace as $key => $value)
            $template = str_replace($key, $value, $template);
        $file = fopen($path.$this->getName().".php", "w") or die("Unable to open file!");
        fwrite($file, $template);
        fclose($file);
    }

}