<?php

use Yoti\Constants;

return [
    'client.sdk.id' => env('YOTI_SDK_ID'),
    'doc.scan.iframe.url' => (env('YOTI_DOC_SCAN_API_URL') ?: Constants::DOC_SCAN_API_URL) . '/web/index.html',
    'pem.file.path' => (function($filePath) {
        return strpos($filePath, '/') === 0 ? $filePath : base_path($filePath);
    })(env('YOTI_KEY_FILE_PATH')),
];
