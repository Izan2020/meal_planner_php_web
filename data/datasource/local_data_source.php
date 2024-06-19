<?php

namespace Data\Datasource;

require_once __DIR__ . '/../model/AppResponse.php';

// Imports
use Data\Model\AppResponse;
use Data\Model\InsertParams;
use Data\Model\ResponseError;
use Data\Model\ResponseSuccess;
use PDO;
use PDOException;

class LocalDataSource {
    private $pdo;

   // Get Recent Meals History
   public function getMealsHistory(): AppResponse  {
    
    try {
        $stmt = $this->pdo->prepare("SELECT * FROM history");
        $stmt->execute();
        
        // Fetch all rows as an associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return a ResponseSuccess with the fetched data as JSON:3
        return new ResponseSuccess(json_encode($data));
    } catch (PDOException $e) {            
        return new ResponseError(500, $e->getMessage());
    }
   }

    // Saves Recent Opened Meal
    public function insertMealHistory(InsertParams $params): AppResponse {
        $recipeId = $params->getRecipeId();
        $title = $params->getTitle();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO history ( recipeId, title) VALUES (?, ?)");
            $stmt->execute([$recipeId, $title ]);
            return new ResponseSuccess("Recipe Successfully Inserted");
        } catch (PDOException $e) {            
            return new ResponseError(500, $e->getMessage());
        }
    }

    // Clear Meal History
    public function clearMealHistory(): AppResponse {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM history");
            $stmt->execute();
            return new ResponseSuccess("History Successfuly Cleared");
        } catch (PDOException $e) {
            return new ResponseError(500, $e->getMessage());
        }
    }

    
    public function __construct() {
        // Dont forget to start MySQL server in XAMPP :3
        // Host
        $host = "127.0.0.1";
        // Database Name
        $dbname = "test";
        // Username
        $username = "pma";
        // Password
        $password = "";
        
        try {
            $dsn = "mysql:host=$host;dbname=$dbname";
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             // Creates Table if it Doesnt exist in Database
            $stmt = $this->pdo->prepare("
            CREATE TABLE IF NOT EXISTS history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            recipeId INT NOT NULL,    
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            // Execute Create table Query
            $stmt->execute();
            error_log("Table 'history' Successfully Created");
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
?>