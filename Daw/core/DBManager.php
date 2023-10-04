<?php

namespace App\Models;

use App\Models\repositories\EntityRepository;
use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use Exception;
use PDO;

require("DBClasses.php");

/**
 * Gestionnaire de base de données.
 * Il offre une gestion simplifiée de la base de données grâce aux méthodes :
 *      - add
 *      - remove
 *      - update
 *      - flush
 */
class DBManager
{
    /**
     * @var array Connexion à la base de données
     */
    private array $toDo;
    /**
     * @var PDO Connexion à la base de données.
     */
    private PDO $pdo;
    /**
     * @var array Liste des dépôts
     */
    private array $repositories;

    /**
     * Constructeur de la classe de DBManager
     * @param array $configuration Tableau avec la configuration de la base de données
     * @param array $entitiesList Tableau avec la liste des entités
     */
    public function __construct(array $configuration, array $entitiesList) {

        try{
            $this->pdo = new PDO("mysql:dbname" . $configuration["db_name"] . ";host=" . $configuration["host"],
                    $configuration["user"],
                    $configuration["password"]);

            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
            $this->pdo->exec("USE ".$configuration["db_name"].";");
        } catch(Exception $e) {
            // DEBUG:
            if($_ENV['MODE'] === 'DEBUG')
                echo("Impossible d'accéder à la base de données : ".$e->getMessage());
            else
                echo("Impossible d'accéder à la base de données.");
            die();
        }
        $this->toDo = array();
        $this->repositories = array();

        // Load all repositories:
        foreach($entitiesList as $entity) {
            $this->repositories[] = require("../models/repositories/".$entity."Repository.php");
            end($this->repositories)->setPDO($this->pdo);
        }
    }

    /**
     * Execute une query sur la base de données
     * @param Query $q Query a exécuter
     * @return int Nombre de lignes concernées
     */
    private function useQuery(Query $q): int {
        $query = $this->pdo->prepare($q->getQuery());
        $query->execute($q->getParams());
        return $query->rowCount();
    }

    /**
     * Renvoie le dépôt demandé
     * @param $class mixed EntityRepository Dépôt demandé
     * @return EntityRepository|null Dépôt si trouvé
     */
    public function getRepository(mixed $class): ?EntityRepository {
        foreach ($this->repositories as $repo)
            if($repo instanceof $class)
                return $repo;
        return null;
    }

    /**
     * Ajoute une entité à la base de données
     * @param mixed $entity Entité à ajouter
     * @return void
     */
    public function add(mixed $entity): void {
        $this->toDo[] = [ACTION_TYPE::CREATE, $entity];
    }

    /**
     * Met à jour une entité dans la base de données
     * @param mixed $entity Entité à actualiser
     * @return void
     */
    public function update(mixed $entity): void {
        $this->toDo[] = [ACTION_TYPE::UPDATE, $entity];
    }

    /**
     * Supprime une entité de la base de données.
     * @param mixed $entity Entité à supprimer
     * @return void
     */
    public function delete(mixed $entity): void {
        $this->toDo[] = [ACTION_TYPE::DELETE, $entity];
    }

    /**
     * Envoie l'intégralité des modifications mises en cache à la base de données :
     *      - Suppressions
     *      - Actualisations
     *      - Ajout
     * @return array ID envoyés en base de données
     */
    public function flush(): array {
        $ids = [];
        foreach($this->toDo as $_ => $row) {
            $this->useQuery($row[1]->toSql($row[0]));
            $ids[] = $this->pdo->lastInsertId();
        }

        $this->toDo = array();
        return $ids;
    }

}