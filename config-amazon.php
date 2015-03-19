<?php
/**
 * Copyright 2013 CPI Group, LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 *
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

include 'config.php';

foreach ($config['amazon-stores'] as $storeId => $amazonStoreData) {
    $store['AmazonStore' . $storeId]['merchantId'] = $amazonStoreData['amazon-merchant-id'];
    $store['AmazonStore' . $storeId]['marketplaceId'] = $amazonStoreData['amazon-marketplace-id'][0];
    $store['AmazonStore' . $storeId]['keyId'] = $amazonStoreData['amazon-access-key-id'];
    $store['AmazonStore' . $storeId]['secretKey'] = $amazonStoreData['amazon-secret-access-key'];
}

$AMAZON_SERVICE_URL = $config['amazon-service-url'];

//Location of log file to use
$logpath = __DIR__ . '/log.txt';

//Name of custom log function to use
$logfunction = '';

//Turn off normal logging
$muteLog = false;
