<?php

namespace App\Service;

use App\Models\entities\AbstractService;
use App\Models\entities\Level;
use App\Models\repositories\LevelRepository;
use App\Models\repositories\McqRepository;
use App\Models\repositories\SubjectRepository;

/**
 * Service pour faciliter la gestion des QCM
 */
class McqMgr extends AbstractService
{
    /**
     * @var SessionMgr|null Service pour la gestion des sessions
     */
    public ?SessionMgr $session;
    /**
     * @var QuizReader|null Service pour la lecture des Quizz
     */
    public ?QuizReader $reader;

    /**
     * @var InputDataChecker|null Service de sécurité
     */
    public ?InputDataChecker $checker;
    /**
     * Constructeur du service
     */
    public function __construct() {
        parent::__construct();
        $this->session = null;
        $this->reader = null;
        $this->checker = null;
    }

    /**
     * Initialisation des services
     * @return void
     */
    public function init(): void {
        $this->session = $this->get(SessionMgr::class);
        $this->reader = $this->get(QuizReader::class);
        $this->checker = $this->get(InputDataChecker::class);
    }

    /**
     * Générère la vue globale
     * @return string Vue
     */
    public function generateGlobalView(): string {
        $out = "";
        $subjects = $this->getRepository(SubjectRepository::class)->findAll();
        foreach($subjects as $subject) {
            $subjectName = $subject->getName();
            $this->checker->sanitizeString($subjectName);
            $out .= "<hr />";
            $out .= '<p><b class="slight">'.$subjectName.'</b></p>';
            $out .= '<div class="space">';
            $id = $subject->getId();
            $rows = $this->getRepository(McqRepository::class)->findBy(["subject_id" => $id]);
            foreach ($rows as $mcq) {
                $reader = $this->get(QuizReader::class);
                if(!file_exists("assets/mcq/".$mcq->getPath()))
                    continue;
                $reader->setPath($mcq->getPath());
                $reader->load();
                $name = $reader->getName();
                $this->checker->sanitizeString($name);
                $out.= '<img src="{% assets(images/pdf.png) %}" alt="img pdf" class="logo_bulle"> ';
                $out.= '<a href="{% route(view/mcq/'.$mcq->getId().'/) %}">'.$name.'</a> <br /><br />';
            }
            $out .= "</div>";
        }
        return $out;
    }

    /**
     * Génération de la vue du Quizz
     * @param string $path Chemin vers le XML
     * @return string Vue
     */
    public function generateQuiz(string $path): string {
        $out = "";
        $this->reader->setPath($path);
        $this->reader->load();

        foreach($this->reader->getQuestions() as $q) {
            $choices = $q->getChoices();
            shuffle($choices);
            $question = $q->getQuestion();
            $this->checker->sanitizeString($question);
            $out .= '<fieldset><legend> Question ' . $q->getId() . ': '. $question .'</legend><br />';
            foreach($choices as $value) {
                $this->checker->sanitizeString($value->text);
                $out .= '<input type="radio" name="question_'.$q->getId().'" id="'.$q->getId().'_'.$value->id.'" value="'.$value->id.'">';
                $out .= '<label  for="'.$q->getId().'_'.$value->id.'">' . $value->text . '</label><br />';
            }
            $out .= "</fieldset><br /> <br />";
        }
        return $out;
    }

    /**
     * Retourne le niveau en fonction du pourcentage de bonnes réponses
     * @param float $percent Pourcentage
     * @return int Niveau {1, 2, 3}
     */
    private function getLevel(float $percent): int {
        if($percent > 70)
            return 3;
        elseif($percent > 30)
            return 2;
        return 1;
    }

    /**
     * Enregistre le résultat du questionnaire
     * @param int $id Identifiant du questionnaire
     * @param array $post Données envoyées par POST
     * @return void
     */
    public function save(int $id, array $post): void {
        $quizz = $this->getRepository(McqRepository::class)->findById($id);
        $reader = new QuizReader();
        $reader->setPath($quizz->getPath());
        $reader->load();
        $c = floatval(count($reader->getQuestions()));
        $right = 0.0;

        foreach($reader->getQuestions() as $question)
            if($question->getAnswer() == ($post["question_".$question->getId()] ?? -1))
                $right++;

        $score = ($right/$c)*100;
        if(!$l = $this->getRepository(LevelRepository::class)->findOneBy([
            "subject_id" => $quizz->getSubjectId(),
            "student_id" => $_SESSION['account_id']
        ])) {

            $l = new Level();
            $l->setStudentId($_SESSION["account_id"]);
            $l->setLevel($this->getLevel($score));
            $l->setSubjectId($quizz->getSubjectId());
            $this->getDBManager()->add($l);
        } else {
            $l->setLevel($this->getLevel($score));
            $this->getDBManager()->update($l);
        }
        $this->getDBManager()->flush();
    }
}