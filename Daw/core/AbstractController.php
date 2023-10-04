<?php

namespace App\Controller;

use App\Models\DBManager;
use App\Models\entities\AbstractService;
use App\Models\repositories\EntityRepository;

/**
 * Classe abstraite modélisant la base d'un contrôleur.
 * Elle permet un traitement uniforme de tous les contrôleurs.
 */
abstract class AbstractController
{
    /**
     * @var array Routes que contient le contrôleur
     */
    protected array $routes;
    /**
     * @var array Liste des services
     */
    protected array $services;
    /**
     * @var DBManager Gestionnaire de base de données (pour les entités)
     */
    protected DBManager $DBManager;

    /**
     * Constructeur de la classe
     */
    public function __construct() {
        $this->routes = [];
        $this->services = [];
    }

    /**
     * Définit la liste des services (référence)
     * @param array $services Liste des services (référence)
     * @return AbstractController Instance courante (fluent)
     */
    public function setServices(array $services): AbstractController {
        $this->services = $services;
        return $this;
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

    /**
     * Retourne le nom de la fonction correspondant à une route
     * @param string $URI Route qui doit match
     * @return string|null Nom de la fonction : null si non trouvée
     */
    public function getFunction(string $URI): ?string {
        foreach($this->routes as $route => $function)
            if(preg_match($route, $URI))
                return $function;
        return null;
    }

    /**
     * Méthode non obligatoire qui permet d'exécuter une routine d'initialisation après avoir
     * utilisé le constructeur de l'objet
     */
    public function init(): void {}

    /**
     * Vérifie si le contrôleur contient une route pour des URI
     * @param string $URI Uri a vérifier
     * @param array $matches Paramètre de l'URI (qui sera complété)
     * @return bool Si une route existe pour l'URI demandé
     */
    public function hasRouteFor(string $URI, array& $matches): bool {
        foreach($this->routes as $route=> $function)
            if(preg_match('/^\\'.$route, $URI, $matches))
                return true;
        return false;
    }

    /**
     * Définit le gestionnaire de bases de données
     * @param DBManager $mgr Gestionnaire de bases de données
     * @return void
     */
    public function setDBManager(DBManager &$mgr): void {
        $this->DBManager = &$mgr;
    }

    /**
     * Renvoie le gestionnaire de base de données
     * @return DBManager Gestionnaire de base de données
     */
    public function getDBManager(): DBManager {
        return $this->DBManager;
    }

    /**
     * Renvoie un dépôt d'entités
     * @param string $class Dépôt demandé
     * @return EntityRepository Dépôt demandé
     */
    protected function getRepository(string $class): EntityRepository {
        return $this->DBManager->getRepository($class);
    }

    /**
     * Redirige le client vers une url tant qu'aucune entité HTML n'a été envoyée
     * @param string $uri URI/URL où renvoyer le client
     * @return void
     */
    protected function redirect(string $uri): void {
        header("Location: /$uri");
        exit();
    }

}
