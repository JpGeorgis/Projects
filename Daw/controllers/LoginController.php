<?php

namespace App\Controller;

require_once('../core/AbstractController.php');

use App\HTTP\Request;
use App\HTTP\Response;
use App\Models\entities\Account;
use App\Models\repositories\AccountRepository;
use App\Service\InputDataChecker;
use App\Service\SessionMgr;
use App\Service\ThemeMgr;

/**
 * Contrôleur pour gérer la connexion et la deconnxion
 */
class LoginController extends AbstractController
{
    /**
     * @var string Erreurs lors de l'enregistrement
     */
    private string $errorsRegister = "";
    /**
     * @var string Erreur lors de la connexion
     */
    private string $errorsLogin = "";
    /**
     * @var ThemeMgr|null Service permettant de gérer le thème
     */
    private ?ThemeMgr $themeMgr;


    /**
     * Construction de la classe
     */
    public function __construct() {
        parent::__construct();
        $this->routes["/login\/action/"] = "loginAction";
        $this->routes["/login/"] = "login";
        $this->routes["/logout/"] = "disconnect";
    }

    /**
     * Initialisation des services
     * @return void
     */
    public function init(): void {
        $this->themeMgr = $this->get(ThemeMgr::class);
    }

    /**
     * Processus d'enregistrement.
     * @param Request $r Requête client
     * @return bool Si l'inscription s'est bien déroulée
     */
    private function registerProcess(Request $r): bool {
        $out = false;
        $login = $r->get("POST", "login") ?? "";
        $password = $r->get("POST", "password") ?? "";
        $confirm = $r->get("POST", "confirm") ?? "";
        $born = $r->get("POST", "born") ?? "";
        $tel = $r->get("POST", "phone") ?? "";
        $firstName = $r->get("POST", "firstname") ?? "";
        $lastName = $r->get("POST", "lastname") ?? "";
        $email = $r->get("POST", "mail") ?? "";

        if($password !== $confirm) {
            $this->errors .= "Les mots de passe ne correspondent pas<br/>";
            $out = true;
        }
        $checker = $this->get(InputDataChecker::class);

        if(!$checker->canUseEmail($email)) {
            $this->errors .= "L'email n'est pas valide ou déjà utilisée<br/>";
                $out = true;
        }
        if(!$checker->canUseLogin($login)) {
            $this->errors .= "Nom d'utilisateur invalide ou déjà utilisé<br/>";
            $out = true;
        }
        if(!$checker->canUsePassword($password)){
            $this->errors .= "Ce mot de passe ne peut être utilisé. <br/>";
            $out = true;
        }
        if(!$checker->isNameBetween($firstName, 2, 25)) {
            $this->errors .= "Le prénom ne semble pas correct... <br/>";
            $out = true;
        }
        if(!$checker->isNameBetween($lastName, 2, 25)) {
            $this->errors .= "Le nom ne semble pas correct... <br/>";
            $out = true;
        }
        if(!$checker->canUseDate($born)) {
            $this->errors .= "Date de naissance invalide. <br/>";
            $out = true;
        }
        if(!$checker->canUseTel($tel)) {
            $this->errors = "Téléphone invalide. <br />";
            $out = true;
        }

        $checker->sanitizeString($lastName);
        $checker->sanitizeString($firstName);


        if(!$out) {
            $new = new Account();
            $new->setRank(Account::$ACCOUNT_RANK_GUEST)
            ->setPassword($this->getRepository(AccountRepository::class)->hashPassword($password))
            ->setLogin($login)->setEmail($email)
            ->setBornDate(\DateTime::createFromFormat("Y-m-d", $born))->setRegisterDate(new \DateTime())
            ->setLastname($lastName)->setFirstname($firstName)->setTel($tel);
            $this->getDBManager()->add($new);
            $this->getDBManager()->flush();
        }

        return $out;
    }

    /**
     * Processus de connexion
     * @param Request $r Requête client
     * @return bool Si la connexion s'est bien déroulée
     */
    private function loginProcess(Request $r): bool {
        $login = $r->get("POST", "login");
        $pass = $r->get("POST", "password");
        if(!$login || !$pass)
            return true;

        $repo = $this->getRepository(AccountRepository::class);
        $account = $repo->findOneBy([
            "login" => $login,
            "password" => $repo->hashPassword($pass)
            ]);
        if(!$account)
            $account = $repo->findOneBy([
                "email" => $login,
                "password" => $repo->hashPassword($pass)
            ]);

        if(!$account) {
            $this->errorsLogin .= "Identifiants incorrects <br />";
            return false;
        }

        // Ban
        if($account->getRank() === Account::$ACCOUNT_RANK_BAN) {
            $this->errorsLogin .= "Identifiants incorrects <br />";
            return false;
        }

        $session = $this->get(SessionMgr::class);
        $session->connect($account);

        $this->redirect("home/"); // go to home if connected
        return false;
    }

    /**
     * Page qui reçoit la demande de connexion
     * @param Request $request Connexion client
     * @return Response Réponse retournée au client
     */
    public function loginAction(Request $request): Response
    {
        if($request->get("POST", "submitLogin"))
            $this->loginProcess($request);
        elseif($request->get("POST", "submitRegister"))
            $this->registerProcess($request);
        return $this->login($request);
    }

    /**
     * Page de connexion
     * @return Response Réponse retournée au client
     */
    public function login(): Response {
        $response = new Response();
        $session = $this->get(SessionMgr::class);
        $connected = $session->isConnected();
        if($connected)
            $this->redirect("home/");

        $errRegister = "";
        $errLogin = "";
        if($this->errorsRegister)
            $errRegister = '<p class="texte">'.$this->errorsRegister.'</p>';
        if($this->errorsLogin)
            $errLogin = '<p class="texte">'.$this->errorsLogin.'</p>';

        $response->render("login.html",
            [
                "errors_register" => $errRegister,
                "errors_login" => $errLogin,
                "connected" => $connected,
                "isAdmin" => $session->isAdmin(),
                "isTeacher" => $session->isTeacher(),
                "theme" => $this->themeMgr->getTheme()
            ]
        );
        return $response;
    }

    /**
     * Page de déconnexion (redirection immédiate)
     * @return Response Réponse client
     */
    public function disconnect(): Response {
        $this->get(SessionMgr::class)->disconnect();
        $this->redirect("home/");
        return new Response();
    }
}