<?php

namespace App;

use App\HTTP\Request;
use App\Models\DBManager;
use ReflectionMethod;
use ReflectionException;

// Imports application (core)
require_once('../core/Request.php');
require_once('../core/Response.php');
require_once('../core/DBManager.php');
require_once('../core/CustomAutoloader.php');
require_once('../core/DotEnvReader.php');


/**
 * Noyau de l'application.
 */
class Kernel
{
    /**
     * @var array Tableau global $_SERVER
     */
    private array $server;
    /**
     * @var array Tableau global $_SESSION
     */
    private array $session;
    /**
     * @var array  Tableau global $_POST
     */
    private array $post;
    /**
     * @var array  Tableau global $_GET
     */
    private array $get;
    /**
     * @var array Tableau contenant les contrôleurs
     */
    private array $controllers;
    /**
     * @var array Tableau contenant les services
     */
    private array $services;
    /**
     * @var array Configuration de la base de données importée du .env
     */
    private array $dbConfig;
    /**
     * @var DBManager Gestionnaire de base de données
     */
    private DBManager $dbMgr;


    /**
     * Initialise la configuration de la base de données en lisant le fichier .env à la racine du framework
     * @return Kernel Retourne l'instance actuelle (fluent)
     */
    private function initDBConfiguration(): Kernel {
        $this->dbConfig = array(
            "host" => $_ENV['DBHOST'],
            "user" => $_ENV['DBUSER'],
            "password" => $_ENV['DBPASSWORD'],
            "db_name" => $_ENV['DBNAME']
        );
        return $this;
    }

    /**
     * Retourne le type des paramètres d'une méthode
     * @param $class mixed Classe qui contient la méthode
     * @param $funcName mixed Méthode à étudier
     * @return array Array avec les paramètres
     */
    public static function get_func_argNames(mixed $class, mixed $funcName): array {
        $result = array();
        try {
            $f = new ReflectionMethod($class, $funcName);
            foreach ($f->getParameters() as $param) {
                $result[] = $param->getType()->getName() ;
            }
        } catch (ReflectionException $_) { /* do nothing */ }
        return $result;
    }

    /**
     * Travail courant du noyau. Il retourne une page en fonction de la route actuelle.
     * @return $this Instance actuelle (fluent)
     */
    public function process(): Kernel
    {
        $uri = $this->server["REQUEST_URI"]; // Get uri to find route
        $r = new Request($this->server, $this->session, $this->post, $this->get); // Create Request
        $URI_matches = array(); // for param in URI

        foreach ($this->controllers as $c) { // For each controller
            if ($c->hasRouteFor($uri, $URI_matches)) { // If controller has route
                $r->setURIMatches($URI_matches);
                $c->setDBManager($this->dbMgr); // Give Database information to controller
                $func = $c->getFunction($uri); // Get function name

                $params = [];
                // Build param :
                foreach(self::get_func_argNames($c, $func) as $type) // foreach args
                {
                    if($type === 'App\HTTP\Request') // if type is Request
                        $params[] = $r; // Add Request
                    else { // Else
                        foreach($this->services as $service) { // For each services
                            if($type == get_class($service)) { // If name are same
                                $params[] = $service; // Add to params
                            }
                        }
                    }
                }

                // Call controller method
                call_user_func_array(
                    [$c, $func], // CallBack
                    $params // params
                )->renderDisplay(); // Display
                return $this;
            }
        }
        return $this;
    }

    /**
     * Initialise la base de données, les contrôleurs et les services.
     * @return $this
     */
    public function init(): Kernel
    {
        $env = new DotEnvReader("../.env");
        $env->load();

        if($_ENV["MODE"] === "DEBUG") { // if debug
            ini_set('display_errors', 1); // show error
            ini_set('display_startup_errors', 1); // show startup errors
            error_reporting(E_ALL); // Report ALL errors and warnings
        }

        // DBManager: Init connection and repositories
        $this->initDBConfiguration();
        $this->dbMgr = new DBManager($this->dbConfig, CustomAutoloader::getEntitiesName());

        // init services
        foreach(CustomAutoloader::getServicesName() as $service) {
            require_once('../services/'.$service.'.php');
            $service = 'App\Service\\'.$service;
            $this->services[] = new $service;
            end($this->services)->setDBManager($this->dbMgr);
        }

        array_map(
          fn($item) => $item->setServices($this->services),
          $this->services
        );
        array_map(
            fn($item) => $item->init(),
            $this->services
        );

        // Init controllers
        foreach(CustomAutoloader::getControllersName() as $controller)  { // For each controller
            require_once('../controllers/'.$controller.'.php');
            $controller = 'App\Controller\\'.$controller;
            $this->controllers[] = new $controller(); // Load file and put class instance into controllers list
            end($this->controllers)->setServices($this->services)->init();

        }

        return $this;
    }

    /**
     * Constructeur de la classe Kernel
     * @param array $server Tableau global $_SERVER
     * @param array $session Tableau global $_SESSION
     * @param array $post Tableau global $_POST
     * @param array $get Tableau global $_GET
     */
    public function __construct(array $server, array $session, array $post, array $get)
    {
        $this->server = $server;
        $this->session = $session;
        $this->post = $post;
        $this->get = $get;
        $this->services = [];
    }


}