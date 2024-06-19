<?php

// To run project, execute this in Terminal :
// nodemon

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





