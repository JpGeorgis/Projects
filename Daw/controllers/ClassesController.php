<?php
namespace App\Controller;

require_once("../core/AbstractController.php");

use App\HTTP\Request;
use App\HTTP\Response;
use App\Models\repositories\AccountRepository;
use App\Models\repositories\ClassesRepository;
use App\Models\repositories\FilesRepository;
use App\Models\repositories\LevelRepository;
use App\Models\repositories\McqRepository;
use App\Models\repositories\SubjectRepository;
use App\Service\McqMgr;
use App\Service\SessionMgr;
use App\Service\ThemeMgr;

/**
 * Contrôleur pour les cours côté utilisateur
 */
class ClassesController extends AbstractController
{
    /**
     * @var SessionMgr|null Service permettant la gestion des sessions.
     */
    private ?SessionMgr $session;
    /**
     * @var McqMgr|null Service permettant de gérer les QCM
     */
    private ?McqMgr $mcqMgr;
    /**
     * @var ThemeMgr|null Service permettant la gestion des thèmes.
     */
    private ?ThemeMgr $theme;

    /**
     * Constructeur de la classe
     */
    public function __construct() {
        parent::__construct();
        $this->session = null;
        $this->theme = null;
        $this->mcqMgr = null;
        $this->routes["/new\/class/"] = "newClass";
        $this->routes["/my\/mcq/"] = "myMCQ";
        $this->routes["/view\/mcq\/(?<mcq>\d+)/"] = "viewMCQ";
        $this->routes["/view\/subject\/(?<subject_id>\d+)/"] = "viewSubject";
        $this->routes["/view\/class\/(?<class_id>\d+)/"] = "viewClass";
        $this->routes["/my\/classes/"] = "myClasses";
    }

    /**
     * Initialisation des services
     * @return void
     */
    public function init(): void {
        $this->session = $this->get(SessionMgr::class);
        $this->theme = $this->get(ThemeMgr::class);
        $this->mcqMgr = $this->get(McqMgr::class);
    }

    /**
     * Renvoie $numbers résultat dans l'array $items avec les coefficients de manière aléatoire
     * @param array $items Liste coefficientée
     * @param int $numbers Nombre d'items voulus
     * @return array Objets choisis aléatoirement
     */
    function getRandomWeighted(array $items, int $numbers): array {
        $out = [];
        while(count($items) && count($out)!==$numbers ) {
            $s = 0;
            foreach($items as $item) {
                [$value, $_] = $item;
                $s += $value;
            }

            // sort array :
            usort($items, function($a, $b) {
                [$i1, $_] = $a;
                [$i2, $_] = $b;
                return $i2 - $i1;
            });

            $rand = mt_rand(1, $s);

            $to_delete = null;
            foreach($items as $item) {
                [$value, $class] = $item;
                $rand -= $value;
                if($rand <= 0) {
                    $out[] = $class;
                    $to_delete = $item;
                    break;
                }
            }

            $new = [];
            // rebuild array
            foreach($items as $item) {
                if($item !== $to_delete)
                    $new[] = $item;
            }

            $items = $new;
        }
        return $out;
    }

    /**
     * Génère les recommandations d'un utilisateur
     * @param int $accountID Identifiant du compte
     * @return string
     */
    public function recommandations(int $accountID): string {
        // Get all classes
        $classes = $this->getRepository(ClassesRepository::class)->findAll();
        // Get account :
        $account = $this->getRepository(AccountRepository::class)->findById($accountID);
        // Get all subject
        $subjects = $this->getRepository(SubjectRepository::class)->findAll();

        $levels = [];
        // Get account level on each
        foreach($subjects as $subject){
            $id = $subject->getId();
            $l = $this->getRepository(LevelRepository::class)->findBy(["subject_id" => $id, "student_id" => $accountID]);
            $level = 0;
            foreach($l as $item) {
                if($item->getLevel() > $level)
                    $level = $item->getLevel();
            }
            $levels[$id] = $level;
        }

        $coefficientClasses = [];
        // For each classes, apply coef :
        foreach($classes as $class) {
            if($levels[$class->getSubjectId()] === 0) // Seems not interested in
                $coefficientClasses[] = [4-$class->getDifficulty(), $class];
             else
                $coefficientClasses[] = [6+(2*$levels[$class->getSubjectId()] === 1)-$class->getDifficulty(), $class];
        }

        $keep = $this->getRandomWeighted($coefficientClasses, 9);

        // Generate out string
        $out = "";
        $index = 0;
        foreach($keep as $class) {
            if($index%3 === 0)
                $out .= '<div class="center" align="center">';

            $out .= '<div class="container">';
            $out .= '    <a href="{% route(/view/class/'.$class->getId().'/) %}">';
            $out .= '        <img src="{% assets(images/'.$class->getPicture().') %}" alt=""/>';
            $out .= '        <p class="text">'.$class->getName().'</p>';
            $out .= '    </a>';
            $out .= '</div>';

            if($index%3 === 2)
                $out .= '</div>';
            $index ++;
        }
        return $out;
    }

    /**
     * Affichage d'un QCM
     * @param Request $request
     * @return Response
     */
    public function viewMCQ(Request $request): Response {
        if(!$this->session->isConnected()) {
            $this->redirect("login/");
            return new Response("");
        }

        $id = intval($request->getURIParam("mcq"));
        if(!($quizz = $this->getRepository(McqRepository::class)->findById($id))) {
            $this->redirect("my/mcq/");
            return new Response("");
        }

        // MQC sent
        if($request->get("POST", "submit")) {
            $post = $request->getPost();
            $this->mcqMgr->save($id, $post);
            $this->redirect("my/classes/");
            return new Response("");
        }

        $response = new Response();
        $response->render("classes/viewMCQ.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "questionnaires" => $this->mcqMgr->generateQuiz($quizz->getPath())
        ]);
        return $response;
    }

    /**
     * QCM conseillés pour un utilisateur
     * @return Response
     */
    public function myMCQ(): Response {
        if(!$this->session->isConnected()) {
            $this->redirect("login/");
            return new Response("");
        }


        $response = new Response();
        $response->render("classes/myMQC.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "view" => $this->mcqMgr->generateGlobalView()
        ]);
        return $response;
    }

    /**
     * Voir tous les cours d'une matière
     * @param Request $request
     * @return Response
     */
    public function viewSubject(Request $request): Response {
        if(!$this->session->isConnected()) {
            $this->redirect("login/");
            return new Response("");
        }

        $id = $request->getURIParam("subject_id");
        if(!($subject = $this->getRepository(SubjectRepository::class)->findById($id))) {
            $this->redirect("my/classes/");
            return new Response("");
        }

        $classes = $this->getRepository(ClassesRepository::class)->findBy(["subject_id" => $id]);

        $grid = "";
        $index = 0;
        foreach($classes as $class) {
            if($index%3 === 0)
                $grid .= '<div class="center" align="center">';

            $grid .= '<div class="container">';
            $grid .= '    <a href="{% route(/view/class/'.$class->getId().'/) %}">';
            $grid .= '        <img src="{% assets(images/'.$class->getPicture().') %}" alt="" />';
            $grid .= '        <p class="text">'.$class->getName().'</p>';
            $grid .= '    </a>';
            $grid .= '</div>';

            if($index%3 === 2)
                $grid .= '</div>';
            $index ++;
        }

        $response = new Response();

        $response->render("classes/classes.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "className" => $subject->getName(),
            "classDescription" => $subject->getDescription(),
            "theme" => $this->theme->getTheme(),
            "grid" => $grid
        ]);

        return $response;
    }

    /**
     * Page pour que l'utilisateur puisse voir ses cours
     * @param Request $request
     * @return Response
     */
    public function myClasses(Request $request): Response {
        if(!$this->session->isConnected()) {
            $this->redirect("login/");
            return new Response("");
        }

        $subjects = $this->getRepository(SubjectRepository::class)->findAll();
        $subjectsHTML = "";
        foreach($subjects as $subject) {
            $subjectsHTML .= '<li class="carousel-item">';
            $subjectsHTML .= '<a href="{% route(/view/subject/'.$subject->getId().'/) %}">';
            $subjectsHTML .= '        <video preload id="video0" class="videos" autoplay loop muted>';
            $subjectsHTML .= '            <source src="{% assets(videos/'.$subject->getVideo().') %}" type="video/mp4">';
            $subjectsHTML .= '        </video>';
            $subjectsHTML .= '        <p class="texte">'.$subject->getName().'</p>';
            $subjectsHTML .= '    </a>';
            $subjectsHTML .= '</li>';
        }

        $response = new Response();
        $response->render("classes/myClasses.html",
            [
                "connected" => $this->session->isConnected(),
                "isAdmin" => $this->session->isAdmin(),
                "isTeacher" => $this->session->isTeacher(),
                "subjects" => $subjectsHTML,
                "theme" => $this->theme->getTheme(),
                "recommandations" => $this->recommandations($_SESSION["account_id"])
            ]);
        return $response;
    }

    /**
     * Création d'une nouvelle classe
     * @param Request $request Requête client
     * @return Response Réponse retournée au client
     */
    public function newClass(Request $request): Response {
        if(!$this->session->isConnected() || !$this->session->isTeacher()) {
            $this->redirect("login/");
            return new Response("");
        }

        $response = new Response();
        $response->render("classes/newClasses.html",
            [
                "connected" => $this->session->isConnected(),
                "isAdmin" => $this->session->isAdmin(),
                "isTeacher" => $this->session->isTeacher(),
                "theme" => $this->theme->getTheme()
            ]);
        return $response;
    }

    /**
     * Contrôleur pour la vision d'un cours
     * @param Request $request Requête client
     * @return Response Réponse retournée au client
     */
    public function viewClass(Request $request): Response {
        if(!$this->session->isConnected()) {
            $this->redirect("login/");
            return new Response("");
        }

        if(!($id = $request->getURIParam("class_id"))) {
            $this->redirect("my/classes");
            return new Response("");
        }

        $response = new Response();

        if(!$class = $this->getRepository(ClassesRepository::class)->findById($id)) {
            $this->redirect("my/classes");
            return new Response("");
        }

        $files = $this->getRepository(FilesRepository::class)->findBy([
            "class_id" => $id
        ]);

        $content = "";
        $description = $class->getDescription();

        foreach($files as $file) {
            if(str_ends_with($file->getPath(), ".pdf"))
                $content.= '<img src="{% assets(images/pdf.png) %}" alt="img pdf" class="logo_bulle"> ';
            elseif(str_ends_with($file->getPath(), ".mp4"))
                $content.= '<img src="{% assets(images/video.png) %}" alt="img pdf" class="logo_bulle"> ';
            else
                $content.= '<img src="{% assets(images/file.png) %}" alt="img pdf" class="logo_bulle"> ';
            $content.= '<a href="{% assets(files/'.$file->getPath().') %}">'.$file->getName().'</a> <br /><br />';
        }

        $response->render("classes/viewClass.html", [
            "connected" => $this->session->isConnected(),
            "isAdmin" => $this->session->isAdmin(),
            "isTeacher" => $this->session->isTeacher(),
            "theme" => $this->theme->getTheme(),
            "className" => $class->getName(),
            "classDescription" => $description,
            "classContent" => $content
        ]);
        return $response;
    }
}
