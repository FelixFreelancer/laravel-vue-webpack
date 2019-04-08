<?php

function authyApi()
{
    $authy_api = new \Authy\AuthyApi(config('services.authy.token'));
    return $authy_api;
}