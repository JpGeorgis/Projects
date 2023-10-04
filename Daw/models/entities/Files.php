<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");


/**
 * Modélise un fichier d'un cours
 */
class Files extends Entity
{
    /**
     * @var string Nom du fichier
     */
	private string $name;
    /**
     * @var string Chemin vers le fichier
     */
	private string $path;
    /**
     * @var DateTime Date d'ajout du fichier
     */
	private DateTime $date;
    /**
     * @var int Identifiant du cours où le fichier se trouve
     */
	private int $classId;

    /**
     * Renvoie le nom du fichier
     * @return string Nom du fichier
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Définit le nom du fichier
     * @param string $name Nom du fichier
     * @return $this Instance courante (fluent)
     */
    public function setName(string $name): Files
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Renvoie le chemin vers le fichier
     * @return string Chemin vers le fichier
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Définit le chemin vers le fichier
     * @param string $path Chemin vers le fichier
     * @return $this Instance courante (fluent)
     */
    public function setPath(string $path): Files
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Renvoie la date de création du fichier
     * @return DateTime Date d'ajout du fichier
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Définit la date de création du fichier
     * @param DateTime $date Date de création du fichier
     * @return $this Instance courante (fluent)
     */
    public function setDate(DateTime $date): Files
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Renvoie l'identifiant du cours où se trouve ce fichier
     * @return int Identifiant du cours où se trouve ce fichier
     */
    public function getClassId(): int
    {
        return $this->classId;
    }

    /**
     * Définit l'identifiant du cours où se trouve ce fichier
     * @param int $classId Identifiant du cours où se trouve ce fichier
     * @return $this Instance courante (fluent)
     */
    public function setClassId(int $classId): Files
    {
        $this->classId = $classId;
        return $this;
    }

    /**
     * Description SQL de l'objet
     * @param int $mode Mode voulu (DELETE, CREATE, UPDATE)
     * @return Query Query demandée
     */
    public function toSql(int $mode): Query
    {
        return match ($mode) {
            ACTION_TYPE::DELETE => new Query("DELETE FROM files WHERE id=?", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "INSERT INTO files (name, path, date, class_id) VALUES (?, ?, ?, ?)",
                [
                    $this->getName(), $this->getPath(), $this->getDate()->format("Y-m-d H:i:s"), $this->getClassId()
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "UPDATE files SET name=?, path=?, date=?, class_id=? WHERE id=?",
                [
                    $this->getName(), $this->getPath(), $this->getDate()->format("Y-m-d H:i:s"), $this->getClassId(), $this->getId()
                ]
            ),

            default => new Query("") // Default: empty query
        };
    }

    /**
     * Retourne la liste des attributs de l'entité
     * @return array Liste des attributs
     */
    public function getAttributes(): array {
        return get_object_vars($this);
    }
}
