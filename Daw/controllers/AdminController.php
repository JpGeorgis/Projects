<?php
namespace App\Controller;

require_once("../core/AbstractController.php");

use App\HTTP\Request;
use App\HTTP\Response;
use App\Models\entities\Account;
use App\Models\repositories\AccountRepository;
use App\Service\InputDataChecker;
use App\Service\SessionMgr;
use App\Service\ThemeMgr;
use DateTime;

/**
 * Contrôleur contenant les pages d'administration.
 * Essentiellement pour gérer les comptes utilisateur.
 */
class AdminController extends AbstractController
{
    /**
     * @var SessionMgr|null Service permettant la gestion des sessions.
     */
    private ?SessionMgr $session;
    /**
     * @var ThemeMgr|null Service permettant la gestion des thèmes.
     */
    private ?ThemeMgr $themeMgr;
    /**
     * @var InputDataChecker|null Module de sécurité. Il permet de sécuriser les entrées.
     */
    private ?InputDataChecker $checker;
    /**
     * @var string Erreurs temporaires
     */
    private string $errors;

    /**
     * Constructeur du contrôleur
     */
    public function __construct() {
        parent::__construct();
        $this->session = null;
        $this->themeMgr = null;
        $this->checker = null;
        $this->errors = "";
        $this->routes["/admin\/account\/(?<account_id>\d+)/"] = "editAccount";
        $this->routes["/admin\/account\/add/"] = "addAccount";
        $this->routes["/admin\/account/"] = "manageAccount";
    }

    /**
     * Initialisation des services
     * @return void
     */
    public function init(): void {
        $this->session = $this->get(SessionMgr::class);
        $this->themeMgr = $this->get(ThemeMgr::class);
        $this->checker = $this->get(InputDataChecker::class);
    }

    /**
     * Ajout d'un compte par l'administrateur.
     * @param Request $r Requête client
     * @return Response Réponse avec la page de retour
     */
    public function addAccount(Request $r): Response {
        if(!$this->session->isAdmin()) {
            $this->redirect("home/");
            return new Response("");
        }

        if($r->get("POST", "submit")) {
            $lastname = $r->get("POST", "lastname");
            $firstname = $r->get("POST", "firstname");
            $email = $r->get("POST", "mail");
            $login = $r->get("POST", "login");
            $tel = $r->get("POST", "phone");
            $password = $r->get("POST", "password");
            $born = $r->get("POST", "born");
            $rank = intval($r->get("POST", "rank"));

            $out = false;

            if(!$this->checker->canUseEmail($email)) {
                $this->errors .= "L'email n'est pas valide ou déjà utilisée<br/>";
                $out = true;
            }
            if(!$this->checker->canUseLogin($login)) {
                $this->errors .= "Nom d'utilisateur invalide ou déjà utilisé<br/>";
                $out = true;
            }
            if(!$this->checker->canUsePassword($password)){
                $this->errors .= "Ce mot de passe ne peut être utilisé. <br/>";
                $out = true;
            }
            if(!$this->checker->isNameBetween($firstname, 2, 25)) {
                $this->errors .= "Le prénom ne semble pas correct... <br/>";
                $out = true;
            }
            if(!$this->checker->isNameBetween($lastname, 2, 25)) {
                $this->errors .= "Le nom ne semble pas correct... <br/>";
                $out = true;
            }
            if(!$this->checker->canUseTel($tel)) {
                $this->errors .= "Téléphone invalide. <br/>";
                $out = true;
            }
            if(!$this->checker->canUseDate($born)) {
                $this->errors .= "Date de naissance invalide. <br/>";
                $out = true;
            }
            if(!$this->checker->canUseRank($rank)) {
                $this->errors .= "Rang invalide. <br/>";
                $out = true;
            }

            $this->checker->sanitizeString($lastname);
            $this->checker->sanitizeString($firstname);

            if(!$out) {
                $repo = $this->getRepository(AccountRepository::class);
                $this->errors = "Compte crée avec succès ! <br/>";
                $account = new Account();
                $account->setRank($rank)
                    ->setLogin($login)
                    ->setEmail($email)
                    ->setBornDate(DateTime::createFromFormat("Y-m-d", $born))
                    ->setLastname($lastname)->setTel($tel)->setFirstname($firstname)
                    ->setPassword($repo->hashPassword($password))
                ->setRegisterDate(new DateTime());
                $this->getDBManager()->add($account);
                $this->getDBManager()->flush();
            }

        }


        $response = new Response();
        $response->render("admin/createAccount.html",
            [
                "connected" => $this->session->isConnected(),
                "isAdmin" => $this->session->isAdmin(),
                "isTeacher" => $this->session->isTeacher(),
                "theme" => $this->themeMgr->getTheme(),
                "errors" => $this->errors
            ]);
        return $response;
    }

    /**
     * Edition d'un compte par l'administrateur.
     * @param Request $r Requête client
     * @return Response Réponse affichée au client.
     */
    public function editAccount(Request $r): Response {
        if(!$this->session->isAdmin()) {
            $this->redirect("home/");
            return new Response("");
        }
        $accountID = intval($r->getURIParam("account_id"));
        $account = $this->getRepository(AccountRepository::class)->findById($accountID);
        if(!$account) {
            $this->redirect("/admin/account/");
            return new Response();
        }

        if($r->get("POST", "submit")) {
            $repo = $this->getRepository(AccountRepository::class);

            $lastname = $r->get("POST", "lastname");
            $firstname = $r->get("POST", "firstname");
            $email = $r->get("POST", "mail");
            $tel = $r->get("POST", "phone");
            $login = $r->get("POST", "login");
            $password = $r->get("POST", "password");
            $born = $r->get("POST", "born");
            $rank = intval($r->get("POST", "rank"));

            $out = false;

            if($account->getEmail() !== $email &&
                !$this->checker->canUseEmail($email)) {
                $this->errors .= "L'email n'est pas valide ou déjà utilisée<br/>";
                $out = true;
            }
            if($account->getLogin() !== $login &&
                !$this->checker->canUseLogin($login)) {
                $this->errors .= "Nom d'utilisateur invalide ou déjà utilisé<br/>";
                $out = true;
            }
            if($password !== ""
                &&!$this->checker->canUsePassword($password)){
                $this->errors .= "Ce mot de passe ne peut être utilisé. <br/>";
                $out = true;
            }
            if($account->getFirstname() !== $firstname &&
                !$this->checker->isNameBetween($firstname, 2, 25)) {
                $this->errors .= "Le prénom ne semble pas correct... <br/>";
                $out = true;
            }
            if($account->getLastname() !== $lastname &&
                !$this->checker->isNameBetween($lastname, 2, 25)) {
                $this->errors .= "Le nom ne semble pas correct... <br/>";
                $out = true;
            }
            if(!$this->checker->canUseDate($born)) {
                $this->errors .= "Date de naissance invalide. <br/>";
                $out = true;
            }
            if(!$this->checker->canUseTel($tel)) {
                $this->errors .= "Téléphone invalide. <br/>";
                $out = true;
            }
            if(!$this->checker->canUseRank($rank)) {
                $this->errors .= "Rang invalide. <br/>";
                $out = true;

            }

            $this->checker->sanitizeString($lastname);
            $this->checker->sanitizeString($firstname);

            if(!$out) {
                $this->errors = "Compte modifié avec succès ! <br/>";
                $account->setRank($rank)
                    ->setLogin($login)
                    ->setEmail($email)
                    ->setBornDate(DateTime::createFromFormat("Y-m-d", $born))
                    ->setLastname($lastname)->setTel($tel)->setFirstname($firstname);

                if($password !== "")
                    $account->setPassword($repo->hashPassword($password));

                $this->getDBManager()->update($account);
                $this->getDBManager()->flush();
            }
        }

        $response = new Response();
        $response->render("admin/editAccount.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->themeMgr->getTheme(),
            "account_id" => $account->getId(),
            "account_login" => $account->getLogin(),
            "account_firstname" => $account->getFirstname(),
            "account_lastname" => $account->getLastname(),
            "account_rank" => $account->getRank(),
            "account_mail" => $account->getEmail(),
            "account_phone" => $account->getTel(),
            "account_born" => $account->getBornDate()->format("Y-m-d"),
            "errors" => $this->errors
        ]);
        return $response;
    }

    /**
     * Retourne la vue sur la liste de tous les comptes
     * @return string Liste de tous les comptes (HTML)
     */
    private function getListOfAccount(): string {
        $accounts = $this->getRepository(AccountRepository::class)->findAll();
        $out = "";
        foreach($accounts as $account) {
            $out .= '<tr>';
            $out .= '<td class="texte">'.$account->getLogin().'</td>';
            $out .= '<td class="text2">'.$account->getEmail().'</td>';
            $out .= '<td><a href="{% route(/admin/account/'.$account->getId().'/) %}" class="modifButton">Modifier</a></td>';
            $out .= '</tr>';
            $out .= '<tr>';
        }

        return $out;
    }

    /**
     * Affichage de l'ensemble des comptes
     * @return Response Réponse envoyée au client
     */
    public function manageAccount(): Response {
        if(!$this->session->isAdmin()) {
            $this->redirect("home/");
            return new Response("");
        }
        $response = new Response();
        $response->render("admin/manageAccount.html",
            [
                "connected" => $this->session->isConnected(),
                "isAdmin" => $this->session->isAdmin(),
                "isTeacher" => $this->session->isTeacher(),
                "accounts" => $this->getListOfAccount(),
                "theme" => $this->themeMgr->getTheme()
            ]);
        return $response;
    }
}
