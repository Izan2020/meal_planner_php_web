<?php

// ███╗░░░███╗███████╗░█████╗░██╗░░░░░
// ████╗░████║██╔════╝██╔══██╗██║░░░░░
// ██╔████╔██║█████╗░░███████║██║░░░░░
// ██║╚██╔╝██║██╔══╝░░██╔══██║██║░░░░░
// ██║░╚═╝░██║███████╗██║░░██║███████╗
// ╚═╝░░░░░╚═╝╚══════╝╚═╝░░╚═╝╚══════╝
// ██████╗░██╗░░░░░░█████╗░███╗░░██╗███╗░░██╗███████╗██████╗░
// ██╔══██╗██║░░░░░██╔══██╗████╗░██║████╗░██║██╔════╝██╔══██╗
// ██████╔╝██║░░░░░███████║██╔██╗██║██╔██╗██║█████╗░░██████╔╝
// ██╔═══╝░██║░░░░░██╔══██║██║╚████║██║╚████║██╔══╝░░██╔══██╗
// ██║░░░░░███████╗██║░░██║██║░╚███║██║░╚███║███████╗██║░░██║
// ╚═╝░░░░░╚══════╝╚═╝░░╚═╝╚═╝░░╚══╝╚═╝░░╚══╝╚══════╝╚═╝░░╚═╝

// Copyright © 2024 Meal Planner Web by Luthfan Hindami. All rights reserved. 
// This project, named Meal Planner Web, leverages PHP and the Slim framework to deliver 
// an efficient web-based meal planning experience. Developed in June 2024, it aims to simplify 
// meal management with its innovative features and user-centric design. Reproduction or distribution of 
// any part of this project without authorization is prohibited.

// 1. MySQL Configuration.
// The database configuration located in 'data' directory,
// and then 'datasource' folder, inside local_data_source.php
// data > datasource > local_data_source.php.

// 2. Spooncaular API Configuration.
// The API configuration located in 'data' directory,
// and then 'datasource' folder, inside remote_data_source.php
// data > datasource > remote_data_source.php.

//                          INSTRUCTION
// 0 ========================================================= 0
// |  To run project, execute this in Terminal 'nodemon'       |
// |  It will provide a link to run the project in localhost.  |
// 0 ========================================================= 0


require '../vendor/autoload.php';
require '../router/routes.php';
require '../router/service.php';

// Imports
use Slim\Factory\AppFactory;
use Router\AppService;
use Router\AppRoutes;


$app = AppFactory::create();

// Error Middleware
$app->addErrorMiddleware(true, true, true);

// Defining Routes
$routes = new AppRoutes();
$routes->defineRoutes($app);

// Defining Services
$services = new AppService();
$services->defineServices($app);

$app->run();;





