# Projet DAW : MVC
## Prérequis
* PHP 8
* MySQL
* Apache

Si la nécessite d'utiliser **nginx** se présente, la configuration est :

```
location / {
  if (!-e $request_filename){
    rewrite ^(.*)$ /index.php break;
  }
}
```

## Installation :
* Déplacer l'ensemble du projet sur le serveur web
* Définir le dossier **public** comme root directory
* Créer une base de données **daw**
* Exécuter le fichier **daw.sql**
* Configurer les accès à la base de données dans le fichier **.env**
* Profiter du site :)

## Compte administrateur
Pour définir un compte administrateur, il faut, après s'être inscrit, se rendre dans la base de données puis modifier 
son rang :

|      Rng       | Chiffre |    Accès     |
|:--------------:|:-------:|:------------:|
|     Élève      |    0    | Consultation |
|   Enseignant   |    1    | Gérer cours  |
| Administrateur |    2    | Gérer membre |

## Identifiants de test
Le fichier **daw.sql** est donné avec des données de test. Les comptes accessibles sont :

| Identifiant | Mot de passe |      Rang      |
|:-----------:|:------------:|:--------------:|
|    admin    |     123      | Administrateur |
|    prof     |     123      |   Professeur   |
|    eleve    |     123      |     Eleve      |
|   eleve2    |     123      |     Eleve      |
|   eleve3    |     123      |     Eleve      |

Des mots de passe aussi simples ne sont pas accessibles pour les utilisateurs du site. Ils doivent respecter une 
certaine politique de sécurité. Cependant, pour les comptes tests, nous avons forcé l'insertion de ses identifiants 
en base de données.

## Explication brève des dossiers :
* **controllers**: Ensemble des contrôleurs
* **core**: Fichier interne pour le framework
* **models**: Listes des entités et des dépôts (ce qui arrive de la base de données)
* **public**: Dossier accessible par le client avec l'**index.php** et le dossier **assets**
* **scripts**: Différents outils mis à disposition
* **services**: Différents services, ce sont des classes sortes de bibliothèques.
* **vues**: Modèles HTML utilisés par les contrôleurs


### Différents scripts :
* **make_controller** : Permet de simplement créer un nouveau contrôleur
* **make_entity** : Création d'une nouvelle entité. Cela génère le .sql, l'entité et le dépôt.

## Auteurs :
 * DE OLIVEIRA Antonia - Backend
 * GEORGET Joris - Frontend
 * HOSSAIN SAIB Yohann - Backend
 * LEESTMANS Richard - Product Owner
 * NAAIMI Rida - Frontend
 * COUDERT-BOUDET Nicolas - Scrum Master


