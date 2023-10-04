<?php

namespace App;

/**
 * Autoloader personnalisé simple (permet d'éviter le module composer)
 * afin d'obtenir le lien des controllers et le nom des entités.
 */
class CustomAutoloader
{
    /**
     * Retourne un tableau contenant les liens vers les controllers
     * @return array Tableau de liens
     */
    public static function getControllersName(): array
    {
        // Get all controllers without indexController :
        $controllersPath = array_filter(glob('../controllers/*Controller.php', GLOB_BRACE), function($file) {
            return $file !== "../controllers/IndexController.php";
        });

        $controllersName = array();

        foreach($controllersPath as $controllerPath)
            $controllersName[] = str_replace('.php', '', str_replace('../controllers/', '', $controllerPath));

        // Add index controller to the end :
        $controllersName[] = "IndexController";
        return $controllersName;
    }

    /**
     * Retourne un tableau contenant le nom de toutes les entités
     * @return array Tableau avec le nom des entités
     */
    public static function getEntitiesName(): array
    {
        $paths = glob("../models/entities/*.php", GLOB_BRACE);

        $ret = array();
        foreach($paths as $filePath) {
            $fileName = str_replace("../models/entities/", "", $filePath);
            $ret[] = str_replace(".php", "", $fileName);
        }
        return $ret;
    }

    /**
     * Renvoie le nom des services dans le dossier services
     * @return array Liste de noms de services
     */
    public static function getServicesName(): array
    {
        $servicesPath = glob('../services/*.php', GLOB_BRACE);

        $servicesName = array();
        foreach($servicesPath as $servicePath) {
            $fileName = str_replace("../services/", "", $servicePath);
            $servicesName[] = str_replace(".php", "", $fileName);
        }
        return $servicesName;
    }
}