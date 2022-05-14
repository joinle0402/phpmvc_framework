<?php
session_start();
require_once __DIR__ . "/vendor/autoload.php";
require_once 'Helper.php';

use application\core\Application;
use Symfony\Component\DomCrawler\Crawler;

function loadCrawler(string $url)
{
    $contextOptions = array (
        "ssl" => array (
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );
    $html = file_get_contents($url, false, stream_context_create($contextOptions));
    $crawler = new Crawler($html);
    return $crawler;
}


$contantsFiles = scandir(__DIR__."/contants");
foreach ($contantsFiles as $contantsFile)
{
    if ($contantsFile !== '.' && $contantsFile !== '..')
    {
        if (file_exists(transformDirectoryPath(__DIR__."/contants/".$contantsFile)))
        {
            require_once transformDirectoryPath(__DIR__."/contants/".$contantsFile);
        }
    }
}

$configuationsFiles = scandir(__DIR__."/configurations");
foreach ($configuationsFiles as $configuationsFile)
{
    if ($configuationsFile !== '.' && $configuationsFile !== '..')
    {
        if (file_exists(transformDirectoryPath(__DIR__."/configurations/".$configuationsFile)))
        {
            require_once transformDirectoryPath(__DIR__."/configurations/".$configuationsFile);
        }
    }
}

$routerFiles = scandir(__DIR__."/routers");
foreach ($routerFiles as $routerFile)
{
    if ($routerFile !== '.' && $routerFile !== '..')
    {
        if (file_exists(transformDirectoryPath(__DIR__."/routers/".$routerFile)))
        {
            require_once transformDirectoryPath(__DIR__."/routers/".$routerFile);
        }
    }
}

$application = new Application(__DIR__);
$application->run();
