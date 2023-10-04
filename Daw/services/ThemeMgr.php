<?php

namespace App\Service;

require_once("../core/AbstractController.php");

use App\Models\entities\AbstractService;
use App\Models\repositories\AccountRepository;

/**
 * Service pour la gestion centralisée des thèmes
 */
class ThemeMgr extends AbstractService
{
    /**
     * @var array|int[] Liste des thèmes accessibles
     */
    public array $themes = [0, 1];

    /**
     * Constructeur de la classe
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Changement de thème
     * @param int $theme Thème à définir
     * @param int $accountID Identifiant de l'utilisateur
     * @return void
     */
    public function changeTheme(int $theme, int $accountID = -1): void
    {
        if(!in_array($theme, $this->themes))
            return;

        if($accountID != 1) {
            if($account = $this->getRepository(AccountRepository::class)->findById($accountID)) {
                $account->setTheme($theme);
                $this->getDBManager()->update($account);
                $this->getDBManager()->flush();
            }
        }

        setcookie("themeID", $theme, 0, "/");
    }

    /**
     * Renvoie le thème de la personne connectée (si non connectée -> par cookie)
     * @return int Identifiant du thème utilisé
     */
    public function getTheme(): int
    {
        $accountID = $_SESSION["account_id"] ?? -1;
        if($accountID !== -1) {
            if($account = $this->getRepository(AccountRepository::class)->findById($accountID)) {
                return $account->getTheme();
            }
        }
        return $_COOKIE["themeID"] ??= 0;
    }

}