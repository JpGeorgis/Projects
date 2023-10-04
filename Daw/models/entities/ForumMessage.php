<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");


/**
 * Message d'un fil de dicussion
 */
class ForumMessage extends Entity
{
    /**
     * @var int Identifiant de l'auteur du message
     */
	private int $authorId;
    /**
     * @var string Contenu du message
     */
	private string $content;
    /**
     * @var DateTime Date à laquelle le message a été publié
     */
	private DateTime $date;
    /**
     * @var int Visibilité du message
     */
	private int $visible;
    /**
     * @var int Identifiant du fil de discussion où se trouve le message
     */
    private int $threadId;

    /**
     * Renvoie l'identifiant du fil de discussion auquel appartient le message
     * @return int Identifiant du fil de discussion auquel appartient le message
     */
    public function getThreadId(): int
    {
        return $this->threadId;
    }

    /**
     * Définit l'identifiant du fil de discussion auquel appartient le message
     * @param int $id Identifiant du fil de discussion auquel appartient le message
     * @return $this Instance courante (fluent)
     */
    public function setThreadId(int $id): ForumMessage
    {
        $this->threadId = $id;
        return $this;
    }

    /**
     * Renvoie l'identifiant de l'auteur du message
     * @return int Identifiant de l'auteur du message
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * Définit l'identifiant de l'auteur du message
     * @param int $authorId Identifiant de l'auteur du message
     * @return $this Instance courante (fluent)
     */
    public function setAuthorId(int $authorId): ForumMessage
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * Renvoie le contenu du message
     * @return string Contenu du message
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Définit le contenu du message
     * @param string $content Contenu du message
     * @return $this Instance courante (fluent)
     */
    public function setContent(string $content): ForumMessage
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Renvoie la date à laquelle le message a été posté
     * @return DateTime Date à laquelle le message a été posté
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Définit la date à laquelle le message a été posté
     * @param DateTime $date Date à laquelle le message a été posté
     * @return $this Instance courante (fluent)
     */
    public function setDate(DateTime $date): ForumMessage
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Renvoie le statut de visibilité du message
     * @return int Visibilité du message
     */
    public function getVisible(): int
    {
        return $this->visible;
    }

    /**
     * Définit le statut de visibilité du message
     * @param int $visible Visibilité du message
     * @return $this Instance courante (fluent)
     */
    public function setVisible(int $visible): ForumMessage
    {
        $this->visible = $visible;
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
            ACTION_TYPE::DELETE => new Query("DELETE FROM forum_message WHERE id=?", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "INSERT INTO forum_message (author_id, content, date, visible, thread_id) VALUES (?, ?, ?, ?, ?)",
                [
                    $this->getAuthorId(), $this->getContent(), $this->getDate()->format("Y-m-d H:i:s"), $this->getVisible(), $this->getThreadId()
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "UPDATE forum_message SET author_id=?, content=?, date=?, visible=?, thread_id=? WHERE id=?",
                [
                    $this->getAuthorId(), $this->getContent(), $this->getDate()->format("Y-m-d H:i:s"), $this->getVisible(), $this->getThreadId(),
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
