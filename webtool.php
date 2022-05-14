<?php
require_once __DIR__ . "/vendor/autoload.php";

require_once 'Helper.php';

use application\core\Application;

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

if ($_SERVER['argv'][1] === 'make:controller')
{
    $componentName = $_SERVER['argv'][2];
    $componentPath = "controllers/$componentName.php";
    $componentContent = file_get_contents(__DIR__."/templates/ControllerTemplate.txt");
    $componentContent = str_replace('$componentName', $componentName, $componentContent);

    if (!file_exists($componentPath))
    {
        file_put_contents($componentPath, $componentContent);
        echo "$componentPath created";
    }
    else
    {
        echo "$componentPath is exists!!";
    }
}

if ($_SERVER['argv'][1] === 'drop:controller')
{
    $componentName = $_SERVER['argv'][2];
    $componentPath = "controllers/$componentName.php";

    if (file_exists($componentPath))
    {
        unlink($componentPath);
        echo "$componentPath deleted";
    }
    else
    {
        echo "$componentPath is not exists!!";
    }
}

if ($_SERVER['argv'][1] === 'make:model')
{
    $componentName = $_SERVER['argv'][2];
    $componentPath = "models/$componentName.php";
    $componentContent = file_get_contents(__DIR__."/templates/ModelTemplate.txt");
    $componentContent = str_replace('$componentName', $componentName, $componentContent);

    if (!file_exists($componentPath))
    {
        file_put_contents($componentPath, $componentContent);
        echo "$componentPath created";
    }
    else
    {
        echo "$componentPath is exists!!";
    }
}

if ($_SERVER['argv'][1] === 'drop:model')
{
    $componentName = $_SERVER['argv'][2];
    $componentPath = "models/$componentName.php";

    if (file_exists($componentPath))
    {
        unlink($componentPath);
        echo "$componentPath deleted";
    }
    else
    {
        echo "$componentPath is not exists!!";
    }
}

if ($_SERVER['argv'][1] === 'make:middleware')
{
    $componentName = $_SERVER['argv'][2];
    $componentPath = "middlewares/$componentName.php";
    $componentContent = file_get_contents(__DIR__."/templates/MiddlewareTemplate.txt");
    $componentContent = str_replace('$componentName', $componentName, $componentContent);

    if (!file_exists($componentPath))
    {
        file_put_contents($componentPath, $componentContent);
        echo "$componentPath created";
    }
    else
    {
        echo "$componentPath is exists!!";
    }
}

if ($_SERVER['argv'][1] === 'drop:middleware')
{
    $componentName = $_SERVER['argv'][2];
    $componentPath = "middlewares/$componentName.php";

    if (file_exists($componentPath))
    {
        unlink($componentPath);
        echo "$componentPath deleted";
    }
    else
    {
        echo "$componentPath is not exists!!";
    }
}

if ($_SERVER['argv'][1] === 'make:migration')
{
    $componentName = $_SERVER['argv'][2];
    $componentName = explode('_', $componentName);
    $componentName = array_map(fn ($name) => ucfirst($name), $componentName);
    $componentName = implode('', $componentName);
    $componentPath = "migrations/".date('d_m_Y')."_$componentName.php";
    $componentContent = file_get_contents(__DIR__."/templates/MigrationTemplate.txt");
    $componentContent = str_replace('$componentName', $componentName, $componentContent);
    file_put_contents($componentPath, $componentContent);
}

if ($_SERVER['argv'][1] === 'apply:migration')
{
    $application = new Application();
    $application->database->applyMigrations();
}

if ($_SERVER['argv'][1] === 'remove:migrations')
{
    $application = new Application();
    $application->database->removeMigrations();
}
