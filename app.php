<?php

ini_set('display_errors', true);
set_time_limit(600);

require 'config.php';
require 'vendor/autoload.php';

function dd($content) {
    var_dump($content);
    die();
}

function logThis($message) {
    file_put_contents('sync.log', date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
    echo $message . PHP_EOL;
}

use Picqer\Api\Client as PicqerClient;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Adapter\Ftp as FtpAdapter;

// Picqer connection
$picqerclient = new PicqerClient($config['picqer-company'], $config['picqer-apikey']);

// Local filesystem
$filesystem = new Filesystem(new LocalAdapter(__DIR__));

// Get data
$datakeeper = new PicqerAmazonImporter\DataKeeper($filesystem);
$data = $datakeeper->getData();

// Get orders from Amazon
$amazonGetter = new PicqerAmazonImporter\AmazonGetter($config, $data);
$amazonOrders = $amazonGetter->getNewOrders();

// Create new orders in Picqer
$orderImporter = new PicqerAmazonImporter\OrderImporter($picqerclient, $config);
$orders = $orderImporter->importOrders($amazonOrders);

// Set processed orders in data array
foreach ($orders as $amazonId => $picqerId) {
    $data['processedOrders'][] = $amazonId;
}

// Save changed data
$datakeeper->saveData($data);

echo count($orders) . ' orders imported from Amazon to Picqer';