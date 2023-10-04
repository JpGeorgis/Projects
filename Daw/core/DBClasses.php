<?php

namespace App\Models\SQL;


/**
 * Interactions possibles avec la base de données.
 * Implémentation simpliste d'une énumération.
 *  0 → Création d'une entité
 *  1 → Actualisation d'une entité
 *  2 → Suppression d'une entité
 */
class ACTION_TYPE
{
    const CREATE = 0; // INSERT INTO
    const UPDATE = 1; // UPDATE FROM
    const DELETE = 2; // DELETE FROM
}

/**
 * Modélisation d'une requête vers la base de données.
 * Elle permet une gestion aisée des paramètres avec les sécurités PDO (addslashes inclu dans prepare, etc..)
 */
class Query
{
    /**
     * @var string Requête SQL
     */
    public string $query;
    /**
     * @var array|null Tableau de paramètres
     */
    public ?array $params;

    /**
     * Renvoie la requête SQL
     * @return string requête SQL
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Définit la requête SQL
     * @param string $query requête SQL
     * @return $this Instance courante de Query
     */
    public function setQuery(string $query): Query
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Renvoie le tableau de paramètres
     * @return array tableau de paramètres
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Définit les paramètres de la requête SQL
     * @param array $params Tableau de paramètres
     * @return $this Instance courante de Query
     */
    public function setParams(array $params): Query
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Constructeur de la classe
     * @param string $query Requête SQL
     * @param array|null $params Paramètres de la requête
     */
    public function __construct(string $query, array $params=null) {
        $this->query = $query;
        $this->params = $params;
    }

}
