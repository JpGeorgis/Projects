<?php

namespace App\Controller;

require_once('../core/AbstractController.php');

use App\HTTP\Request;
use App\HTTP\Response;
use App\Service\SessionMgr;
use App\Service\ThemeMgr;

class IndexController extends AbstractController
{
    private ?SessionMgr $session;
    private ?ThemeMgr $themeMgr;

    public function __construct() {
        parent::__construct();
        $this->session = null;
        $this->themeMgr = null;
        $this->routes["//"] = "index";
    }

    public function init(): void {
        $this->session = $this->get(SessionMgr::class);
        $this->themeMgr = $this->get(ThemeMgr::class);
    }

    /**
     * Page d'accueil du site.
     * @return Response Réponse retournée au client
     */
    public function index(): Response {
        $response = new Response();

        $response->render("index.html",
            [
                "connected" => $this->session->isConnected(),
                "isAdmin" => $this->session->isAdmin(),
                "isTeacher" => $this->session->isTeacher(),
                "theme" => $this->themeMgr->getTheme()
            ]
        );

        return $response;
    }
}
