<?php
declare(strict_types=1);

if (PHP_SAPI === 'cli-server') {
	$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
	if (strpos($path, '/semujeres/public/') === 0) {
		$path = substr($path, strlen('/semujeres/public'));
	}
	$file = __DIR__ . $path;
	if (is_file($file)) {
		$contentType = function_exists('mime_content_type') ? mime_content_type($file) : null;
		header('Content-Type: ' . ($contentType ?: 'application/octet-stream'));
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'HEAD') {
			readfile($file);
		}
		exit;
	}
}

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\ResponseEmitter\ResponseEmitter;
use App\Application\Middleware\SessionMiddleware;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Middleware\MethodOverrideMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Legacy dependencies trigger deprecation notices on PHP 8.2; do not treat them as fatal.
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

session_start();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (false) { // Should be set to true in production
	$containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../app/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

//Set view in Container
$container->set('view', function(){
	 $twig = Twig::create(__DIR__ . '/../src/templates',['cache'=>false]);
	 return $twig;
});

$container->set('storageImg',__DIR__.'/../app/uploadImg');
$container->set('files',__DIR__.'/../app/uploadFiles');
$container->set('fichas',__DIR__.'/../app/uploadFichas');


// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$basePath = getenv('APP_BASE_PATH') ?: '/semujeres/public';
if (PHP_SAPI === 'cli-server') {
	$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
	$basePath = (strpos($requestPath, '/semujeres/public') === 0) ? '/semujeres/public' : '';
}
$app->setBasePath($basePath);
$callableResolver = $app->getCallableResolver();



// DB CONNECTION
require __DIR__ . '/../config/dbConnection.php';

//Add Twig Middleware

$app->add(TwigMiddleware::createFromContainer($app));

// Register middleware
//$middleware = require __DIR__ . '/../app/middleware.php';
//$middleware($app);
$app->add(SessionMiddleware::class);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);


/** @var SettingsInterface $settings */
$settings = $container->get(SettingsInterface::class);

$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);


// Add Routing Middleware
$app->addRoutingMiddleware();


$app->addBodyParsingMiddleware();

$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);
// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
