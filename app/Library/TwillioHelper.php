<?php

function twillioApi()
{
    return new \Twilio\Rest\Client(config('services.twillio.id'), config('services.twillio.token'));
}

function twilioTwiML()
{
    return new \Twilio\Twiml();
}