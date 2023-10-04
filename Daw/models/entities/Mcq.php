<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");


/**
 * Modélise un quizz en base de données
 */
class Mcq extends Entity
{
    /**
     * @var string Chemin vers le XML du quizz
     */
	private string $path;
    /**
     * @var int Identifiant du sujet traité par le QCM
     */
	private int $subjectId;
    /**
     * @var int Identifiant du propriétaire du QCM
     */
    private int $ownerId;

    /**
     * Renvoie l'identifiant du propriétaire du QCM
     * @return int  Identifiant du propriératire du QCM
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * Définit l'identifiant du propriétaire du QCM
     * @param int $ownerId Identifiant du propriétaire du QCM
     * @return Mcq Instance courante (fluent)
     */
    public function setOwnerId(int $ownerId): Mcq
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    /**
     * Renvoie le chemin vers le XML du questionnaire
     * @return string Chemin vers le XML
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Définit le chemin vers le XMl
     * @param string $path Chemin vers le XMl
     * @return $this Instance courante (fluent)
     */
    public function setPath(string $path): Mcq
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Renvoie l'identifiant du sujet concerné par le QCM
     * @return int Identifiant du sujet traité
     */
    public function getSubjectId(): int
    {
        return $this->subjectId;
    }

    /**
     * Définit l'identifiant du sujet traité par le QCM
     * @param int $subjectId Identifiant du sujet traité par le QCM
     * @return $this Instance courante (fluent)
     */
    public function setSubjectId(int $subjectId): Mcq
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
            ACTION_TYPE::DELETE => new Query("DELETE FROM mcq WHERE id=?", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "INSERT INTO mcq (path, subject_id, owner_id) VALUES (?, ?, ?)",
                [
                    $this->getPath(), $this->getSubjectId(), $this->getOwnerId()
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "UPDATE mcq SET path=?, subject_id=?, owner_id WHERE id=?",
                [
                    $this->getPath(), $this->getSubjectId(), $this->getOwnerId(), $this->getId()
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
