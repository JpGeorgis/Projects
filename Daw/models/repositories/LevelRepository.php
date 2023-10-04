<?php

namespace App\Models\repositories;

use App\Models\entities\Level;
use DateTime;

require_once("../core/EntityRepository.php");
require_once("../models/entities/Level.php");

/**
 * Classe qui modélise une liste de niveaux en base de données
 */
class LevelRepository extends EntityRepository
{

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
        parent::__construct("level");
    }

    /**
     * Création d'un objet à partir d'un tableau de données si possible
     * @param array $item Tableau de données
     * @return Level|null Objet Level si constructible, sinon null
     */
    public function makeByArray(array $item): ?Level {
        $needed = ["student_id", "subject_id", "level"];
        foreach($needed as $need)
            if(!array_key_exists($need, $item))
                return null;
        $entity = new Level();
        $entity->setId($item["id"]);
		$entity->setStudentId(intval($item["student_id"]));
		$entity->setSubjectId(intval($item["subject_id"]));
		$entity->setLevel(intval($item["level"]));

        return $entity;
    }
}

return new LevelRepository();