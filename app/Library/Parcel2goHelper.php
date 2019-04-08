<?php

function getAuthToken()
{
    if (cache()->has('parcel2go_token')) {
        $rst = cache('parcel2go_token');
        return $rst['access_token'];
    } else {
        $parcel2go = config('site.parcel2go');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.parcel2go.com/auth/connect/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "grant_type=" . $parcel2go['grant_type'] . "&scope=" . $parcel2go['scope'] . "&client_id=" . $parcel2go['client_id'] . "&client_secret=" . $parcel2go['client_secret']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $resultArray = json_decode($result, true);
        cache()->put('parcel2go_token', $resultArray, $resultArray['expires_in'] - 1000);
        return $resultArray['access_token'];
    }
    return '';
}

function getQuotes($token, $input)
{
    $header = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, "https://www.parcel2go.com/api/quotes");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $resultArray = json_decode($result, true);
    return $resultArray;
}

?>
