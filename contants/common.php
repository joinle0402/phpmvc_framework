<?php
namespace application\contants;

define('ROOT_DIRECTORY', dirname(__DIR__));
define('ROOT_WEBSITE_ADDRESS', getWebsiteAddress());
define('PUBLIC_DIRECTORY', transformWebsitePath(ROOT_WEBSITE_ADDRESS."/public"));

