<?php

namespace App\Models\repositories;

use App\Models\entities\Classes;
use DateTime;

require_once("../core/EntityRepository.php");
require_once("../models/entities/Classes.php");

/**
 * Classe qui modélise une liste de cours en base de données
 */
class ClassesRepository extends EntityRepository
{

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
        parent::__construct("classes");
    }

    /**
     * Construit un objet Classes si possible avec un tableau de données
     * @param array $item Tableau de données
     * @return Classes|null Objet Classes si possible, sinon null
     */
    public function makeByArray(array $item): ?Classes {
        $needed = ["name", "owner_id", "difficulty", "creation",
            "picture", "subject_id", "description"];
        foreach($needed as $need)
            if(!array_key_exists($need, $item))
                return null;
        $entity = new Classes();
        $entity->setId($item["id"]);
		$entity->setName($item["name"]);
		$entity->setOwnerId(intval($item["owner_id"]));
		$entity->setDifficulty(intval($item["difficulty"]));
		$rawDate = $item["creation"];
		$date = DateTime::createFromFormat("Y-m-d H:i:s", $rawDate);
		$entity->setCreation($date);
		$entity->setPicture($item["picture"]);
		$entity->setSubjectId(intval($item["subject_id"]));
        $entity->setDescription($item["description"]);

        return $entity;
    }
}

return new ClassesRepository();