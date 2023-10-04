<?php

namespace App\Service;

require_once("../core/AbstractService.php");

use App\Models\entities\AbstractService;
use App\Models\entities\Account;
use App\Models\repositories\AccountRepository;
use DateTime;

/**
 * Service centralisant la gestion des sessions
 */
class SessionMgr extends AbstractService
{
    /**
     * Constructeur du service
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Renvoie si la personne est connectée au site
     * @return bool Connectée ou non
     */
    public function isConnected(): bool {
        if(empty($_SESSION["account_id"]))
            return false;

        // if isn't banned :
        $account = $this->getDBManager()->getRepository(AccountRepository::class)->findById($_SESSION["account_id"]);
        if(!$account)
            return false;
        if($account->getRank() == Account::$ACCOUNT_RANK_BAN)
            return false;

        $current_date = new DateTime();
        if($_SESSION["last_activity"]->format("U") < $current_date->format("U")-10800) {
            $this->disconnect();
            return false;
        }

        $_SESSION["last_activity"] = new DateTime();
        return true;
    }

    /**
     * Si la personne connectée est un administrateur
     * @return bool Administrateur ou non
     */
    public function isAdmin(): bool {
        if(!$this->isConnected())
            return false;
        return $this->getRepository(AccountRepository::class)->findOneBy(
            array(
                "id" => $_SESSION["account_id"]
            )
        )->getRank() === Account::$ACC0UNT_RANK_ADMIN;
    }

    /**
     * Si la personne connectée est un professeur
     * @return bool Professeur ou non
     */
    public function isTeacher(): bool {
        if(!$this->isConnected())
            return false;

        return $this->getRepository(AccountRepository::class)->findOneBy(
                array(
                    "id" => $_SESSION["account_id"]
                )
            )->getRank() >= Account::$ACCOUNT_RANK_PROF;
    }

    /**
     * Processus de connexion d'une personne
     * @param Account $account Dompte a connecté
     * @return bool Si la connexion s'est déroulée correctement
     */
    public function connect(Account $account): bool {
        if($this->isConnected())
            return false;

        $_SESSION["account_id"] = $account->getId();
        $_SESSION["account_login"] = $account->getLogin();
        $_SESSION["account_email"] = $account->getEmail();
        $_SESSION["last_activity"] = new DateTime();
        return true;
    }

    /**
     * Déconnexion d'une personne
     * @return void
     */
    public function disconnect(): void {
        session_destroy();
        $_SESSION = array();
    }
}

