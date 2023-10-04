<?php

namespace App\Models\entities;

use APP\Models\SQL\Query;

/**
 * Classe abstraite pour les entités en base de données.
 * Toutes les entités doivent hériter de cette classe
 *
 * -→ L'utilisation de la classe abstraite et non l'interface est justifiée par la définition de certaines méthodes
 * comme celles concernant les identifiants.
 */
abstract class Entity
{
    /**
     * @var int Identifiant de l'entité
     */
    protected int $id;

    /**
     * Renvoie l'identifiant de l'entité
     * @return int Identifiant de l'entité
     */
    final public function getId(): int {
        return $this->id;
    }

    /**
     * Définit l'identifiant de l'entité
     * @param int $id Identifiant de l'entité
     * @return $this Getter fluent -> Renvoie l'instance pour enchainer les setters
     */
    final public function setId(int $id): Entity {
        $this->id = $id;
        return $this;
    }

    /**
     * La classe doit être capable de pouvoir se décrire en SQL afin de permettre au manager de la traiter.
     * Le traiter n'est pas unifié : Il n'existe pas d'ORM dans le projet.
     * @param int $mode Action [0: CREATE, 1: UPDATE, 2: DELETE]
     * @return Query
     */
    abstract function toSql(int $mode): Query;

    /**
     * Renvoie les attributs en mémoire
     * @return array Attributs en mémoire
     */
    public abstract function getAttributes(): array;

}