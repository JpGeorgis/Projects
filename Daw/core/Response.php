<?php

namespace App\HTTP;

use App\Vues\VueReader;
use Exception;

require('VueReader.php');

/**
 * Classe Response.
 * Response est la classe retournée pour chaque entrée dans un controller. C'est elle qui contient le code HTML.
 */
class Response
{
    /**
     * @var string Contenu de la réponse
     */
    private string $content;

    /**
     * @var VueReader Lecteur de Vue
     */
    private VueReader $vueReader;
    /**
     * @var int Code HTTP (200 par défaut)
     */
    private int $HTTPCode;

    /**
     * Constructeur de la classe Response.
     * @param string $content Contenu brut de la page
     */
    function __construct(string $content = "") {
        $this->content = $content;
        $this->vueReader = new VueReader();
        $this->HTTPCode = 200;
    }

    /**
     * Fait le rendu d'une vue avec son chemin et ses paramètres.
     * @param string $path Chemin vers la vue
     * @param array $params Paramètre à passer à la vue
     * @return $this Instance courante (fluent)
     */
    public function render(string $path, array $params = array()): Response {
        try {
            $this->content = $this->vueReader->read($path, $params);
            return $this;
        } catch(Exception $e) {
            die($e);
        }
    }

    /**
     * Définit le contenu de la vue avec un texte brut.
     * @param string $text Texte brut
     * @return $this Instance courante (fluent)
     */
    public function text(string $text): Response {
        $this->content = $text;
        return $this;
    }

    /**
     * Retourne le contenu actuel de la vue.
     * @return string Contenue de la vue
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * Renvoie le code HTTP de la vue. (200 par défaut)
     * @return int Code HTTP de la vue
     */
    public function getHTTPCode(): int
    {
        return $this->HTTPCode;
    }

    /**
     * Définit le code HTTP de la réponse. (200 par défaut)
     * @param int $HTTPCode Code HTTP
     * @return $this Instance courante (fluent)
     */
    public function setHTTPCode(int $HTTPCode=200): Response
    {
        $this->HTTPCode = $HTTPCode;
        return $this;
    }

    /**
     * Affiche le contenu de la vue.
     * @return void
     */
    public function renderDisplay() {
        http_response_code($this->HTTPCode);
        echo($this->getContent());
    }

}