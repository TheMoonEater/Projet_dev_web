<?php
declare(strict_types=1);


$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'); // true en HTTPS

session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'domain' => '',
  'secure' => $secure,       // en local http -> false
  'httponly' => true,
  'samesite' => 'Lax',
]);

session_start();

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'");



require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

use App\Core\Router;
use App\Core\Security;

Security::setSecurityHeaders();

$router = new Router();


// PILOTS (SFx12-15) - ADMIN only
$router->get('/pilots', 'PilotController@index');            // SFx12
$router->get('/pilots/create', 'PilotController@create');    // SFx13
$router->post('/pilots', 'PilotController@store');           // SFx13
$router->get('/pilots/{id}', 'PilotController@show');        // SFx12
$router->get('/pilots/{id}/edit', 'PilotController@edit');   // SFx14
$router->post('/pilots/{id}/update', 'PilotController@update'); // SFx14
$router->post('/pilots/{id}/delete', 'PilotController@delete'); // SFx15

// STUDENTS (SFx16-19) - ADMIN + PILOT
$router->get('/students', 'StudentController@index');            // SFx16
$router->get('/students/create', 'StudentController@create');    // SFx17
$router->post('/students', 'StudentController@store');           // SFx17
$router->get('/students/{id}', 'StudentController@show');        // SFx16
$router->get('/students/{id}/edit', 'StudentController@edit');   // SFx18
$router->post('/students/{id}/update', 'StudentController@update'); // SFx18
$router->post('/students/{id}/delete', 'StudentController@delete'); // SFx19


$router->get('/', 'PageController@home');

/** Routes publiques */
$router->get('/', 'OfferController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

$router->get('/offers', 'OfferController@index');
$router->get('/offers/{id}', 'OfferController@show');

// LISTE & CREATE
$router->get('/companies', 'CompanyController@index');
$router->get('/companies/create', 'CompanyController@create');
$router->post('/companies', 'CompanyController@store');

// EDIT/UPDATE/DELETE/RATE
$router->get('/companies/{id}/edit', 'CompanyController@edit');
$router->post('/companies/{id}/update', 'CompanyController@update');
$router->post('/companies/{id}/delete', 'CompanyController@delete');
$router->post('/companies/{id}/rate', 'CompanyController@rate');

// SHOW (toujours à la fin)
$router->get('/companies/{id}', 'CompanyController@show');


// OFFERS
$router->get('/offers', 'OfferController@index');

$router->get('/offers/create', 'OfferController@create');
$router->post('/offers', 'OfferController@store');

$router->get('/offers/{id}/edit', 'OfferController@edit');
$router->post('/offers/{id}/update', 'OfferController@update');
$router->post('/offers/{id}/delete', 'OfferController@delete');

// ⚠️ TOUJOURS À LA FIN
$router->get('/offers/{id}', 'OfferController@show');




// APPLICATIONS (candidatures)
$router->get('/offers/{id}/apply', 'ApplicationController@create');     // SFx20 form
$router->post('/offers/{id}/apply', 'ApplicationController@store');     // SFx20 submit

$router->get('/my-applications', 'ApplicationController@mine');         // SFx21

$router->get('/pilot/applications', 'ApplicationController@pilot');     // SFx22

$router->get('/applications/{id}/cv', 'ApplicationController@downloadCv'); // download sécurisé




// WISHLIST
$router->get('/wishlist', 'WishlistController@index');                 // SFx23
$router->post('/offers/{id}/wishlist', 'OfferController@wishlist');
$router->post('/offers/{id}/unwishlist', 'OfferController@unwishlist');


$router->get('/offers/stats', 'OfferController@stats');


$router->get('/legal', 'LegalController@index');

$router->get('/sitemap.xml', 'SeoController@sitemap');

$router->get('/mentions-legales', 'LegalController@mentions');


$router->get('/profile', 'ProfileController@show');
$router->post('/profile/photo', 'ProfileController@updatePhoto');



$router->get('/pilot-students', 'PilotStudentController@index');
$router->post('/pilot-students/assign', 'PilotStudentController@assign');
$router->post('/pilot-students/unassign', 'PilotStudentController@unassign');


/** Dispatch */
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
