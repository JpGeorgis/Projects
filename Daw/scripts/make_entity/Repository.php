<?php

namespace MakeEntity;

class Repository
{
    private Entity $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    private function getSelectQuery(): string
    {
        return "SELECT * FROM ".$this->entity->toSqlName().";";
    }

    private function getInitLine(): string
    {
        $txt = "";

        foreach($this->entity->getProperties() as $property)
            $txt .= "\t\t".$property->getInitLines();

        return $txt;
    }

    public function getNeededColumn(): string {
        $out = "";
        foreach($this->entity->getProperties() as $property)
            $out .= '"'.$property->getSqlName().'", ';

        return substr($out, 0, -2);
    }

    public function write(string $path): void
    {
        $template = require("patterns/repositoryPattern.php");

        $replace = array(
            "[REPO_NAME]" => $this->entity->getName()."Repository",
            "[ENTITY_NAME]" => $this->entity->getName(),
            "[INIT_LINE]" => $this->getInitLine(),
            "[TABLE_NAME]" => $this->entity->toSqlName(),
            "[NEEDED]" => $this->getNeededColumn()
        );

        foreach($replace as $key => $value)
            $template = str_replace($key, $value, $template);
        $file = fopen($path.ucfirst($this->entity->getName())."Repository.php", "w") or die("Unable to open file!");
        fwrite($file, $template);
        fclose($file);
    }
}