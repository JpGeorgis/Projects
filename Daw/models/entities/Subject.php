<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");

/**
 * Objet modélisant un sujet de classe.
 */
class Subject extends Entity
{
    /**
     * @var string Nom du sujet
     */
	private string $name;
    /**
     * @var string Description du sujet
     */
	private string $description;
    /**
     * @var string Vidéo de prévisualisation du sujet
     */
	private string $video;


    /**
     * Retourne le nom du sujet
     * @return string Nom du sujet
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Définit le nom du sujet
     * @param string $name Nom du sujet
     * @return $this Instance courante (fluent)
     */
    public function setName(string $name): Subject
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Retourne la description du sujet
     * @return string Description du sujet
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Définit la description du sujet
     * @param string $description Description du sujet
     * @return $this Instance courante (fluent)
     */
    public function setDescription(string $description): Subject
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Retourne la vidéo de prévisualisation du sujet.
     * @return string Vidéo de prévisualisation
     */
    public function getVideo(): string
    {
        return $this->video;
    }

    /**
     * Définit la vidéo de prévisualisation du sujet
     * @param string $video Vidéo de prévisualisation
     * @return $this Instance courante (fluent)
     */
    public function setVideo(string $video): Subject
    {
        $this->video = $video;
        return $this;
    }

    /**
     * Décrit l'objet en SQL selon le mode voulu
     * @param int $mode Mode (UPDATE, CREATE, DELETE)
     * @return Query Requête SQL demandée
     */
    public function toSql(int $mode): Query
    {
        return match ($mode) {
            ACTION_TYPE::DELETE => new Query("DELETE FROM subject WHERE id=?", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "INSERT INTO subject (name, description, video) VALUES (?, ?, ?)",
                [
                    $this->getName(), $this->getDescription(), $this->getVideo()
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "UPDATE subject SET name=?, description=?, video=? WHERE id=?",
                [
                    $this->getName(), $this->getDescription(), $this->getVideo(), $this->getId()
                ]
            ),

            default => new Query("") // Default: empty query
        };
    }

    /**
     * Description des attributs de l'instance sous forme de tableau
     * @return array Tableau de données décrivant les attributs de la classe
     */
    public function getAttributes(): array {
        return get_object_vars($this);
    }
}
