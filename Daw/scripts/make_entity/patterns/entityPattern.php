<?php
return '<?php

namespace App\Models\entities;

use APP\Models\SQL\ACTION_TYPE;
use APP\Models\SQL\Query;
use DateTime;

require_once("../core/Entity.php");


class [ENTITY_NAME] extends Entity
{
[ENTITY_PROPERTIES]
[ENTITY METHODS]
    public function toSql(int $mode): Query
    {
        return match ($mode) {
            ACTION_TYPE::DELETE => new Query("[QUERY_DELETE]", [$this->getId()]),

            ACTION_TYPE::CREATE => new Query(
                "[QUERY_CREATE]",
                [
                    [CREATE_PARAMS]
                ]
            ),

            ACTION_TYPE::UPDATE => new Query(
                "[QUERY_UPDATE]",
                [
                    [UPDATE_PARAMS]
                ]
            ),

            default => new Query("") // Default: empty query
        };
    }
    
    public function getAttributes(): array {
        return get_object_vars($this);
    }
}
';