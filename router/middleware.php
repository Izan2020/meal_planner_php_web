<?php

namespace Router;

require_once "../data/datasource/remote_data_source.php";
require_once "../data/model/AppResponse.php";
require_once "../data/model/InsertParams.php";

// Imports
use Data\Datasource\LocalDataSource;
use Data\Model\InsertParams;
use Data\Model\ResponseError;
use Data\Model\ResponseSuccess;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use RuntimeException;


class MealDetailMiddleware implements Middleware {
    private $lds;

    // Implementasi Middleware.
    public function process(
        Request $request,         
        RequestHandler $requestHandler): Response {
        $params = $request->getQueryParams();

        $recipeId = $params["id"]?? 0;
        $title = $params["title"]?? "";

        if($title ==="")  {
            $response = $requestHandler->handle($request);
            return $response;
        };

        $params = new InsertParams(            
            $title,
            $recipeId
        );

        $localDataSource = $this->lds->insertMealHistory($params);
        if($localDataSource instanceof ResponseError) {
            $message = $localDataSource->getMessage();
            throw new RuntimeException("Failed to insert meal history :" . $message);
        } else if ($localDataSource instanceof ResponseSuccess) {
            $response = $requestHandler->handle($request);
            return $response;
        }        
        throw new RuntimeException("Unexpected response from insertMealHistory");
    }

    public function __construct(){        
        $this->lds = new LocalDataSource();
    }
}