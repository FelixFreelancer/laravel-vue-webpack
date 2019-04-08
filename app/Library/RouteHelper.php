<?php

/**
 * @desc current route find and give active class
 * @date 20 Jan 2018 13:36
 */
function route_to_reply_able($routes, $condition = true)
{
    return call_user_func_array([app('router'), 'is'], (array)$routes) && $condition ? 'active' : '';
}

/**
 * @desc current route find and give defined class for active and wrong return
 * @date 20 Jan 2018 13:37
 */
function route_to_reply_submenu($submenu, $true_return, $false_return = '')
{
    $condition = true;

    $routes = get_submenu_active_routes($submenu);

    return call_user_func_array([app('router'), 'is'], (array)$routes) && $condition ? $true_return : $false_return;
}

/**
 * @desc submenu active
 * @date 20 Jan 2018 13:37
 */
function get_submenu_active_routes($submenu)
{
    $submenu = collect($submenu);
    return $submenu->pluck('active_routes')->flatten()->toArray();
}