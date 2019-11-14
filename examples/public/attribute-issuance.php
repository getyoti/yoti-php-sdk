<?php

// Load dependent packages and env data

require_once __DIR__ . '/../bootstrap.php';

use Yoti\Http\Payload;
use Yoti\Http\RequestBuilder;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Extension\ThirdPartyAttributeExtensionBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;

$yotiClient = new Yoti\YotiClient(YOTI_SDK_ID, YOTI_KEY_FILE_PATH, YOTI_CONNECT_BASE_URL);

$token = isset($_GET['token']) ? $_GET['token'] : '';

define('ATTRIBUTE_NAME', getenv('YOTI_ISSUE_ATTRIBUTE_NAME'));
define('HTTP_HOST_NO_PORT', explode(':', $_SERVER['HTTP_HOST'])[0]);

if (empty($token)) {

  $request = (new RequestBuilder())
      ->withBaseUrl(YOTI_CONNECT_BASE_URL . '/attribute-registry')
      ->withEndpoint('/definitions')
      ->withPemFilePath(YOTI_KEY_FILE_PATH)
      ->withHeader('X-Yoti-Auth-Id', YOTI_SDK_ID)
      ->withPost()
      ->withPayload(new Payload([
          "name" => ATTRIBUTE_NAME,
          "mimeType" => "text/plain",
          "subjectMimeType" => "text/plain",
          "config" => [
            "accessProperties" => [
              "requestors" => [
                [
                  "domain" => HTTP_HOST_NO_PORT
                ],
                [
                  "domain" => "www.partner1.com"
                ]
              ]
            ],
            "localisationProperties" => [
              "defaultLocalisation" => [
                "locale" => "en_GB",
                "value" => "Example.com identifier"
              ]
            ],
            "issuanceProperties" => [
              "maxCardinality" => 1,
              "issuers" => [
                [
                  "domain" => HTTP_HOST_NO_PORT
                ]
              ],
              "issuanceURL" => sprintf('https://%s/get-identifier', $_SERVER['HTTP_HOST'])
            ],
            "displayProperties" => [
              "displayValueMimeType" => "text/plain"
            ]
          ]
        ]))
      ->build();

  $response = $request->execute();
  if ($response->getStatusCode() === 201) {
    $statusMessage = "Created " . ATTRIBUTE_NAME;
  }
  else {
    $statusMessage = $response->getBody();
  }

  $thirdPartyExtension = (new ThirdPartyAttributeExtensionBuilder())
      ->withDefinition(ATTRIBUTE_NAME)
      ->withExpiryDate(new \DateTime('2020-12-12'))
      ->build();

  $policy = (new DynamicPolicyBuilder())
      ->withSelfie()
      ->build();

  $scenario = (new DynamicScenarioBuilder())
      ->withPolicy($policy)
      ->withCallbackEndpoint('/attribute-issuance.php')
      ->withExtension($thirdPartyExtension)
      ->build();

  $shareUrlResult = $yotiClient->createShareUrl($scenario);
} else {
  $profileAttributes = [];

  $activityDetails = $yotiClient->getActivityDetails($token);
  $attributeIssuanceDetails = $activityDetails->getExtraData()->getAttributeIssuanceDetails();

  $attributes = [];
  foreach ($attributeIssuanceDetails->getIssuingAttributes() as $attribute) {
    $attributes[] = [
      'name' => $attribute->getName(),
      'value' => (string) microtime(),
    ];
  }
  $request = (new RequestBuilder())
      ->withBaseUrl(YOTI_CONNECT_BASE_URL . '/attribute-registry')
      ->withEndpoint('/attributes')
      ->withPemFilePath(YOTI_KEY_FILE_PATH)
      ->withHeader('X-Yoti-Auth-Id', YOTI_SDK_ID)
      ->withPost()
      ->withPayload(new Payload([
          "issuance_token" => $attributeIssuanceDetails->getToken(),
          "attributes" => $attributes
      ]))
      ->build();

  $response = $request->execute();
  if ($response->getStatusCode() === 201) {
    $statusMessage = "Issued " . ATTRIBUTE_NAME;
  }
  else {
    $statusMessage = $response->getBody();
  }

  $policy = (new DynamicPolicyBuilder())
    ->withSelfie()
    ->withWantedAttributeByName(ATTRIBUTE_NAME)
    ->build();

  $scenario = (new DynamicScenarioBuilder())
    ->withPolicy($policy)
    ->withCallbackEndpoint('/profile.php')
    ->build();

  $shareUrlResult = $yotiClient->createShareUrl($scenario);
}

$jsonConfig = [
  'elements' => [
    [
      'domId' => "yoti-share-button",
      'clientSdkId' => getenv('YOTI_SDK_ID'),
      'shareUrl' => $shareUrlResult->getShareUrl(),
      'button' => [
        'label' => 'Use Yoti',
      ],
    ],
  ],
];

if (getenv('YOTI_SHARE_API_URL')) {
  $jsonConfig['shareApiUrl'] = getenv('YOTI_SHARE_API_URL');
}

if (getenv('YOTI_SHARE_CDN_URL')) {
  $jsonConfig['shareCdnUrl'] = getenv('YOTI_SHARE_CDN_URL');
}

?>
<!DOCTYPE html>
<html class="yoti-html">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Yoti Dynamic Share example</title>
        <link rel="stylesheet" type="text/css" href="assets/css/index.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    </head>
    <body class="yoti-body">
        <div style="text-align: center; font-family: Arial; padding: 10px; background-color: #8fcf94;"><?php echo htmlspecialchars($statusMessage); ?></div>
        <main>
            <section class="yoti-top-section">
                <div class="yoti-logo-section">
                    <a href="https://www.yoti.com" target="_blank">
                        <img class="yoti-logo-image" src="assets/images/logo.png" srcset="assets/images/logo@2x.png 2x" alt="Yoti"/>
                    </a>
                </div>

                <h1 class="yoti-top-header">Attribute Issuance Example</h1>

                <div class="yoti-sdk-integration-section">
                    <div id="yoti-share-button"></div>
                </div>

                <div class="yoti-login-or-separator">or</div>

                <div class="yoti-login-dialog">
                    <h2 class="yoti-login-dialog-header">Login with your email:</h2>

                    <input class="yoti-input" type="text" placeholder="Name"/>

                    <input class="yoti-input" type="text" placeholder="Email address"/>

                    <div class="yoti-login-actions">
                        <span class="yoti-login-forgot-button">forgot password?</span>

                        <button class="yoti-login-button">login</button>
                    </div>
                </div>
            </section>

            <section class="yoti-sponsor-app-section">
                <h3 class="yoti-sponsor-app-header">The Yoti app is free to download and use:</h3>

                <div class="yoti-store-buttons-section">
                    <a href="https://itunes.apple.com/us/app/yoti/id983980808?ls=1&mt=8" class="yoti-app-button-link">
                        <img
                            src="assets/images/app-store-badge.png"
                            srcset="assets/images/app-store-badge@2x.png 2x"
                            alt="Download on the App Store"/>
                    </a>

                    <a href="https://play.google.com/store/apps/details?id=com.yoti.mobile.android.live" class="yoti-app-button-link">
                        <img
                            src="assets/images/google-play-badge.png"
                            srcset="assets/images/google-play-badge@2x.png 2x"
                            alt="Yoti" alt="get it on Google Play"/>
                    </a>
                </div>
            </section>
        </main>

        <script src="<?php echo htmlspecialchars(getenv('YOTI_SHARE_CLIENT_URL')); ?>"></script>
        <script>
        window.Yoti.Share.init(<?php echo json_encode($jsonConfig); ?>);
        </script>
    </body>
</html>
