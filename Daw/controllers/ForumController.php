<?php

namespace App\Controller;

require_once('../core/AbstractController.php');

use App\HTTP\Request;
use App\HTTP\Response;
use App\Models\entities\ForumMessage;
use App\Models\entities\ForumThread;
use App\Models\repositories\AccountRepository;
use App\Models\repositories\ForumMessageRepository;
use App\Models\repositories\ForumThreadRepository;
use App\Service\ForumMgr;
use App\Service\InputDataChecker;
use App\Service\SessionMgr;
use App\Service\ThemeMgr;
use Cassandra\Date;
use DateTime;
use Exception;

class ForumController extends AbstractController
{
    /**
     * @var SessionMgr|null Service permettant la gestion des sessions.
     */
    private ?SessionMgr $session;
    /**
     * @var ThemeMgr|null Service permettant la gestion des thèmes
     */
    private ?ThemeMgr $theme;
    /**
     * @var ForumMgr|null Service avec les tâches courantes du forum
     */
    private ?ForumMgr $forum;

    public function __construct() {
        parent::__construct();
        $this->session = null;
        $this->theme = null;
        $this->forum = null;
        $this->routes["/new\/thread/"] = "newThread";
        $this->routes["/forum\/delete\/message/"] = "deleteMessage";
        $this->routes["/forum\/delete\/thread/"] = "deleteThread";
        $this->routes["/forum\/thread\/(?<thread>\d+)/"] = "viewThread";
        $this->routes["/forum/"] = "forum";
    }

    /**
     * Initialisation des services
     * @return void
     */
    public function init(): void {
        $this->session = $this->get(SessionMgr::class);
        $this->theme = $this->get(ThemeMgr::class);
        $this->forum = $this->get(ForumMgr::class);
    }

    /**
     * Supprimer un fil de discussion
     * @param Request $r Requête client
     * @return Response Réponse envoyée au client
     */
    public function deleteThread(Request $r): Response {
        if(!$this->session->isAdmin())
            return new Response("false");
        $id = $r->get("POST", "thread_id");
        if(!$id)
            return new Response("false");


        $id = intval($id);

        if(!($thread = $this->getRepository(ForumThreadRepository::class)->findById($id)))
            return new Response("false");

        $thread->setVisible(0);

        $this->getDBManager()->update($thread);
        $this->getDBManager()->flush();
        return new Response("true");
    }

    /**
     * Suppression d'un message du forum
     * @param Request $r Requête client
     * @return Response Réponse renvoyée au client
     */
    public function deleteMessage(Request $r): Response {
        if(!$this->session->isAdmin())
            return new Response("");
        $id = $r->get("POST", "message_id");

        if(!$id)
            return new Response("false");

        $id = intval($id);

        if(!($message = $this->getRepository(ForumMessageRepository::class)->findById($id)))
            return new Response("false");

        $message->setVisible(0);

        $this->getDBManager()->update($message);
        $this->getDBManager()->flush();
        return new Response("true");
    }

    /**
     * Page d'accueil du forum
     * @return Response Page à afficher
     */
    public function forum(): Response {
        $response = new Response();

        $response->render("forum/forum.html",
            [
                "globalViewThreads" => $this->forum->getGlobalView(),
                "connected" => $this->session->isConnected(),
                "isAdmin" => $this->session->isAdmin(),
                "isTeacher" => $this->session->isTeacher(),
                "theme" => $this->theme->getTheme()
            ]
        );
        return $response;
    }

    /**
     * Affiche un fil de discussion
     * @param Request $r Requête client
     * @return Response Page à afficher
     */
    public function viewThread(Request $r): Response {
        $threadId = (int)$r->getURIParam("thread");
        $thread = $this->getRepository(ForumThreadRepository::class)->findById($threadId);

        // If thread doesn't exist
        if(!$thread || !$thread->getVisible()) {
            $this->redirect("forum/");
            return new Response(); // Empty response (if redirection disable)
        }

        // Publish message:
        if($r->get("POST", "submit")) {
            $this->forum->addMessage($threadId, $r->get("POST", "content"));
        }

        $response = new Response();

        $response->render("forum/viewThread.html",
        [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "messages" => $this->forum->getThreadMessages($threadId),
            "threadName" => $thread->getName(),
            "theme" => $this->theme->getTheme()
        ]);
        return $response;
    }

    /**
     * Création d'un nouveau Thread
     * @return Response Page à afficher
     */
    public function newThread(Request $r): Response {
        // need to be connected
        if(!$this->session->isConnected())
            $this->redirect("login/");

        if($r->get("POST", "submit")) {
            $title = $r->get("POST", "title");
            $content = $r->get("POST", "content");

            // Check for data
           $threadID = $this->forum->createThread($title, $content);
           if($threadID !== -1)
               $this->redirect('forum/thread/'.$threadID);
        }

        $response = new Response();
        $response->render("forum/newThread.html",
            [
                "connected" => $this->session->isConnected(),
                "isAdmin" => $this->session->isAdmin(),
                "isTeacher" => $this->session->isTeacher(),
                "theme" => $this->theme->getTheme()

            ]);
        return $response;
    }
}