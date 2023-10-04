<?php

namespace App\Service;

require_once("../core/AbstractService.php");

use App\Models\entities\AbstractService;
use App\Models\repositories\AccountRepository;
use DateTime;

/**
 * Service permettant la sécurisation des entrées utilisateur
 */
class InputDataChecker extends AbstractService
{
    /**
     * Constructeur de la classe
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Vérifie le format de l'email
     * @param string $email Email
     * @return bool Si l'email est valide ou non
     */
    public function isValidEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Si l'utilisateur peut utiliser l'email (elle doit être unique)
     * @param string $email Adresse email désirée
     * @return bool Si l'email peut-être utilisé
     */
    public function canUseEmail(string $email): bool {
        // if is valid address :
        if (!$this->isValidEmail($email))
            return false;
        // if not use
        return !$this->getRepository(AccountRepository::class)->findOneBy(["email" => $email]);
    }

    /**
     * Si l'utilisateur peut utiliser cet identifiant (il doit être unique)
     * @param string $login
     * @return bool
     */
    public function canUseLogin(string $login): bool {
        // Between 5 and 25 chars :
        if(strlen($login) < 4 || strlen($login) > 25)
            return false;
        // is alphanumeric:
        if(!ctype_alnum($login))
            return false;
        // if not use:
        return !$this->getRepository(AccountRepository::class)->findOneBy(["login" => $login]);
    }

    /**
     * Si l'utilisateur peut utiliser ce mot de passe
     * @param string $password Mot de passe voulu
     * @return bool Si il peut être utilisé ou non
     */
    public function canUsePassword(string $password): bool {
        // between 8 and 25 chars:
        if(strlen($password) < 9 || strlen($password) > 25)
            return false;

        // at least one uppercase:
        if(!preg_match('/[A-Z]/', $password))
            return false;

        // at least one lowercase
        if(!preg_match('/[a-z]/', $password))
            return false;
        // at least one number
        if(!preg_match('/\d/', $password))
            return false;

        return true;
    }

    /**
     * Si la taille de la chaine est comprise entre les deux bornes (incluses)
     * @param string $string Chaine à vérifier
     * @param int $to Limite basse
     * @param int $limit Limite haute
     * @return bool Si la longueur correspond
     */
    public function isNameBetween(string $string, int $to, int $limit): bool {
        return strlen($string) >= $to && strlen($string) <= $limit;
    }

    /**
     * Pas encore implémenté
     * @param string $tel Numéro de téléphone
     * @return bool Si le téléphone est valide ou non
     */
    public function canUseTel(string $tel): bool {
        return true;
    }

    /**
     * Si ce rang est un rang valide
     * @param int $rank Rang désiré
     * @return bool Si le rang est valide ou non
     */
    public function canUseRank(int $rank): bool {
        return $rank < 3 && $rank > -1;
    }

    /**
     * Si l'utilisateur peut utiliser cette date
     * → Pour le moment, c'est une simple conversion
     * @param string $rawDate Date en string
     * @return bool|DateTime Date si convertie, sinon false
     */
    public function canUseDate(string $rawDate): bool|DateTime {
        return DateTime::createFromFormat("Y-m-d", $rawDate);
    }

    /**
     * Sécurise un string quant à une future injection par le biais du moteur de template
     * @param string $work Chaîne (référence)
     * @param bool $ret Si la valeur doit être retournée
     * @return string|null
     */
    public function sanitizeString(string& $work, bool $ret = false): null|string {
        $work = htmlspecialchars($work);
        $work = str_replace('{', '&#123;', $work);
        $work = str_replace('}', '&#125;', $work);
        if($ret)
            return $work;
        return null;
    }

}