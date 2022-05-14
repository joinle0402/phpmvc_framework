<?php
namespace application\core;

class Response
{
    public function statusCode($statusCode)
    {
        http_response_code($statusCode);
    }

    public function redirect(string $uri)
    {
        $url = preg_match('@^(http|https)@is', $uri) ? $uri :ROOT_WEBSITE_ADDRESS.$uri;

        header('Location: '.$url);
        exit;
    }
}
