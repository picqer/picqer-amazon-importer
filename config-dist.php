<?php

$config = array(
    'picqer-company' => '', // picqer company subdomain
    'picqer-apikey' => '', // your api key

    'picqer-close-orders' => true, // do we need to close the order after import?
    'picqer-idcustomer' => 1, // id of the customer you want all the orders to be added to
    'picqer-idtag' => null, // id of the tag new orders needs to get

    'amazon-service-url' => 'https://mws-eu.amazonservices.com/', // Main Amazon endpoint
);

// Configure multiple Amazon store ID's
// DE
$config['amazon-stores'][0] = array(
    'amazon-merchant-id' => '',
    'amazon-marketplace-id' => '',
    'amazon-access-key-id' => '',
    'amazon-secret-access-key' => '',
);

// UK
$config['amazon-stores'][1] = array(
    'amazon-merchant-id' => '',
    'amazon-marketplace-id' => '',
    'amazon-access-key-id' => '',
    'amazon-secret-access-key' => '',
);