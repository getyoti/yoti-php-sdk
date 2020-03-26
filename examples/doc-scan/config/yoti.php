<?php

return [
    'client.sdk.id' => env('YOTI_SDK_ID'),
    'doc.scan.iframe.url' => env('YOTI_DOC_SCAN_IFRAME_URL', 'https://api.yoti.com/idverify/v1/web/index.html'),
    'doc.scan.api.url' => env('YOTI_DOC_SCAN_API_URL') ?: null,
    'pem.file.path' => (function($filePath) {
        return strpos($filePath, '/') === 0 ? $filePath : base_path($filePath);
    })(env('YOTI_KEY_FILE_PATH')),
];
