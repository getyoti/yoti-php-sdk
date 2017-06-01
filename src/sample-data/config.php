<?php
return [
    'yoti_app_id' => 'stub-app-id',
    'yoti_scenario_id' => 'stub-scenario-id',
    'yoti_sdk_id' => 'stub-sdk-id',
    'yoti_pem' => [
        'name' => 'node-sdk-test.pem',
        'contents' => file_get_contents('/mnt/storage/code/clients/yoti/sdk/example/keys/node-sdk-test.pem'),
    ],

    'yoti_only_existing' => 0,
    'yoti_connect_email' => 0,
];