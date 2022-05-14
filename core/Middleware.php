<?php
namespace application\core;

interface Middleware
{
    public function handle(Request $request, Response $response);
}
