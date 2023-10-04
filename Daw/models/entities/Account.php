<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");


/**
 * Entité qui modélise un compte en base de données
 */
class Account extends Entity
{
    /**
     * @var string Identifiant du compte
     */
	private string $login = "";
    /**
     * @var string Mot de passe du compte (jamais stocké en clair)
     */
	private string $password = "";
    /**
     * @var string Adresse email du compte
     */
	private string $email = "";
    /**
     * @var DateTime Date d'enregistrement du compte
     */
	private DateTime $registerDate;
    /**
     * @var DateTime Date de naissance de l'utilisateur
     */
	private DateTime $bornDate;
    /**
     * @var int Rang du compte
     */
	private int $rank = 0;
    /**
     * @var string Prénom de l'utilisateur
     */
    private string $firstname = "";
    /**
     * @var string Nom de famille de l'utilisateur
     */
    private string $lastname = "";
    /**
     * @var string Téléphone de l'utilisateur
     */
    private string $tel = "";
    /**
     * @var int Thème utilisé par l'utilisateur
     */
    private int $theme = 0;

    /**
     * @var int ID du rang des utilisateurs bannis
     */
    public static int $ACCOUNT_RANK_BAN = -1;
    /**
     * @var int ID du rang des membres lambdas
     */
    public static int $ACCOUNT_RANK_GUEST = 0;
    /**
     * @var int ID du rang des professeurs
     */
    public static int $ACCOUNT_RANK_PROF = 1;
    /**
     * @var int ID du rang des administrateurs
     */
    public static int $ACC0UNT_RANK_ADMIN = 2;

    /**
     * Retourne l'identifiant de l'utilisateur
     * @return string Identifiant de l'utilisateur
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Définit l'identifiant de l'utilisateur
     * @param string $login Identifiant de l'utilisateur
     * @return $this Instance courante (fluent)
     */
    public function setLogin(string $login): Account
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Retourne le mot de passe du compte (hash)
     * @return string Mot de passe du compte (hash)
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe du compte. Mot de passe hashé seulement
     * @param string $password Hash du mot de passe
     * @return $this Instance courante (fluent)
     */
    public function setPassword(string $password): Account
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Retourne l'email de l'utilisateur
     * @return string Email de l'utilisateur
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Définit l'email de l'utilisateur
     * @param string $email Email de l'utilisateur
     * @return $this Instance courante (fluent)
     */
    public function setEmail(string $email): Account
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Retourne la date d'enregistrement du compte
     * @return DateTime Date d'enregistrement
     */
    public function getRegisterDate(): DateTime
    {
        return $this->registerDate;
    }

    /**
     * Définit la date d'enregistrement du compte
     * @param DateTime $registerDate Date d'enregistrement
     * @return $this Instance courante (fluent)
     */
    public function setRegisterDate(DateTime $registerDate): Account
    {
        $this->registerDate = $registerDate;
        return $this;
    }

    /**
     * Renvoie la date de naissance de l'utilisateur
     * @return DateTime Date de naisance
     */
    public function getBornDate(): DateTime
    {
        return $this->bornDate;
    }

    /**
     * Définit la date de naissance de l'utilisateur
     * @param DateTime $bornDate Date de naissance
     * @return $this Instance courante (fluent)
     */
    public function setBornDate(DateTime $bornDate): Account
    {
        $this->bornDate = $bornDate;
        return $this;
    }

    /**
     * Renvoie le rang de l'utilisateur
     * @return int Rang de l'utilisateur
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * Définit le rang de l'utilisateur
     * @param int $rank Rang de l'utilisateur
     * @return $this Instance courante (fluent)
     */
    public function setRank(int $rank): Account
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * Renvoie le prénom de l'utilisateur
     * @return string Prénom de l'utilisateur
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Définit le prénom de l'utilisateur
     * @param string $firstname Prénom de l'utilisateur
     * @return $this Instance courante (fluent)
     */
    public function setFirstname(string $firstname): Account
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Renvoie le nom de famille de l'utilisateur
     * @return string Nom de famille de l'utilisateur
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Définit le nom de l'utilisateur
     * @param string $lastname Nom de famille
     * @return $this Instance courante (fluent)
     */
    public function setLastname(string $lastname): Account
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Renvoie le téléphone de l'utilisateur
     * @return string Téléphone
     */
    public function getTel(): string
    {
        return $this->tel;
    }

    /**
     * Définit le numéro de téléphone de l'utilisateur
     * @param string $tel Numéro de téléphone
     * @return $this Instance courante (fluent)
     */
    public function setTel(string $tel): Account
    {
        $this->tel = $tel;
        return $this;
    }

    /**
     * Définit le thème de l'utilisateur
     * @param int $theme Thème à utiliser
     * @return $this Instance courante (fluent)
     */
    public function setTheme(int $theme): Account
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Retourne le thème utilisé par l'utilisateur
     * @return int Thème utilisé
     */
    public function getTheme(): int
    {
        return $this->theme;
    }

    /**
     * Description SQL de l'objet
     * @param int $mode Mode voulu (DELETE, CREATE, UPDATE)
     * @return Query Query demandée
     */
    public function toSql(int $mode): Query
    {
        return match ($mode) {
            ACTION_TYPE::DELETE => new Query("DELETE FROM account WHERE id=?", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "INSERT INTO account (login, password, email, register_date, born_date, rank, firstname, lastname, tel, theme) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $this->getLogin(), $this->getPassword(), $this->getEmail(), $this->getRegisterDate()->format("Y-m-d H:i:s"),
                    $this->getBornDate()->format("Y-m-d H:i:s"), $this->getRank(), $this->getFirstname(),
                    $this->getLastname(), $this->getTel(), $this->getTheme()
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "UPDATE account SET login=?, password=?, email=?, register_date=?, born_date=?, rank=?, firstname=?, lastname=?, tel=?, theme=? WHERE id=?",
                [
                    $this->getLogin(), $this->getPassword(), $this->getEmail(), $this->getRegisterDate()->format("Y-m-d H:i:s"),
                    $this->getBornDate()->format("Y-m-d H:i:s"), $this->getRank(),
                    $this->getFirstname(), $this->getLastname(), $this->getTel(), $this->getTheme(),
                    $this->getId()
                ]
            ),

            default => new Query("") // Default: empty query
        };
    }

    /**
     * Retourne la liste des attributs de l'entité
     * @return array Liste des attributs
     */
    public function getAttributes(): array {
        return get_object_vars($this);
    }
}
