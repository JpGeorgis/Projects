<?php

namespace App\Models\repositories;

use App\Models\entities\Mcq;
use DateTime;

require_once("../core/EntityRepository.php");
require_once("../models/entities/Mcq.php");

/**
 * Modélise une liste de questionnaires en base de données
 */
class McqRepository extends EntityRepository
{

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
        parent::__construct("mcq");
    }

    /**
     * Construction d'un objet à partir d'un tableau de données
     * @param array $item Tableau de données
     * @return Mcq|null Objet si constructible, sinon null
     */
    public function makeByArray(array $item): ?Mcq {
        $needed = ["path", "subject_id", "owner_id"];
        foreach($needed as $need)
            if(!array_key_exists($need, $item))
                return null;
        $entity = new Mcq();
        $entity->setId($item["id"]);
		$entity->setPath($item["path"]);
		$entity->setSubjectId(intval($item["subject_id"]));
        $entity->setOwnerId(intval($item["owner_id"]));

        return $entity;
    }
}

return new McqRepository();