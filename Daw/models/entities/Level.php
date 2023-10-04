<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");

/**
 * Modélise le résultat d'un QCM, un niveau en base de données
 */
class Level extends Entity
{
    /**
     * @var int identifiant de l'utilisateur qui a passé le test
     */
	private int $studentId;
    /**
     * @var int Sujet traité par le QCM
     */
	private int $subjectId;
    /**
     * @var int Niveau atteint pas l'utilisateur
     */
	private int $level;


    /**
     * Renvoie l'identifiant de l'utilisateur
     * @return int Identifiant de l'utilisateur
     */
    public function getStudentId(): int
    {
        return $this->studentId;
    }

    /**
     * Définit l'identifiant de l'utilisateur
     * @param int $studentId Identifiant de l'utilisateur
     * @return $this Instance courante (fluent)
     */
    public function setStudentId(int $studentId): Level
    {
        $this->studentId = $studentId;
        return $this;
    }

    /**
     *  Renvoie l'identifiant du sujet traité par le QCM
     * @return int Identifiant du sujet traité par le QCM
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
    public function setSubjectId(int $subjectId): Level
    {
        $this->subjectId = $subjectId;
        return $this;
    }

    /**
     * Renvoie le niveau de l'utilisateur
     * @return int Niveau de l'utilisateur
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Définit le niveau de l'utilisateur
     * @param int $level Niveau de l'utilisateur (1, 2, 3)
     * @return $this Instance courante (fluent)
     */
    public function setLevel(int $level): Level
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Renvoie une description SQL de l'objet selon le mode voulu
     * @param int $mode Mode désiré (UPDATE, CREATE, DELETE)
     * @return Query Query voulu
     */
    public function toSql(int $mode): Query
    {
        return match ($mode) {
            ACTION_TYPE::DELETE => new Query("DELETE FROM level WHERE id=?", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "INSERT INTO level (student_id, subject_id, level) VALUES (?, ?, ?)",
                [
                    $this->getStudentId(), $this->getSubjectId(), $this->getLevel()
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "UPDATE level SET student_id=?, subject_id=?, level=? WHERE id=?",
                [
                    $this->getStudentId(), $this->getSubjectId(), $this->getLevel(), $this->getId()
                ]
            ),

            default => new Query("") // Default: empty query
        };
    }

    /**
     * Renvoie une description des attributs de classe sous forme d'un tableau de données
     * @return array Tableau de données décrivant les attributs de classe
     */
    public function getAttributes(): array {
        return get_object_vars($this);
    }
}
