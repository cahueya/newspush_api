<?php
defined('C5_EXECUTE') or die("Access Denied.");

$router->post('/pages/write', '\Concrete\Package\NewspushClient\Api\Page\PagesController::write')
    ->setScopes('pages:write');

