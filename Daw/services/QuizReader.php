<?php

namespace App\Service;

require_once("../core/AbstractService.php");

use App\Models\entities\AbstractService;
use SimpleXMLElement;

/**
 * Modélise une réponse
 */
class Answer
{
    /**
     * @var int Identifiant de la réponse
     */
    public int $id;
    /**
     * @var string Texte de la réponse
     */
    public string $text;

    /**
     * Constructeur de la classe
     * @param int $id Identifiant de la réponse
     * @param string $text Texte de la réponse
     */
    public function __construct(int $id, string $text) {
        $this->id = $id;
        $this->text = $text;
    }
}

/**
 * Modélise une question d'un QCM
 */
class Question
{
    /**
     * @var int Identifiant de la bonne réponse
     */
    private int $answer;
    /**
     * @var int Identifiant de la question
     */
    private int $id;
    /**
     * @var string Texte de la question
     */
    private string $question;
    /**
     * @var array Liste des choix
     */
    private array $choices;

    /**
     * Constructeur de la classe
     * @param array $data Données afin de construire la question
     */
    public function __construct(array $data) {
        $this->choices = [];
        $this->answer = $data["attributes"]["correct_answer"];
        $this->id = intval($data["attributes"]["id"]);
        $this->question = $data["text"]["value"];
        foreach($data["answer"] as $answer)
            $this->choices[] = new Answer(intval($answer["attributes"]["id"]), $answer["value"]);
    }

    /**
     * Retourne l'identifiant de la question
     * @return int Identifiant de la question
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Retourne l'id de la bonne réponse
     * @return int Identifiant de la bonne réponse
     */
    public function getAnswer(): int
    {
        return $this->answer;
    }

    /**
     * Retourne les choix de la question
     * @return array Choix de la question
     */
    public function getChoices(): array
    {
        return $this->choices;
    }

    /**
     * Retourne le texte de la question
     * @return string Texte de la question
     */
    public function getQuestion(): string 
    {
        return $this->question;
    }
}

/**
 * Permet de lire un quizz en XML
 */
class QuizReader extends AbstractService
{
    /**
     * @var string Chemin relalatif/absolu vers le XML
     */
    private string $path;
    /**
     * @var array Contenu parse du XML
     */
    private array $content;
    /**
     * @var string Nom du quizz
     */
    private string $name;
    /**
     * @var array Liste des questions du quizz
     */
    private array $questions;

    /**
     * Constructeur de la classe
     */
    public function __construct() {
        parent::__construct();
        $this->path = "";
        $this->content = [];
        $this->name = "";
    }

    /**
     * Définit le chemin vers le XML du quizz
     * @param string $path Chemin vers le XML du quizz
     * @return $this Instance courante (fluent)
     */
    public function setPath(string $path): QuizReader {
        $this->path = "assets/mcq/".$path;
        return $this;
    }

    /**
     * Retourne le contenu du quizz (parse en tableau)
     * @return array Tableau avec le contenu du quizz
     */
    public function getContent(): array {
        return $this->content;
    }

    /**
     * Retourne la liste des questions
     * @return array Liste des questions
     */
    public function getQuestions(): array {
        return $this->questions;
    }

    /**
     * Retourne le nom du quizz
     * @return string Nom du quizz
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Charge le quizz en mémoire en lisant le fichier XML
     * @return void
     */
    public function load(): void {
        try {
        if(!file_exists($this->path))
            throw new \Exception("XML not found: ".$this->path);
        } catch(\Exception) {
            die();
        }
        $this->content = $this->xmlToArray(
            simplexml_load_file($this->path)
        );
        $this->name = $this->content["quiz"]["name"]["value"];
        foreach ($this->content["quiz"]["questions"]["question"] as $question) {
            $this->questions[] = new Question($question);
        }
    }

    /**
     * Parse an XML element into an array
     * @param SimpleXMLElement $xml XML element (load with simplexml_load_file for example)
     * @return array[] xml parsed
     *
     * @author CameronXie : https://www.php.net/manual/fr/class.simplexmlelement.php#122006
     */
    private function xmlToArray(SimpleXMLElement $xml): array
    {
        $parser = function (SimpleXMLElement $xml, array $collection = []) use (&$parser) {
            $nodes = $xml->children();
            $attributes = $xml->attributes();

            if (0 !== count($attributes)) {
                foreach ($attributes as $attrName => $attrValue) {
                    $collection['attributes'][$attrName] = strval($attrValue);
                }
            }

            if (0 === $nodes->count()) {
                $collection['value'] = strval($xml);
                return $collection;
            }

            foreach ($nodes as $nodeName => $nodeValue) {
                if (count($nodeValue->xpath('../' . $nodeName)) < 2) {
                    $collection[$nodeName] = $parser($nodeValue);
                    continue;
                }

                $collection[$nodeName][] = $parser($nodeValue);
            }

            return $collection;
        };

        return [
            $xml->getName() => $parser($xml)
        ];
    }

}