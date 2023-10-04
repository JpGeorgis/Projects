<?php

namespace App\Models\repositories;

use App\Models\entities\Files;
use DateTime;

require_once("../core/EntityRepository.php");
require_once("../models/entities/Files.php");

/**
 * Classe qui modélise une liste de fichiers en base de données
 */
class FilesRepository extends EntityRepository
{

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
        parent::__construct("files");
    }

    /**
     * Fabrique un objet Files grâce à un tableau de données si possible
     * @param array $item Tableau de données
     * @return Files|null Files si constructible, sinon null
     */
    public function makeByArray(array $item): ?Files {
        $needed = ["name", "path", "date", "class_id"];
        foreach($needed as $need)
            if(!array_key_exists($need, $item))
                return null;
        $entity = new Files();
        $entity->setId($item["id"]);
		$entity->setName($item["name"]);
		$entity->setPath($item["path"]);
		$rawDate = $item["date"];
		$date = DateTime::createFromFormat("Y-m-d H:i:s", $rawDate);
		$entity->setDate($date);
		$entity->setClassId(intval($item["class_id"]));

        return $entity;
    }
}

return new FilesRepository();