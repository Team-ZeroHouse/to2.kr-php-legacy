<?php
require_once('../bootstrap.php');

$req = Request::get_instance();

if (BLACKLIST && in_array($req->country, $blacklist))
{
    exit_with_code(403);
}

Route::routing($req);