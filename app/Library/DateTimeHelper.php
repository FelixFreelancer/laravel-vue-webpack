<?php

function date_time_datatable($date)
{
    return date(config('date_time.front_date_time'), strtotime($date));
}

function date_datatable($date)
{
    return date(config('date_time.front_date'), strtotime($date));
}

function date_database($date)
{
    return date(config('date_time.database_date'), strtotime($date));
}

function date_time_database($date)
{
    return date(config('date_time.database_date_time'), strtotime($date));
}

function clientShowingDate($date)
{
    return date(config('date_time.client_showing_date'), strtotime($date));
}

function clientShowingDateTime($date)
{
    return date(config('date_time.client_showing_date_time'), strtotime($date));
}

function datepicker_date_time($date)
{
    return date(config('date_time.datepicker_date_time'), strtotime($date));
}


function convertInAgo($dateTime)
{
    return \Carbon\Carbon::createFromTimeStamp(strtotime($dateTime))->diffForHumans();
}
