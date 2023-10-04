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

/**
 * Contrôleur pour les actions de l'utilisateur
 */
class UserController extends AbstractController
{
    /**
     * @var SessionMgr|null Service permettant la gestion des sessions.
     */
    private ?SessionMgr $session;
    /**
     * @var ThemeMgr|null Service permettant la gestion du thème.
     */
    private ?ThemeMgr $themeMgr;

    /**
     * Constructeur de la classe avec l'ensemble des routes.
     */
    public function __construct() {
        parent::__construct();
        $this->session = null;
        $this->themeMgr = null;
        $this->routes["/change\/theme/"] = "changeTheme";
        $this->routes["/change\/password/"] = "changePassword";
        $this->routes["/change\/email/"] = "changeEmail";
        $this->routes["/change\/login/"] = "changeLogin";
        $this->routes["/my\/account/"] = "myAccount";
        $this->routes["/delete\/account/"] = "deleteAccount";
    }

    /**
     * Initialisation des services
     * @return void
     */
    public function init(): void {
        $this->session = $this->get(SessionMgr::class);
        $this->themeMgr = $this->get(ThemeMgr::class);
    }

    /**
     * Procédure pour changer de mot de passe (compte connecté)
     * @param Request $r Requête client
     * @return Response Réponse (true/false)
     */
    public function changePassword(Request $r): Response {
        if(!$this->session->isConnected())
            return new Response("false");

        $password = $r->get("POST", "password");
        $oldPassword = $r->get("POST", "old_password");

        if(!$password || !$oldPassword)
            return new Response("false");

        // Update password:
        $accountID = $_SESSION["account_id"];
        $repo = $this->getRepository(AccountRepository::class);
        $account = $repo->findOneBy(
            [
                "password" => $repo->hashPassword($oldPassword),
                "id" => $accountID
            ]
        );

        // If can't find account
        if(!$account)
            return new Response("bad_old");

        if(!$this->get(InputDataChecker::class)->canUsePassword($password))
            return new Response("not_good");

        $account->setPassword($repo->hashPassword($password));
        $this->getDBManager()->update($account);
        $this->getDBManager()->flush();

        return new Response("true");

    }

    /**
     * Procédure pour changer le login (compte connecté)
     * @param Request $r Requête client
     * @return Response Réponse (true/false)
     */
    public function changeLogin(Request $r): Response {
        if(!$this->session->isConnected())
            return new Response("false");

        if(!($login = $r->get("POST", "login")))
            return new Response("false");

        // Update password:
        $accountID = $_SESSION["account_id"];
        $account = $this->getRepository(AccountRepository::class)->findById($accountID);

        // If can't find account
        if(!$account)
            return new Response("false");

        if($this->getRepository(AccountRepository::class)->findOneBy(["login" => $login]))
            return new Response("exists");

        if(!$this->get(InputDataChecker::class)->canUseLogin($login))
            return new Response("not_good");

        $account->setLogin($login);
        $this->getDBManager()->update($account);
        $this->getDBManager()->flush();

        $_SESSION["account_login"] = $account->getLogin();

        return new Response("true");

    }

    /**
     * Procédure pour changer d'email (compte connecté)
     * @param Request $r Requête client
     * @return Response Réponse retournée (true/false)
     */
    public function changeEmail(Request $r): Response {
        if(!$this->session->isConnected())
            return new Response("false");

        if(!($email = $r->get("POST", "email")))
            return new Response("false");

        // Update email:
        $accountID = $_SESSION["account_id"];
        $account = $this->getRepository(AccountRepository::class)->findById($accountID);

        // If can't find account
        if(!$account)
            return new Response("false");

        if($this->getRepository(AccountRepository::class)->findOneBy(["email" => $email]))
            return new Response("exists");

        if(!$this->get(InputDataChecker::class)->canUseEmail($email))
            return new Response("not_good");

        $account->setEmail($email);
        $this->getDBManager()->update($account);
        $this->getDBManager()->flush();

        $_SESSION["account_email"] = $account->getEmail();

        return new Response("true");
    }

    /**
     * Suppression d'un compte (connecté)
     * @return Response Réponse vide
     */
    public function deleteAccount(): Response {
        if($this->session->isConnected()) {
            $accountId = $_SESSION['account_id'];
            $account = $this->getRepository(AccountRepository::class)->findById($accountId);
            if($account) {
                $account->setRank(Account::$ACCOUNT_RANK_BAN);
                $this->getDBManager()->update($account);
                $this->getDBManager()->flush();
            }
        }
        $this->session->disconnect();
        $this->redirect("index/");
        return new Response("");
    }

    /**
     * Page de gestion du compte
     * @param Request $request Requête client
     * @return Response Réponse envoyée au client
     */
    public function myAccount(Request $request): Response {
        if(!$this->session->isConnected())  {
            $this->redirect("index/");
            return new Response();
        }

        $account = $this->getRepository(AccountRepository::class)->findById($_SESSION["account_id"]);
        if(!$account)  {
            $this->redirect("index/");
            return new Response();
        }

        $response = new Response();
        $response->render("account/myAccount.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->themeMgr->getTheme(),
            "login" => $account->getLogin(),
            "email" => $account->getEmail(),
            "born" => $account->getBornDate()->format("d/m/Y"),
            "phone" => $account->getTel(),
            "lastname" => $account->getLastname(),
            "firstname" => $account->getFirstname()

        ]);
        return $response;
    }

    /**
     * Change le thème du client
     * @param Request $r Requête client
     * @return Response Réponse retourner au client (vide ici)
     */
    public function changeTheme(Request $r): Response {
        $theme = (int)$r->get("POST", "theme") ?? 0;
        $sessionMgr = $this->get(SessionMgr::class);

        if($sessionMgr->isConnected()) {
            $account = $this->getRepository(AccountRepository::class)->findById($r->get("SESSION", "account_id"));
            $account->setTheme($theme);
            $this->getDBManager()->update($account);
            $this->getDBManager()->flush();
        }

       setcookie("themeID", $theme, 0, "/");

        return new Response("");
    }
}
