<?php
return $content= '<?php
namespace App\Controller;

require_once("../core/AbstractController.php");

use App\HTTP\Request;
use App\HTTP\Response;

class [NAME]Controller extends AbstractController
{
    public function __construct() {
        parent::__construct();
        // Update routes :
        $this->routes["/example/"] = "example";
    }

    public function example(Request $request): Response {
        $response = new Response("content");
        return $response;
    }
}
';

