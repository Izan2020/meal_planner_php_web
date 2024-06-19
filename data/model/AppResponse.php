<?php

namespace Data\Model;

class AppResponse {}

class ResponseSuccess extends AppResponse {
    public function __construct(
        protected  $data,        
    ) {}

    public function getData()  { return $this->data; }
}

class ResponseError extends AppResponse {
    
    public function __construct(
        protected $statusCode,
        protected $message
    ) {}

    public function getStatusCode() { return $this->statusCode; }
    public function getMessage() {         
        return $this->message; 
    }
    public function getParsedMessage() {
        $apiPricing = "<a href='https://spoonacular.com/food-api/pricing' style='color: white;' target='_blank'>Pricing Disini</a>";
        $pathToHere = "<a style='color: white;'>File Project 'data > datasource > remote_data_source.php > ubah bagian API_KEY'</a>";
        switch ($this->statusCode) {
            case 401:
                return "API Service unauthorized, Ganti API Key-nya di: <br>" . $pathToHere;                 
            case 402:
                return "Bayar API Service dek, " . $apiPricing;                            
            default:
                return "Unknown Error Occured, Error Code : " . $this->statusCode;
                
        }
    }

}