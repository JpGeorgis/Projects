<?php

namespace App\Models\repositories;

use App\Models\entities\ForumThread;
use DateTime;

require_once("../core/EntityRepository.php");
require_once("../models/entities/ForumThread.php");

/**
 * Classe qui modélise une liste de fils de discussion en base de données
 */
class ForumThreadRepository extends EntityRepository
{

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
        parent::__construct("forum_thread");
    }

    /**
     * Construit un objet FormThread grâce à un tableau de données
     * @param array $item Tableau de données
     * @return ForumThread|null Objet ForumThread si possible, sinon null
     */
    public function makeByArray(array $item): ?ForumThread {
        $needed = ["name", "create_date", "author", "visible", "vues"];
        foreach($needed as $need)
            if(!array_key_exists($need, $item))
                return null;
        $entity = new ForumThread();
        $entity->setId($item["id"]);
		$entity->setName($item["name"]);
		$rawDate = $item["create_date"];
		$date = DateTime::createFromFormat("Y-m-d H:i:s", $rawDate);
		$entity->setCreateDate($date);
		$entity->setAuthor(intval($item["author"]));
        $entity->setVisible(intval($item["visible"]));
        $entity->setVues(intval($item["vues"]));

        return $entity;
    }
}

return new ForumThreadRepository();