<?php

declare(strict_types=1);

namespace Yoti\Test;

class TestData
{
    public const SDK_ID = '990a3996-5762-4e8a-aa64-cb406fdb0e68';
    public const RECEIPT_JSON = __DIR__ . '/sample-data/receipt.json';
    public const INVALID_YOTI_CONNECT_TOKEN = 'sdfsdfsdasdajsopifajsd=';
    public const PEM_FILE = __DIR__ . '/sample-data/yw-access-security.pem';
    public const INVALID_PEM_FILE = __DIR__ . '/sample-data/invalid.pem';
    public const DUMMY_SELFIE_FILE = __DIR__ . '/sample-data/dummy-avatar.png';
    public const DUMMY_PDF_FILE = __DIR__ . '/sample-data/barcapdf.pdf';
    public const AML_PRIVATE_KEY = __DIR__ . '/sample-data/aml-check-private-key.pem';
    public const AML_PUBLIC_KEY = __DIR__ . '/sample-data/aml-check-public-key.pem';
    public const AML_CHECK_RESULT_JSON = __DIR__ . '/sample-data/aml-check-result.json';
    public const YOTI_CONNECT_TOKEN = __DIR__ . '/sample-data/connect-token.txt';
    public const YOTI_CONNECT_TOKEN_DECRYPTED = 'i79CctmY-22ad195c-d166-49a2-af16-8f356788c9dd' .
        '-be094d26-19b5-450d-afce-070101760f0b';
    public const MULTI_VALUE_ATTRIBUTE = __DIR__ . '/sample-data/attributes/multi-value.txt';
    public const IDENTITY_PROFILE_ATTRIBUTE = __DIR__ . '/sample-data/attributes/identity-profile-report.json';
    public const EXTRA_DATA_CONTENT = __DIR__ . '/sample-data/extra-data-content.txt';
    public const THIRD_PARTY_ATTRIBUTE = __DIR__ . '/sample-data/attributes/third-party-attribute.txt';
    public const PEM_AUTH_KEY = __DIR__ . '/sample-data/pem-auth-key.txt';
    public const CONNECT_BASE_URL = 'https://api.yoti.com/sandbox/v1';

    public const DOC_SCAN_BASE_URL = 'https://api.yoti.com/sandbox/idverify/v1';
    public const DOC_SCAN_SESSION_ID = 'someSessionId';
    public const DOC_SCAN_MEDIA_ID = 'someMediaId';

    public const DOC_SCAN_SESSION_CREATION_RESPONSE = __DIR__ . '/sample-data/docs/session-creation.json';
    public const DOC_SCAN_SESSION_RESPONSE = __DIR__ . '/sample-data/docs/session.json';
    public const SESSION_RESULT_IDENTITY_PROFILE = __DIR__ . '/sample-data/sessionResultIdentityProfileResult.json';

    public const SOME_SESSION_SPEC_BYTES = [1,2,3,4];
    public const SOME_RESOURCE_ID = 'someResourceId';
    public const SOME_IMAGE_CONTENT_TYPE = 'someImageContentType';
    public const SOME_ID = 'someId';
    public const SOME_FRAMES = 1;
    public const INCLUDE_NON_LATIN = true;
}
