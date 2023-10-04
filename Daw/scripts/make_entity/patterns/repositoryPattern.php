<?php
return '<?php

namespace App\Models\repositories;

use App\Models\entities\[ENTITY_NAME];
use DateTime;

require_once("../core/EntityRepository.php");
require_once("../models/entities/[ENTITY_NAME].php");

class [REPO_NAME] extends EntityRepository
{

    public function __construct()
    {
        parent::__construct("[TABLE_NAME]");
    }
    
    public function makeByArray(array $item): ?[ENTITY_NAME] {
        $needed = [[NEEDED]];
        foreach($needed as $need)
            if(!array_key_exists($need, $item))
                return null;
        $entity = new [ENTITY_NAME]();
        $entity->setId($item["id"]);
[INIT_LINE]
        return $entity;
    }
}

return new [REPO_NAME]();';
