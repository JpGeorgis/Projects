<?php

namespace App\Controller;

require_once("../core/AbstractController.php");

use App\HTTP\Request;
use App\HTTP\Response;
use App\Models\entities\Classes;
use App\Models\entities\Files;
use App\Models\entities\Mcq;
use App\Models\repositories\AccountRepository;
use App\Models\repositories\ClassesRepository;
use App\Models\repositories\FilesRepository;
use App\Models\repositories\McqRepository;
use App\Models\repositories\SubjectRepository;
use App\Service\InputDataChecker;
use App\Service\QuizReader;
use App\Service\SessionMgr;
use App\Service\ThemeMgr;
use DateTime;
use DOMDocument;


/**
 * Contrôleur pour les actions des professeurs
 */
class TeacherController extends AbstractController
{
    /**
     * @var SessionMgr|null Service permettant la gestion des sessions.
     */
    private ?SessionMgr $session;
    /**
     * @var ThemeMgr|null Service permettant la gestion des thèmes.
     */
    private ?ThemeMgr $theme;
    /**
     * @var InputDataChecker|null Service pour sécuriser les entrées
     */
    private ?InputDataChecker $checker;


    /**
     * Constructeur de la classe
     */
    public function __construct() {
        parent::__construct();
        $this->session = null;
        $this->theme = null;
        $this->checker = null;
        $this->routes["/teacher\/add\/class/"] = "addClass";
        $this->routes["/teacher\/add\/mcq/"] = "addMCQ";
        $this->routes["/teacher\/add\/file\/(?<class_id>\d+)/"] = "addFile";
        $this->routes["/teacher\/edit\/class\/(?<class_id>\d+)/"] = "editClass";
        $this->routes["/teacher\/delete\/file/"] = "deleteFile";
        $this->routes["/teacher\/delete\/mcq/"] = "deleteMcq";
        $this->routes["/teacher\/view\/mcq/"] = "viewMcq";
        $this->routes["/teacher/"] = "teacher";
    }

    /**
     * Initialisation des services
     * @return void
     */
    public function init(): void {
        $this->session = $this->get(SessionMgr::class);
        $this->theme = $this->get(ThemeMgr::class);
        $this->checker = $this->get(InputDataChecker::class);
    }

    /**
     * Processus d'ajout d'un questionnaire QCM
     * @param array $data Données envoyées par l'utilisateur
     * @return void
     */
    private function addMCQProcess(array $data): void {
        $id = intval($data["subject"] ?? -1);

        // if bad subject
        if(!($this->getRepository(SubjectRepository::class)->findById($id))) {
            $this->redirect("teacher/view/mcq");
            return;
        }

        move_uploaded_file($_FILES["xml"]["tmp_name"], "assets/mcq/temp.xml");

        $dom = new DOMDocument();
        $dom->load("assets/mcq/temp.xml");

        if(!$dom->validate())
            return;

        $hash = hash_file('md5', "assets/mcq/temp.xml");
        rename("assets/mcq/temp.xml", "assets/mcq/".$hash.".xml");

        $mcq = new Mcq();
        $mcq->setSubjectId($id);
        $mcq->setPath($hash.".xml");
        $mcq->setOwnerId(intval($_SESSION["account_id"]));
        $this->getDBManager()->add($mcq);
        $this->getDBManager()->flush();
        $this->redirect("teacher/view/mcq");
    }

    /**
     * Page pour ajouter un QCM
     * @param Request $request Requête client
     * @return Response Réponse retournée au client
     */
    public function addMCQ(Request $request): Response {
        if (!$this->session->isConnected() || !$this->session->isTeacher()) {
            $this->redirect("login/");
            return new Response("");
        }

        if($request->get("POST", "submit"))
            $this->addMCQProcess($request->getPost());

        $subjects = $this->getRepository(SubjectRepository::class)->findAll();
        $subjectsList = "";
        foreach($subjects as $subject){
            $subjectsList .= '<option value="'.$subject->getId().'">'.$subject->getName().'</option>';
        }


        $response = new Response();
        $response->render("teacher/addMCQ.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "subjects" => $subjectsList

        ]);

        return $response;
    }

    /**
     * Processus d'ajout d'un cours
     * @param array $post Données envoyées par l'utilisateur (POST)
     * @return void
     */
    private function addClassProcess(array $post): void {
        $id = intval($post["subject"] ?? -1);
        $difficulty = intval($post["difficulty"]);
        $name = $post["subjectName"];
        $resume = $post["subjectResume"];
        $useImage = True;

        // if bad subject
        if(!($this->getRepository(SubjectRepository::class)->findById($id))) {
            $this->redirect("teacher/");
            return;
        }

        // if name not correct
        if(!$this->checker->isNameBetween($name, 3, 30))
            $name = "Cours sans nom";

        // is valid image
        if (!$_FILES['subjectImage']["tmp_name"] || exif_imagetype($_FILES['subjectImage']["tmp_name"]) != (IMAGETYPE_JPEG || IMAGETYPE_GIF || IMAGETYPE_PNG))
            $useImage = False;

        // Defined difficulty
        if($difficulty < 0 || $difficulty > 3)
            $difficulty = 1;

        $extension = strtolower(pathinfo($_FILES["subjectImage"]["name"],PATHINFO_EXTENSION));
        if($useImage) {
            $newName = hash_file('md5', $_FILES["subjectImage"]["tmp_name"]).'.'.$extension;
            $target = "assets/images/uploads/".$newName;
            move_uploaded_file($_FILES["subjectImage"]["tmp_name"], $target);
            $newPath = "uploads/".$newName;
        } else {
            $newPath = "default.jpg";
        }

        $this->checker->sanitizeString($name);
        $this->checker->sanitizeString($resume);

        $class = new Classes();
        $class->setSubjectId($id);
        $class->setOwnerId($_SESSION['account_id']);
        $class->setDescription($resume);
        $class->setPicture($newPath);
        $class->setName($name);
        $class->setCreation(new \DateTime());
        $class->setDifficulty($difficulty);

        $this->getDBManager()->add($class);
        $this->getDBManager()->flush();
        $this->redirect("teacher/");
    }

    /**
     * Page d'ajout d'un nouveau cours
     * @param Request $request Requête client
     * @return Response Réponse retournée au client
     */
    public function addClass(Request $request): Response {
        if (!$this->session->isConnected() || !$this->session->isTeacher()) {
            $this->redirect("login/");
            return new Response("");
        }

        if($request->get("POST", "submit"))
            $this->addClassProcess($request->getPost());

        $subjects = $this->getRepository(SubjectRepository::class)->findAll();
        $subjectsList = "";
        foreach($subjects as $subject){
            $subjectsList .= '<option value="'.$subject->getId().'">'.$subject->getName().'</option>';
        }

        $response = new Response();
        $response->render("teacher/addClass.html",  [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "subjects" => $subjectsList
        ]);

        return $response;
    }

    /**
     * Traitement pour la suppression d'un fichier
     * @param Request $request Requête client
     * @return Response Réponse retourne (true/false)
     */
    public function deleteFile(Request $request): Response {
        if(!$request->get("POST", "id"))
            return new Response("false");

        if (!$this->session->isConnected() || !$this->session->isTeacher())
            return new Response("false");

        $id = intval($request->get("POST", "id"));
        if(!($file = $this->getRepository(FilesRepository::class)->findById($id)))
            return new Response("false");

        if(!($class = $this->getRepository(ClassesRepository::class)->findById(intval($file->getClassId()))))
            return new Response("false");

        if($class->getOwnerId() !== $_SESSION["account_id"] &&! $this->session->isAdmin())
            return new Response("false");

        $this->getDBManager()->delete($file);
        $this->getDBManager()->flush();
        return new Response("true");
    }

    /**
     * Affichage des cours accessible par l'enseignant (tous les cours pour les admins)
     * @param Request $request Requête client
     * @return Response Réponse retournée au client
     */
    public function teacher(Request $request): Response
    {
        if (!$this->session->isConnected() || !$this->session->isTeacher()) {
            $this->redirect("login/");
            return new Response("");
        }

        $classes = $this->getRepository(ClassesRepository::class)->findAll();
        $keep = [];
        if ($this->session->isAdmin()) {
            $keep = $classes;
        } else {
            foreach($classes as $class)
                if($class->getOwnerId() === $_SESSION['account_id'])
                    $keep[] = $class;
        }

        $grid = "";

        foreach($keep as $row) {
            if(!($author = $this->getRepository(AccountRepository::class)->findById($row->getOwnerId())))
                $author = "Un humain... Sûrement";
            else
                $author = $author->getLogin();
            $grid .= '<tr>';
            $grid .= '<td class="texte">'.$row->getName().'</td>';
            $grid .= '<td class="text2"> '.$author.' </td>';
            $grid .= '<td><a href="{% route(/teacher/edit/class/'.$row->getId().'/) %}" class="modifButton">Voir</a></td>';
            $grid .= '</tr>';
            $grid .= '<tr>';
        }


        $response = new Response();

        $response->render("teacher/viewClasses.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "grid" => $grid
        ]);
        return $response;
    }

    /**
     * Page permettant d'éditer un cours (version prof)
     * @param Request $request Requête client
     * @return Response Réponse renvoyée
     */
    public function editClass(Request $request): Response {
        if (!$this->session->isConnected() || !$this->session->isTeacher()) {
            $this->redirect("login/");
            return new Response("");
        }

        $id = intval($request->getURIParam("class_id") ?? -1);
        if(!($class = $this->getRepository(ClassesRepository::class)->findById($id))) {
            $this->redirect("teacher/");
            return new Response("");
        }

        // Check for perms
        if($class->getOwnerId() !== $_SESSION["account_id"] &&! $this->session->isAdmin()) {
            $this->redirect("teacher/");
            return new Response("");
        }

        $grid = "";
        $files = $this->getRepository(FilesRepository::class)->findBy([
            "class_id" => $id
        ]);

        foreach($files as $file) {

            if(!file_exists("assets/files/".$file->GetPath()))
                continue;

            $name = $file->getName();
            $this->checker->sanitizeString($name);

            $grid .= '<tr>';
            $grid .= '<td class="texte">'.$name.'</td>';
            $grid .= '<td><a href="{% assets(files/'.$file->getPath().') %}" class="modifButton">Voir</a></td>';
            $grid .= '<td><a href="#" id="'.$file->getId().'" class="modifButton del">Supprimer</a></td>';
            $grid .= '</tr>';
            $grid .= '<tr>';
        }
        if(!$grid)
            $grid = "<span class='white'>Aucun fichier ici...</span>";

        $response = new Response();

        $response->render("teacher/editClass.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "grid" => $grid,
            "classID" => $class->getid()
        ]);
        return $response;
    }

    /**
     * Ajout d'un fichier
     * @param array $data Tableau POST
     * @param int $classId Identifiant du cours
     * @return void
     */
    private function addFileProcess(array $data, int $classId): void {
        $name = $data["documentName"] ?? "Fichier sans nom";

        if(!filesize($_FILES["document"]["tmp_name"]))
            return;

        $extension = strtolower(pathinfo($_FILES["document"]["name"],PATHINFO_EXTENSION));

        $target = hash_file('md5', $_FILES["document"]["tmp_name"]).'.'.$extension;
        move_uploaded_file($_FILES["document"]["tmp_name"], "assets/files/".$target);

        $this->checker->sanitizeString($name);

        $file = new Files();
        $file->setPath($target);
        $file->setName($name);
        $file->setDate(new DateTime());
        $file->setClassId($classId);
        $this->getDBManager()->add($file);
        $this->getDBManager()->flush();

        $this->redirect("teacher/edit/class/".$classId."/");
    }

    /**
     * Page pour ajouter un fichier à un cours
     * @param Request $request Requête client
     * @return Response Réponse retournée
     */
    public function addFile(Request $request): Response {
        if (!$this->session->isConnected() || !$this->session->isTeacher()) {
            $this->redirect("login/");
            return new Response("");
        }

        $id = intval($request->getURIParam("class_id") ?? -1);
        if(!($class = $this->getRepository(ClassesRepository::class)->findById($id))) {
            $this->redirect("teacher/");
            return new Response("");
        }

        // Check for perms
        if($class->getOwnerId() !== $_SESSION["account_id"] &&! $this->session->isAdmin()) {
            $this->redirect("teacher/");
            return new Response("");
        }


        // POST data
        if($request->get("POST", "submit"))
            $this->addFileProcess($request->getPost(), $id);


        $response = new Response();

        $response->render("teacher/addFile.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "className" => $class->getName(),

        ]);
        return $response;


    }

    /**
     * Suppression d'un QCM (appel post)
     * @param Request $request Requête client
     * @return Response Réponse retournée au client
     */
    public function deleteMcq(Request $request): Response {
        $id = $request->get("POST", "id") ?? -1;

        if (!$this->session->isConnected() || !$this->session->isTeacher()) {
            $this->redirect("login/");
            return new Response("");
        }

        $mcq = $this->getRepository(McqRepository::class)->findById($id);

        if(!$mcq)
            return new Response("false");

        if($mcq->getId() !== $_SESSION["account_id"] && !$this->session->isAdmin())
            return new Response("false");


        $this->getDBManager()->delete($mcq);
        $this->getDBManager()->flush();

        return new Response("true");
    }

    /**
     * Voir la liste des QCM
     * @param Request $request Requête client
     * @return Response Réponse retournée au client
     */
    public function viewMcq(Request $request): Response {
        if (!$this->session->isConnected() || !$this->session->isTeacher()) {
            $this->redirect("login/");
            return new Response("");
        }

        $grid = "";

        if($this->session->isAdmin())
            $quizz = $this->getRepository(McqRepository::class)->findAll();
        else
            $quizz = $this->getRepository(McqRepository::class)->findBy(["owner_id" => $_SESSION["account_id"]]);

        foreach($quizz as $q) {
            $reader = $this->get(QuizReader::class);
            $reader->setPath($q->getPath());
            $reader->load();

            $account = $this->getRepository(AccountRepository::class)->findById($q->getOwnerId());
            if($account)
                $account = $account->getLogin();
            else
                $account = "Quelqu'un de cool";

            $subject = $this->getRepository(SubjectRepository::class)->findById($q->getSubjectId());
            if($subject)
                $subject = $subject->getName();
            else
                $subject = "??";

            $name = $reader->getName();
            $this->checker->sanitizeString($name);
            $grid .= '<tr>';
            $grid .= '<td class="texte">'.$name.'['.$subject.'] </td>';
            $grid .= '<td class="texte">'.$account.'</td>';
            $grid .= '<td><a href="#" id="'.$q->getId().'" class="modifButton del">Supprimer</a></td>';
            $grid .= '</tr>';
            $grid .= '<tr>';
        }

        $response = new Response();

        $response->render("teacher/viewMcq.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "grid" => $grid
        ]);

        return $response;
    }
}
