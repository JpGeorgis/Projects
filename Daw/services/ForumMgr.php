<?php

namespace App\Service;

require_once("../core/AbstractService.php");


use App\Models\entities\AbstractService;
use App\Models\entities\ForumMessage;
use App\Models\entities\ForumThread;
use App\Models\repositories\AccountRepository;
use App\Models\repositories\ForumMessageRepository;
use App\Models\repositories\ForumThreadRepository;
use DateTime;

/**
 * Service pour unifier les actions sur le forum
 */
class ForumMgr extends AbstractService
{
    /**
     * @var SessionMgr|null Service gérant les sessions
     */
    public ?SessionMgr $session;
    /**
     * @var InputDataChecker|null Service assurant la sécurité des entrés utilisateurs
     */
    public ?InputDataChecker $checker;

    /**
     * Constructeur du service
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Initialisation des autres services
     * @return void
     */
    public function init(): void {
        $this->session = $this->get(SessionMgr::class);
        $this->checker = $this->get(InputDataChecker::class);
    }

    /**
     * Ajoute un message à un fil de discussion
     * @param int $threadId Identifiant du fil de discussion
     * @param string $content Message à ajouter
     * @return void
     */
    public function addMessage(int $threadId, string $content): void {
        if($this->session->isConnected() && $this->checker->isNameBetween($content, 3, 3000)) {
            $this->checker->sanitizeString($content);

            $message = new ForumMessage();
            $message->setVisible(1)
                ->setDate(new DateTime())
                ->setAuthorId($_SESSION['account_id'])
                ->setContent($content)
                ->setThreadId($threadId);

            $this->getDBManager()->add($message);
            $this->getDBManager()->flush();
        }
    }

    /**
     * Renvoie la vue générale sur les fils de discussion
     * @return string Liste (HTML) des fils de discussion
     */
    public function getGlobalView(): string {
        $threads = $this->getRepository(ForumThreadRepository::class)->findAll();

        if(count($threads) === 0)
            return "<p>Ooh il n'y a rien ici ! </p>";

        $out = "";

        foreach($threads as $thread) {
            if(!$this->session->isAdmin() && $thread->getVisible() !== 1)
                continue;
            $author = $this->getRepository(AccountRepository::class)->findById($thread->getAuthor());
            $messages = $this->getRepository(ForumMessageRepository::class)->findBy(["thread_id" => $thread->getId()]) ?? [];

            // sort message :
            usort($messages, fn($a, $b) => $a->getDate()->getTimestamp() - $b->getDate()->getTimestamp());
            $message = $messages[0] ?? null;
            $lastMessageAuthorName = "Invité";

            if($message) {
                if($account = $this->getRepository(AccountRepository::class)->findById($message->getAuthorid()))
                    $lastMessageAuthorName = $account->getLogin();
            }

            $out .= "<tr class=\"fil\" onclick=\"location.href='{% route(forum/thread/".$thread->getId().") %}'\">";
            $out .= '<td class="image"><img src="{% assets(images/logo_topic.png) %}" alt="logo_topic" class="logo_topic"></td>';
            $out .= '<td class="sujet"><strong class="postTitle">'.$thread->getName().'</strong><br />';
            $out .= 'créé par <span class="nom_auteur">';
            $out .= $author ? $author->getLogin() : "Invité";
            $out .= '</span> le '.$thread->getCreateDate()->format("d/m/Y");
            $out .= "</td>";
            $out .= '<td class="reponses">'.count($messages).'</td>';
            $out .= '<td class="vues">'.$thread->getVues().'</td>';

            if($message) {
                $out .= '<td class="der_message">par <span class="nom_auteur">'.$lastMessageAuthorName.'</span><br />';
                $out .= 'le '.$message->getDate()->format("d/m/Y");
            } else {
                $out .= '<td class="der_message">Aucun message</span><br />';
            }
            if($this->session->isAdmin()) {
                if($thread->getVisible() == 1)
                    $out .= '<br /> <a id="' . $thread->getId() . '" href="#" class="nom_autheur deleteButton">Supprimer</a>';
                else
                    $out .= '<br /> <a id="' . $thread->getId() . '" class="nom_autheur">Supprimé</a>';
            }
            $out .= '</td>';
            $out .= "</tr>";
        }

        return $out;

    }

    /**
     * Génère la liste des messages d'un fil de discussion
     * @param int $threadID Identifiant du fil de discussion
     * @return string Liste des messages (HTML)
     */
    public function getThreadMessages(int $threadID): string {
        if(!$this->getRepository(ForumThreadRepository::class)->findById($threadID))
            return "";

        $messages = $this->getRepository(ForumMessageRepository::class)->findBy(["thread_id" => $threadID]) ?? [];
        if(count($messages) === 0)
            return "<p>OOoops, on a rien trouvé ici !</p>";
        // sort message :
        usort($messages, fn($a, $b) => $a->getDate()->getTimestamp() - $b->getDate()->getTimestamp());

        $out = "";

        foreach($messages as $message) {
            if($message->getVisible() == 0 && !$this->session->isAdmin())
                continue;
            $author = $this->getRepository(AccountRepository::class)->findById($message->getAuthorId());
            $messageAuthorName = "Invité";
            if($author)
                $messageAuthorName = $author->getLogin();

            $out .= '<tr class="fil">';
            $out .= '<td class="image"><img src="{% assets(images/logo_topic.png) %}" alt="logo_topic" class="logo_topic"></td>';
            $out .= '<td class="sujet">'.$message->getContent().'</td>';
            $out .= '<td class="responses"></td>';
            $out .= '<td class="vues"></td>';
            $out .= '<td class="der_message">par <span class="nom_auteur">'.$messageAuthorName.'</span><br />';
            $out .= 'le '.$message->getDate()->format("d/m/Y");
            if($this->session->isAdmin()) {
                if($message->getVisible() == 1)
                    $out .= '<br /> <a id="' . $message->getId() . '" href="#" class="nom_autheur deleteButton">Supprimer</a>';
                else
                    $out .= '<br /> <a id="' . $message->getId() . '" class="nom_autheur">Supprimé</a>';
            }
            $out .= '</td>';
            $out .= '</tr>';
        }

        return $out;
    }

    /**
     * Créer un fil de discussion
     * @param string $title Titre du fil de discussion
     * @param string $content Contenu du premier message
     * @return int Retourne l'identifiant du futur fil de discussion. -1 Si erreur
     */
    public function createThread(string $title, string $content): int {
        $threadID = -1;
        if($this->checker->isNameBetween($title, 3, 30)) {
            if($this->checker->isNameBetween($content, 10, 2000)) {
                $this->checker->sanitizeString($title);
                $this->checker->sanitizeString($content);

                // Create thread :
                $thread =  new ForumThread();
                $thread->setVisible(1)
                    ->setAuthor($_SESSION['account_id'])
                    ->setName($title)
                    ->setCreateDate(new DateTime())
                    ->setVues(1);

                $this->getDBManager()->add($thread);
                $threadID = $this->getDBManager()->flush()[0];

                // First message :
                $message = new ForumMessage();
                $message->setVisible(1)
                    ->setContent($content)
                    ->setDate(new DateTime())
                    ->setAuthorId($_SESSION['account_id'])
                    ->setThreadId($threadID);
                $this->getDBManager()->add($message);
                $this->getDBManager()->flush();
            }
        }
        return $threadID;
    }

}