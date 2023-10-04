<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");


/**
 * Cours en base de données
 */
class Classes extends Entity
{
    /**
     * @var string Nom du cours
     */
	private string $name;
    /**
     * @var int Identifiant du créateur du cours
     */
	private int $ownerId;
    /**
     * @var int Difficulté du cours (1, 2, 3)
     */
	private int $difficulty;
    /**
     * @var DateTime Date de création du cours
     */
	private DateTime $creation;
    /**
     * @var string Lien vers l'image de prévisualisation du cours
     */
	private string $picture;
    /**
     * @var int Identifiant du sujet du cours
     */
	private int $subjectId;
    /**
     * @var string Description textuelle du cours
     */
    private string $description;

    /**
     * Renvoie la description textuelle du cours
     * @return string Description textuelle du cours
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Définit la description textuelle du cours
     * @param string $description Description textuelle du cours
     * @return $this Instance courante (fluent)
     */
    public function setDescription(string $description): Classes
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Retourne le nom du cours
     * @return string Nom du cours
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Définit le nom du cours
     * @param string $name Nom du cours
     * @return $this Instance courante (fluent)
     */
    public function setName(string $name): Classes
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Renvoie l'identifiant du créateur du cours
     * @return int Identifiant du créateur du cours
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * Définit l'identifiant du créateur du cours
     * @param int $ownerId Identifiant du créateur du cours
     * @return $this Instance courante (fluent)
     */
    public function setOwnerId(int $ownerId): Classes
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    /**
     * Renvoie la difficulté du cours
     * @return int Difficulté du cours (1, 2, 3)
     */
    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    /**
     * Définit la difficulté du cours
     * @param int $difficulty Difficulté du cours
     * @return $this Instance courante (fluent)
     */
    public function setDifficulty(int $difficulty): Classes
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    /**
     * Renvoie la date de création du cours
     * @return DateTime Date de création de cours
     */
    public function getCreation(): DateTime
    {
        return $this->creation;
    }

    /**
     * Définit la date de création du cours
     * @param DateTime $creation Date de création du cours
     * @return $this Instance courante (fluent)
     */
    public function setCreation(DateTime $creation): Classes
    {
        $this->creation = $creation;
        return $this;
    }

    /**
     * Renvoie le lien vers l'image de prévisualisation du cours
     * @return string Lien vers l'image de prévisualisation du cours
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * Définit le lien vers l'image de prévisualisation du cours
     * @param string $picture Lien vers l'image de prévisualisation du cours
     * @return $this Instance courante (fluent)
     */
    public function setPicture(string $picture): Classes
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * Renvoie l'index du sujet concerné par le cours
     * @return int Index du sujet concerné par le cours
     */
    public function getSubjectId(): int
    {
        return $this->subjectId;
    }

    /**
     * Définit l'index du sujet concerné par le cours
     * @param int $subjectId Index du sujet concerné par le cours
     * @return $this Instance courante (fluent)
     */
    public function setSubjectId(int $subjectId): Classes
    {
        $this->subjectId = $subjectId;
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
            ACTION_TYPE::DELETE => new Query("DELETE FROM classes WHERE id=?", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "INSERT INTO classes (name, owner_id, difficulty, creation, picture, subject_id, description) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    $this->getName(), $this->getOwnerId(), $this->getDifficulty(), $this->getCreation()->format("Y-m-d H:i:s"),
                    $this->getPicture(), $this->getSubjectId(), $this->getDescription()
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "UPDATE classes SET name=?, owner_id=?, difficulty=?, creation=?, picture=?, subject_id=?, description=? WHERE id=?",
                [
                    $this->getName(), $this->getOwnerId(), $this->getDifficulty(), $this->getCreation()->format("Y-m-d H:i:s"),
                    $this->getPicture(), $this->getSubjectId(),$this->getDescription(), $this->getId()
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
