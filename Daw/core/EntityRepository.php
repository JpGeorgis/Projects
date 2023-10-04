<?php

namespace App\Models\repositories;

use App\Models\entities\Entity;
use PDO;
use function Sodium\add;

/**
 * Classe abstraite modélisant un dépôt.
 * TODO: FindBy ne sélectionne pas tout, puis traite en PHP -> Ajout de query montée avec le WHERE.
 */
abstract class EntityRepository
{
    /**
     * @var array Liste des entités du dépôt
     */
    protected array $entities;

    /**
     * Connexion PDO
     * @var PDO connexion
     */
    private PDO $pdo;

    /**
     * @var string Nom de la table
     */
    private string $tableName;

    /**
     * Constructeur de la classe
     */
    public function __construct(string $tableName) {
        $this->entities = [];
        $this->tableName = $tableName;
    }

    /**
     * Retourne le nom de la table
     * @return string nom de la table
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * Définit la connexion PDO vers la base de données
     * @param PDO $pdo Connexion PDO
     * @return $this Instance actuelle
     */
    public function setPDO(PDO $pdo): EntityRepository
    {
        $this->pdo = $pdo;
        return $this;

    }

    /**
     * Retourne la connexion PDO
     * @return PDO connexion PDO
     */
    protected function getPDO(): PDO
    {
        return $this->pdo;
    }

    /**
     * Ajoute une entité à la classe.
     * Les entités sont ajoutées à la suite dans la liste interne.
     * @param mixed $entity Entité à ajouter
     * @return $this Instance courante (fluent)
     */
    public function add(mixed $entity): EntityRepository {
        $this->entities[] = $entity;
        return $this;
    }

    /**
     * Renvoie l'intégralité des entités chargées.
     * @return array Intégralité des entités en caches
     */
    public function findAll(): array {
        $entities = [];
        $query = $this->getPDO()->prepare("SELECT * FROM ".$this->getTableName());
        $query->execute();
        foreach ($query->fetchAll() as $item)
            $entities[] = $this->makeByArray($item);
        return $entities;
    }

    /**
     * Méthode abstraite qui construit un objet à partir d'un tableau
     * @param array $item Tableau d'attributs
     * @return Entity|null Entité construite à partir du tableau
     */
    public abstract function makeByArray(array $item): ?Entity;

    /**
     * Renvoie une entité grâce à son identifiant.
     * @param int $id Identifiant de l'entité voulue
     * @return Entity|null Entité
     */
    public function findById(int $id): ?Entity {
        $query = $this->pdo->prepare("SELECT * FROM ".$this->getTableName()." WHERE id=?");
        $query->execute([$id]); // id -> not danger with sql injection
        $r = $query->fetchAll();
        return count($r) != 0 ? $this->makeByArray($r[0]) : null;
    }

    /**
     * Renvoie une entité qui satisfait les conditions indiquées
     * @param array $conditions Conditions à satisfaire
     * @return Entity|null Entité si trouvée, sinon null
     */
    public function findOneBy(array $conditions): ?Entity {
        if(count($conditions) == 0)
            return null;

        // Construct query :
        $query = "SELECT * FROM ".$this->getTableName()." WHERE ";
        foreach($conditions as $col => $value)
            $query .= $col." = '".$value."' AND ";
        $query = substr($query, 0, -5);
        $query = $this->pdo->prepare($query); // prevent sql injection
        $query->execute();
        $r = $query->fetchAll();
        return count($r) != 0 ? $this->makeByArray($r[0]) : null;
    }

    /**
     * Renvoie toutes les entités qui satisfont les conditions.
     * @param array $conditions Conditions
     * @return array Liste d'entités
     */
    public function findBy(array $conditions): array {
        if(count($conditions) == 0)
            return [];

        // Construct query :
        $query = "SELECT * FROM ".$this->getTableName()." WHERE ";
        foreach($conditions as $col => $value) {
            $query .= "$col = '$value' AND ";
        }
        $query = substr($query, 0, -5);
        $query = $this->pdo->prepare($query); // prevent sql injection
        $query->execute();
        $out = [];
        foreach($query->fetchAll() as $item)
            if($temp = $this->makeByArray($item))
                $out[] = $temp;


        return $out;
    }
}