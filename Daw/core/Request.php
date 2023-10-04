<?php

namespace App\HTTP;

use Exception;

/**
 * Classe Request
 * Interface permettant de gérer plus facilement les données.
 * La requête contient tous les éléments liés à la demande de l'utilisateur :
 *  - URI
 *  - GET
 *  - POST
 *  - ...
 */
class Request
{
    /**
     * @var array Tableau POST.
     */
    private array $post;
    /**
     * @var array Tableau GET.
     */
    private array $get;
    /**
     * @var array Tableau SERVER.
     */
    private array $server;
    /**
     * @var array Correspondances avec la regex de l'URI spécifiées dans la route.
     */
    private array $URI_matches;
    /**
     * @var array Tableau SESSION.
     */
    private array $session;

    /**
     * Constructeur de la classe.
     *
     * @param array $server Tableau global Server.
     * @param array $post Tableau global POST.
     * @param array $get Tableau global GET.
     */
    function __construct(array $server, array $session, array $post, array $get) {
        $this->server = $server;
        $this->session = $session;
        $this->post = $post;
        $this->get = $get;
    }

    /**
     * Renvoie l'intégralité du tableau POST.
     * @return array tableau POST.
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * Renvoie l'intégralité du tableau GET.
     * @return array tableau GET.
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * Renvoie l'intégralité du tableau SERVER.
     * @return array tableau SERVER.
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * Renvoie l'intégralité du tableau SESSION.
     * @return array tableau SESSION.
     */
    public function getSession() : array {
        return $this->session;
    }

    /**
     * Renvoie un paramètre d'un tableau (GET, POST, SERVEUR, SESSION).
     * @param string $method Tableau à aller chercher (GET, POST, SERVEUR, SESSION)?
     * @param string $item Objet voulu.
     * @return mixed Valeur si trouvée, sinon null.
     */
    public function get(string $method, string $item): Mixed {
        return match (strtoupper($method)) {
            "POST" => $this->post[$item] ?? null,
            "GET" => $this->get[$item] ?? null,
            "SERVER" => $this->server[$item] ?? null,
            "SESSION" => $this->session[$item] ?? null,
            default => "",
        };
    }

    /**
     * Définir un objet dans un tableau (SESSION, SERVEUR).
     * @param string $method Tableau (SESSION, SERVEUR).
     * @param string $item Index de l'objet à redéfinir.
     * @param mixed $value Valeur à définir.
     * @return void
     * @throws Exception Si la méthode n'existe pas.
     */
    public function set(string $method, string $item, Mixed $value): void {
        switch(strtoupper($method)) {
            case "SESSION":
                $_SESSION[$item] = $value;
                break;
            case "SERVER":
                $_SERVER[$item] = $value;
                break;
            default:
                throw new Exception("Unknown or unacceptable method");
        }
    }

    /**
     * Renvoie les correspondances trouvées dans l'URI.
     * @return array Correspondances de l'URI.
     */
    public function getURIMatches(): array
    {
        return $this->URI_matches;
    }

    /**
     * Définit les correspondances trouvées dans l'URI.
     * @param array $URI_matches Correspondances de l'URI.
     * @return $this Instance courante (fluent)
     */
    public function setURIMatches(array $URI_matches): Request
    {
        $this->URI_matches = $URI_matches;
        return $this;
    }

    /**
     * Renvoie une correspondance de l'URI en fonction de son nom
     * @param string $key Correspondance voulue.
     * @return mixed La correspondance si trouvée, sinon null.
     */
    public function getURIParam(string $key): mixed {
        if(array_key_exists($key, $this->URI_matches))
            return $this->URI_matches[$key];
        return null;
    }


}