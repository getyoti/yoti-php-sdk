<?php

declare(strict_types=1);

namespace YotiTest;

class TestData
{
    const SDK_ID = '990a3996-5762-4e8a-aa64-cb406fdb0e68';
    const RECEIPT_JSON = __DIR__ . '/sample-data/receipt.json';
    const INVALID_YOTI_CONNECT_TOKEN = 'sdfsdfsdasdajsopifajsd=';
    const PEM_FILE = __DIR__ . '/sample-data/yw-access-security.pem';
    const INVALID_PEM_FILE = __DIR__ . '/sample-data/invalid.pem';
    const DUMMY_SELFIE_FILE = __DIR__ . '/sample-data/dummy-avatar.png';
    const AML_PRIVATE_KEY = __DIR__ . '/sample-data/aml-check-private-key.pem';
    const AML_PUBLIC_KEY = __DIR__ . '/sample-data/aml-check-public-key.pem';
    const AML_CHECK_RESULT_JSON = __DIR__ . '/sample-data/aml-check-result.json';
    const YOTI_CONNECT_TOKEN = __DIR__ . '/sample-data/connect-token.txt';
    const YOTI_CONNECT_TOKEN_DECRYPTED = 'i79CctmY-22ad195c-d166-49a2-af16-8f356788c9dd' .
        '-be094d26-19b5-450d-afce-070101760f0b';
    const MULTI_VALUE_ATTRIBUTE = __DIR__ . '/sample-data/attributes/multi-value.txt';
    const EXTRA_DATA_CONTENT = __DIR__ . '/sample-data/extra-data-content.txt';
    const THIRD_PARTY_ATTRIBUTE = __DIR__ . '/sample-data/attributes/third-party-attribute.txt';
    const PEM_AUTH_KEY = __DIR__ . '/sample-data/pem-auth-key.txt';
    const CONNECT_BASE_URL = 'https://api.yoti.com/api/v1';
}
