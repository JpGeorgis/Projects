<?php

namespace App\Models\entities;

use App\Models\DBManager;
use App\Models\repositories\EntityRepository;

/**
 * Classe abstraite pour les services
 */
abstract class AbstractService
{
    /**
     * @var ?DBManager Gestionnaire base de données
     */
    private ?DBManager $dbManager;

    /**
     * @var array Liste des services du projet
     */
    private array $services;

    /**
     * Constructeur de la classe
     */
    public function __construct() {
        $this->services = [];
        $this->dbManager = null;
    }

    /**
     * Initialisation des composantes de la classe une fois les services définies
     * @return void
     */
    public function init(): void { }

    /**
     * Définit le gestionnaire de base de données
     * @param DBManager $db Gestionnaire de base de données
     * @return $this Instance courante (fluent)
     */
    public function setDBManager(DBManager $db): AbstractService {
        $this->dbManager = $db;
        $this->services = [];
        return $this;
    }

    /**
     * Renvoie le gestionnaire de base de données
     * @return DBManager Gestionnaire de base de données
     */
    public function getDBManager(): DBManager {
        return $this->dbManager;
    }

    /**
     * Renvoie un dépôt
     * @param string $class Dépôt voulu
     * @return EntityRepository|null Dépôt ou null si non trouvé
     */
    public function getRepository(string $class): ?EntityRepository
    {
        return $this->dbManager->getRepository($class);
    }

    /**
     * Définit la liste des services
     * @param array $services Liste des services
     * @return $this Instance courante (fluent)
     */
    public function setServices(array $services): AbstractService
    {
        $this->services = $services;
        return $this;
    }

    /**
     * Retourne la liste des services
     * @return array Liste des services
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * Retourne un service grâce à son nom
     * @param string $serviceName Service voulu
     * @return AbstractService|null Service instancié (null sinon)
     */
    protected function get(string $serviceName): ?AbstractService
    {
        foreach($this->services as $service) {
            if($serviceName === get_class($service))
                return $service;
        }
        return null;
    }
}