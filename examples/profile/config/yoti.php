<?php

return [
    'client.sdk.id' => env('YOTI_SDK_ID'),
    'scenario.id' => env('YOTI_SCENARIO_ID'),
    'pem.file.path' => (function($filePath) {
        return strpos($filePath, '/') === 0 ? $filePath : base_path($filePath);
    })(env('YOTI_KEY_FILE_PATH')),
];
