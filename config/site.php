<?php

return [
	'default_currency' => '£',
    'payment'                => [
        'epdq' => [
          'pspid' => env('EPDQ_PSPID'),
          // 'password' => env('EPDQ_PASSWORD'),
          'mode' => env('EPDQ_MODE'),
          'test' => env('EPDQ_TEST_URL'),
          'prod' => env('EPDQ_PROD_URL'),
          // 'user_id' => env('EPDQ_USER_ID'),
          'sha_phrase' => env('EPDG_SHAPHRASE'),
          'sha' => env('EPDQ_SHA'),
        ],
        'paypal' => [
          'url' => env('PAYPAL_SUBSCRIPTION_URL'),
          'button' => env('PAYPAL_BUTTON_ID')
        ]
    ],
    'gender'                => [
        '1' => 'male',
        '2' => 'female',
        '3' => 'others'
    ],
    'roles'                 => [
        '1' => 'Admin',
        '2' => 'User'
    ],
    'quotation_status'      => [
        '1' => 'New Request',
        '2' => 'Offered',
        '3' => 'Paid'
    ],
    'otp_expire'            => '10', //in minutes
    'otp_disable'            => '2', //in minutes
    'call_disable'            => '4', //in minutes ( we need just 2 minutes here but call will appear after otp and we have 3 minute of otp. So here the valus is 6)
    'payment_types'         => [
        '1' => 'Shipping',
        '2' => 'Shopper',
        '3' => 'Membership',
    ],
    'payment_gateway_types' => [
        '1' => 'Paypal',
        '2' => 'ePDQ',
    ],
    'months'            => [
        1  => 'Jan.',
        2  => 'Feb.',
        3  => 'Mar.',
        4  => 'Apr.',
        5  => 'May',
        6  => 'Jun.',
        7  => 'Jul.',
        8  => 'Aug.',
        9  => 'Sep.',
        10 => 'Oct.',
        11 => 'Nov.',
        12 => 'Dec.'
    ],
    'parcel2go'         => [
        'grant_type'    => 'client_credentials',
        'scope'         => 'public-api',
        'client_id'     => '587d60f27be14c58b204a84d00ced3b0:GlobalParcelForward',
        'client_secret' => 'gpfislove'
    ],
	'estimated_delivery_period' => '10', //in days
    'shipment_statuses' => [
        '1' => 'Created',
        '2' => 'Shipping option selected',
        '3' => 'Paid',
        '4' => 'Payment verified',
        '5' => 'Shipped',
        '6' => 'Delivered'
    ],

    'quotation_statuses' => [
        '1' => 'Quote Requested',
        '2' => 'Payment Awaited',
        '3' => 'Paid'
    ],
    'inactivity_logout'=>'30', //in min
    'customer_role_id'=>'2',
    'pagination' => '10',
    'membership_validity' => '1',//in month
		'insurance_charge' => '10',
		'contact_us_email' => [
       'support@globalparcelforward.com ',
		]
  ];
