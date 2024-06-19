<?php

namespace Router;

require_once "../data/datasource/remote_data_source.php";
require_once "../data/datasource/local_data_source.php";

// Imports
use Data\Datasource\LocalDataSource;
use Data\Datasource\RemoteDataSource;
use Data\Model\ResponseError;
use Data\Model\ResponseSuccess;
use Data\Model\SearchParams;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AppService {
    private $rds;
    private $lds;

    
    

    public function defineServices($app) {

            // Clear Meals History
            $app->get("/clear-history", function(Request $request, Response $response) {
            
                        
                $localDataSource = $this->lds->clearMealHistory();
                if($localDataSource instanceof ResponseError)  {                
                    $statusCode = $localDataSource->getStatusCode();
                    $errorMessage = $localDataSource->getMessage();                
                    error_log("Error History " . $statusCode);
                    $response->getBody()->write($errorMessage);
                    return $response->withStatus($statusCode);
                } else if ($localDataSource instanceof ResponseSuccess) {                                                 
                    return $response->withStatus(200);
                }
                return $response;
            });   

        // Fetch Meals History
        $app->get("/get-meal-history", function(Request $request, Response $response) {
            
                        
            $localDataSource = $this->lds->getMealsHistory();
            if($localDataSource instanceof ResponseError)  {                
                $statusCode = $localDataSource->getStatusCode();
                $errorMessage = $localDataSource->getMessage();                
                error_log("Error History " . $statusCode);
                $response->getBody()->write($errorMessage);
                return $response->withStatus($statusCode);
            } else if ($localDataSource instanceof ResponseSuccess) {
                $data = $localDataSource->getData();     
                $jsonData = json_encode($data);  
                error_log("Success History :" . $jsonData);         
                $response->getBody()->write($jsonData);                
                return $response;
            }
            return $response;
        });                

        // Get Meals Detail Request Method
        $app->get("/get-meal-detail", function(Request $request, Response $response) {
            $params = $request->getQueryParams();

            $recipeId = $params["recipeId"]?? 0;
            
            $remoteDataSource = $this->rds->getMealsDetail($recipeId);
            if($remoteDataSource instanceof ResponseError)  {                
                $statusCode = $remoteDataSource->getStatusCode();
                $errorMessage = $remoteDataSource->getParsedMessage();                
                error_log("Error! " . $statusCode);
                $response->getBody()->write($errorMessage);
                return $response->withStatus($statusCode);
            } else if ($remoteDataSource instanceof ResponseSuccess) {
                $data = $remoteDataSource->getData();
                error_log("Success");
                $response->getBody()->write(json_decode($data));                
                return $response;
            }
            return $response;
        });

        // Search Meal Request Method
        $app->get("/search-meal", function(Request $request, Response $response,) {            

            $params = $request->getQueryParams();

            // Example usage
            $diet = $params['diet'] ?? '';
            $intolerances = $params['intolerances'] ?? '';
            $minCalories = $params['minCalories'] ?? '';
            
            $searchParams = new SearchParams(
                $diet, $minCalories, $intolerances
            );
                                                                            
            $remoteDataSource = $this->rds->searchMeals($searchParams);
            if($remoteDataSource instanceof ResponseError)  {                
                $statusCode = $remoteDataSource->getStatusCode();
                $errorMessage = $remoteDataSource->getParsedMessage();
                $response->getBody()->write($errorMessage);
                return $response->withStatus($statusCode);
            } else if ($remoteDataSource instanceof ResponseSuccess) {
                $data = $remoteDataSource->getData();

                $response->getBody()->write(json_decode($data));
                return $response;
            }
            return $response;
        });

    }    

    public function __construct(){
        $this->rds = new RemoteDataSource();
        $this->lds = new LocalDataSource();
        
    }

}