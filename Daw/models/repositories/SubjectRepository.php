<?php

namespace App\Models\repositories;

use App\Models\entities\Subject;
use DateTime;

require_once("../core/EntityRepository.php");
require_once("../models/entities/Subject.php");

/**
 * Classe modélisant une liste de sujets en base de données
 */
class SubjectRepository extends EntityRepository
{

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
        parent::__construct("subject");
    }

    /**
     * Construit l'objet à partir d'un tableau de données
     * @param array $item Tableau de données
     * @return Subject|null Objet si constructible, sinon null.
     */
    public function makeByArray(array $item): ?Subject {
        $needed = ["name", "description", "video"];
        foreach($needed as $need)
            if(!array_key_exists($need, $item))
                return null;
        $entity = new Subject();
        $entity->setId($item["id"]);
		$entity->setName($item["name"]);
		$entity->setDescription($item["description"]);
		$entity->setVideo($item["video"]);

        return $entity;
    }
}

return new SubjectRepository();