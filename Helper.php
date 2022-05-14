<?php
function transformDirectoryPath(string $path)
{
    return str_replace('/', '\\', $path);
}

function transformWebsitePath(string $path)
{
    return str_replace('\\', '/', $path);
}

function getWebsiteAddress()
{
    if (isset($_SERVER['HTTP_HOST']))
    {
        $websiteAddress = isset($_SERVER['HTTPS']) ? "https://" : "http://";
        $websiteAddress .= $_SERVER['HTTP_HOST'];
        $websiteAddress .= str_replace($_SERVER['DOCUMENT_ROOT'], '', transformWebsitePath(ROOT_DIRECTORY));
        return $websiteAddress;
    }
}

function asset(string $path)
{
    return transformWebsitePath(PUBLIC_DIRECTORY.$path);
}

function url(string $path)
{
    return transformWebsitePath(ROOT_WEBSITE_ADDRESS.$path);
}

function getTextBetweenTags($string, $tagname)
{
    echo '<pre>';
    var_dump(trim($string), trim($tagname));
    echo '</pre>';
    echo '<hr/>';
    $pattern = "#<$tagname.*?>(.*?)</'.$tagname.'>#s";

    echo '<pre>';
    var_dump(trim($string), trim($tagname), trim($pattern));
    echo '</pre>';
    echo '<hr/>';
    die;

    if (preg_match($pattern, $string, $matches))
    {
        return $matches[1];
    }

    return $string;
}

function getTextBetweenTag($string)
{
    // echo '<pre>';
    // var_dump($string);
    // echo '</pre>';
    // echo '<hr/>';

    // $string = substr($string, 3, -5);

    // echo '<pre>';
    // var_dump($string);
    // echo '</pre>';
    // echo '<hr/>';

    // die;

    return substr($string, 4, -5);
}

function extractDigitNumberInString(string $string)
{
    $pattern = '@([0-9]+)@siu';

    if (preg_match($pattern, $string, $matches))
    {
        return $matches[1];
    }

    return $string;
}
