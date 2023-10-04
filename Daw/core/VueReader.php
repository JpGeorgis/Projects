<?php

namespace App\Vues;

use Exception; // for \ char before Exception

/**
 * Classe VueReader.
 * Elle permet de charger une vie et de générer avec de l'HTML valide contenu dans son attribut content.
 * On récupère le contenu avec l'accesseur getContent().
 *
 */
class VueReader
{
    /**
     * @var string Contenu HTML de la vue
     */
    private string $content;
    /**
     * @var string Chemin vers la vue à charger
     */
    private string $path;
    /**
     * @var array Liste des paramètres pour la substitution des variables dans la vue
     */
    private array $params;

    /**
     * @var bool Si le lecteur a réalisé une opération cette itération
     */
    private bool $doSomething;

    /**
     * Constructeur de la classe
     */
    function __construct() {
        $this->path = "";
        $this->params = array();
        $this->content = "";
        $this->doSomething = true;
    }

    /**
     * Accesseur pour le chemin de la vue
     * @return string Chemin jusqu'à la vue
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Accesseur pour le contenu HTML
     * @return string contenu HTML de la vue
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Remplace tous les includes de la vue par le contenu désiré
     * @return void
     * @throws Exception Si la vue n'a pas été trouvée où n'est pas valide
     */
    private function includeProcess(): void {
        $regex = '#\{% *include\((.*)\) *%}#m'; // Regex to find include()
        $includes = null; // matches
        preg_match_all($regex, $this->content, $includes, PREG_SET_ORDER); // get matches

        foreach($includes as $include) { // for each match
            $this->doSomething = true;
            $reader = new VueReader(); // new Vue Reader
            $inc = $reader->read("../vues/".$include[1], $this->params); // render view
            $this->content = str_replace($include[0], $inc, $this->content); // replace with content
        }
    }

    /**
     * Remplace tous les assets() de la vue par le contenu approprié.
     * Cela permet de mieux gérer les inclusions avec les différentes profondeurs de fichier.
     * La méthode génère automatiquement les ../ nécessaire.
     * @return void
     */
    private function assetsProcess(): void {
        $regex = '#\{% *assets\((.*?)\) *%}#m'; // regex to find assets()
        $assets = null; // regex matches
        preg_match_all($regex, $this->content, $assets, PREG_SET_ORDER); // get matches

        foreach($assets as $asset) { // foreach match
            $this->doSomething = true;
            $folder_depth = substr_count($_SERVER["REQUEST_URI"] , "/"); // get folder depth

            if(!$folder_depth) // if can't
                $folder_depth = 1; // default value

            $link = str_repeat("../", $folder_depth-1)."assets/".$asset[1]; // repeat by folder_depth
            $this->content = str_replace($asset[0], $link, $this->content); // replace content
        }
    }

    /**
     * Créer les chemins vers les routes spécifiées en fonction de l'URI actuelle.
     * @return void
     */
    private function routeProcess(): void {
        $regex = '#\{% *route\((.*?)\) *%}#m'; // regex to find route()
        $routes = null; // regex matches
        preg_match_all($regex, $this->content, $routes, PREG_SET_ORDER); // get matches

        foreach($routes as $route) { // for each match
            $this->doSomething = true;
            $folder_depth = substr_count($_SERVER["REQUEST_URI"] , "/"); // get folder dept with URI

            if(!$folder_depth) // if can't
                $folder_depth = 1; // default value

            $link = str_repeat("../", $folder_depth-1).$route[1]; // repeat for each depth
            $link = str_replace('//', '/', $link);
            $this->content = str_replace($route[0], $link, $this->content); // replace content
        }
    }

    /**
     * Substitue les affichages de la vue par les valeurs de params
     * @return void
     */
    private function printProcess(): void {
        $regex = '#{{ *(\S*) *}}#m'; // regex to find print {{ var }}
        $matches = null; // matches
        preg_match_all($regex, $this->content, $matches, PREG_SET_ORDER); // get matches
        foreach($matches as $match) { // for each match
            $replace = ""; // replace by
            if (array_key_exists($match[1], $this->params)) {// if var exists
                $this->doSomething = true;
                $replace = $this->params[$match[1]]; // save value
            }

            $this->content = str_replace($match[0], $replace, $this->content); // replace value
        }

    }

    /**
     * Traitement pour gérer les conditions dans le moteur de modèle.
     * Il ne gère que des conditions simples en évaluant une variable à true ou false
     * @return void
     */
    private function ifProcess(): void {
        $regex = '/{%\s*if\((\S*)\)\s*%}([\s\S]*?){%\sendif\s%}/'; // find structure regex
        $matches = null; // matches
        $onFalse = ""; // to do if condition is false
        preg_match_all($regex, $this->content, $matches, PREG_SET_ORDER); // find structure
        foreach($matches as $match) { // foreach match
            $cond = $match[1]; // get condition
            $action = $match[2]; // get action (with else
            $else = null; // else match
            $regex = '/([\s\S]*){%\s*else\s*%}([\s\S]*)/'; // else regex
            preg_match($regex, $action, $else); // try to find else
            if($else) { // if else
                $onTrue = $else[1]; // if cond is true do onTrue
                $onFalse = $else[2]; // if cond is false do onFalse
            } else { // if no else
                $onTrue = $action; // on True -> do all structure body
            }

            if (array_key_exists($cond, $this->params)) { // if cond exist
                $this->doSomething = true;
                $this->content = str_replace($match[0],
                    $this->params[$cond] ? $onTrue : $onFalse,
                    $this->content); // replace content
            }
        }
    }

    /**
     * Traitement afin de remettre les {} dans le texte.
     * Ils ont été retirés en prévention des injections par le moteur de templates.
     * @return void
     */
    public function bracketsProcess(): void
    {
        $this->content = str_replace('&#125;', '}', $this->content);
        $this->content = str_replace('&#123;', '{', $this->content);
    }

    /**
     * Lit la vue et traite son contenu.
     * @param string $path Chemin vers la vue
     * @param array $params Tableau des paramètres de substitution pour la vue
     * @return string Contenu HTML de la vue
     * @throws Exception Si la vue n'a pas été trouvée ou ne peut pas être lue
     */
    public function read(string $path = "", array $params = array()): string {
        // If vues doesn't exist
        if(!file_exists("../vues/".$path))
            throw new Exception("Vue not found: " . $path);

        // Get content
        $buffer = file_get_contents("../vues/".$path);

        // if reader can't read
        if(!$buffer)
            throw new Exception("Can't read vue: " . $path);

        // Saves content and params
        $this->content = $buffer;
        $this->params = $params;

        // Render :
        while($this->doSomething) {
            $this->doSomething = false;
            $this->ifProcess(); // {% if(conf) %} action {% else %} action2 {% endif %}
            $this->includeProcess(); // {% include(path) %}
            $this->assetsProcess(); // {% assets(component/style.css %}
            $this->routeProcess(); // {% route(home) %}
            $this->printProcess(); // {{ var }}
        }

        $this->bracketsProcess();


        return $this->content; // Return new content
    }

}