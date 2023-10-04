<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");

/**
 * Objet modélisant un fil de discussion du forum
 */
class ForumThread extends Entity
{
    /**
     * @var string Nom du fil de discussion
     */
	private string $name;
    /**
     * @var DateTime Date de création du fil de discussion
     */
	private DateTime $createDate;
    /**
     * @var int Identifiant de l'auteur du fil de discussion
     */
	private int $author;
    /**
     * @var int Visibilité du fil de discussion
     */
    private int $visible;
    /**
     * @var int Nombre de vues du fil de discussion
     */
    private int $vues;

    /**
     * Renvoie le nombre de vues du fil de discussion
     * @return int Nombre de vues du fil de discussion
     */
    public function getVues(): int
    {
        return $this->vues;
    }

    /**
     * Définit le nombre de vues du fil de discussion
     * @param int $vues Nombre de vues du fil de discussion
     * @return $this Instance courante (fluent)
     */
    public function setVues(int $vues): ForumThread
    {
        $this->vues = $vues;
        return $this;
    }

    /**
     * Renvoie le statut de visibilité du fil de discussion
     * @return int Statut de visibilité du fil de discussion
     */
    public function getVisible(): int {
        return $this->visible;
    }

    /**
     * Définit le statut de visibilité du fil de discussion
     * @param int $visible Statut de visibilité du fil de discussion
     * @return $this Instance courante (fluent)
     */
    public function setVisible(int $visible): ForumThread {
        $this->visible = $visible;
        return $this;
    }

    /**
     * Renvoie le nom du fil de discussion
     * @return string Nom du fil de discussion
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Définit le nom du fil de discussion
     * @param string $name Nom du fil de discussion
     * @return $this Instance courante (fluent)
     */
    public function setName(string $name): ForumThread
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Renvoie la date de création du fil de discussion
     * @return DateTime Date de création du fil de discussion
     */
    public function getCreateDate(): DateTime
    {
        return $this->createDate;
    }

    /**
     * Définit la date de création du fil de discussion
     * @param DateTime $createDate Date de création du fil de discussion
     * @return $this Instance courante (fluent)
     */
    public function setCreateDate(DateTime $createDate): ForumThread
    {
        $this->createDate = $createDate;
        return $this;
    }

    /**
     * Renvoie l'identifiant de l'auteur du fil de discussion
     * @return int Identifiant de l'auteur du fil de discussion
     */
    public function getAuthor(): int
    {
        return $this->author;
    }

    /**
     * Définit l'identifiant de l'auteur du fil de discussion
     * @param int $author Identifiant de l'auteur du fil de discussion
     * @return $this Instance courante (fluent)
     */
    public function setAuthor(int $author): ForumThread
    {
        $this->author = $author;
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
            ACTION_TYPE::DELETE => new Query("DELETE FROM forum_thread WHERE id=?", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "INSERT INTO forum_thread (name, create_date, author, visible, vues) VALUES (?, ?, ?, ?, ?)",
                [
                    $this->getName(), $this->getCreateDate()->format("Y-m-d H:i:s"), $this->getAuthor(),
                    $this->getVisible(), $this->getVues()
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "UPDATE forum_thread SET name=?, create_date=?, author=?, visible=?, vues=? WHERE id=?",
                [
                    $this->getName(), $this->getCreateDate()->format("Y-m-d H:i:s"), $this->getAuthor(),
                    $this->getVisible(), $this->getVues(),
                    $this->getId()
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
