<?php

namespace Router;

require_once "middleware.php";

// Imports
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Router\MealDetailMiddleware;


class AppRoutes {
    private $mealDetailMiddleware;
    const basePath = __DIR__ . "/../src/pages/";
    public function defineRoutes($app) {
        
        // Main Screen Route
        $app->get("/", function(Request $request, Response $response,) {            
            include(self::basePath . "main.html");
            return $response;
        });

        // Recipe Screen Route
        $app->get("/recipe-history", function(Request $request, Response $response,) {            
            include(self::basePath . "recipe_history.html");
            return $response;
        });

        // Recipe History  Screen Route
        $app->get("/recipe", function(Request $request, Response $response,) {            
            include(self::basePath . "recipe.html");
            return $response;
        });

        // Recipe Detail Screen Route
        $app->get("/recipe-detail", function(Request $request, Response $response,) {            
            include(self::basePath . "recipe_detail.html");
            return $response;
        })->add($this->mealDetailMiddleware);        
    }

    public function __construct(){
        $this->mealDetailMiddleware = new MealDetailMiddleware();
    }
}