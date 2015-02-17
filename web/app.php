<?php
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__ . '/../app/bootstrap.php.cache';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
$loader = new ApcClassLoader('sf2', $loader);
$loader->register(true);
*/

require_once __DIR__ . '/../app/AppKernel.php';
//require_once __DIR__.'/../app/AppCache.php';

$appEnv = getenv('APPLICATION_ENV') ?: 'prod';
$appDebug = getenv('APPLICATION_DEBUG') ?: false;

if ($appEnv !== 'prod') {
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);
}

$kernel = new AppKernel($appEnv, $appDebug);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
