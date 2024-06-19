<?php
namespace Data\Datasource;

require_once __DIR__ . '/../model/SearchParams.php';

// Imports
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Data\Model\AppResponse;
use Data\Model\ResponseSuccess;
use Data\Model\ResponseError;
use Data\Model\SearchParams;

class RemoteDataSource {
    

    // ░██████╗██████╗░░█████╗░░█████╗░███╗░░██╗░█████╗░░█████╗░██╗░░░██╗██╗░░░░░░█████╗░██████╗░
    // ██╔════╝██╔══██╗██╔══██╗██╔══██╗████╗░██║██╔══██╗██╔══██╗██║░░░██║██║░░░░░██╔══██╗██╔══██╗
    // ╚█████╗░██████╔╝██║░░██║██║░░██║██╔██╗██║███████║██║░░╚═╝██║░░░██║██║░░░░░███████║██████╔╝
    // ░╚═══██╗██╔═══╝░██║░░██║██║░░██║██║╚████║██╔══██║██║░░██╗██║░░░██║██║░░░░░██╔══██║██╔══██╗
    // ██████╔╝██║░░░░░╚█████╔╝╚█████╔╝██║░╚███║██║░░██║╚█████╔╝╚██████╔╝███████╗██║░░██║██║░░██║
    // ╚═════╝░╚═╝░░░░░░╚════╝░░╚════╝░╚═╝░░╚══╝╚═╝░░╚═╝░╚════╝░░╚═════╝░╚══════╝╚═╝░░╚═╝╚═╝░░╚═╝

    // Each free account (Spoonacular API) is limited to 150 requests per day.
    // Here is the link to register for a Spoonacular account: 'https://spoonacular.com/food-api/console#Dashboard' 

    // Api Key
    const API_KEY = "";
    const BASE_URL = "https://api.spoonacular.com/recipes/";

    private $client;
    
    public function getMealsDetail(int $mealsId): AppResponse  {
        $url = self::BASE_URL . "{$mealsId}/information";
        try {
            $response = $this->client->get($url, [
                "query"=> ["apiKey" => self::API_KEY]
            ]);
            $responseResult = $response->getBody()->getContents();
            
            
            return new ResponseSuccess(json_encode($responseResult, true));
        } catch (RequestException $error) {                        
            return new ResponseError($error->getCode(), $error->getMessage());
        }
    }
    public function searchMeals(SearchParams $params): AppResponse  {
        $url = self::BASE_URL . "complexSearch";

        try {
            $queryParams = [
                "apiKey" => self::API_KEY,
                "diet" => $params->getDiet(),
                "intolerances" => $params->getIntolerances(),
                "minCalories" => $params->getCalories()
            ];
    
            $response = $this->client->get($url, ["query"=> $queryParams]);
            $responseResult = $response->getBody()->getContents();
            if($response->getStatusCode() !== 200) {
                $statusCode = $response->getStatusCode();
                $message = $response->getReasonPhrase();
                return new ResponseError($statusCode, $message);    
            }
            return new ResponseSuccess(json_encode($responseResult, true));
        } catch (RequestException $error) {
            return new ResponseError($error->getCode(), $error->getMessage());
        }


    }

    public function __construct() {
        // Initialize Guzzle client
        $this->client = new Client();
    }
        
}