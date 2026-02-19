# Yoti Doc Scan

## About

Yoti Doc Scan can be seamlessly integrated with your website, app or custom product so you can perform secure identity checks. You'll be able to request specific ID documents from users directly from your website or app.

See the the [Developer Docs](https://developers.yoti.com/yoti/getting-started-docscan) for more information.

## Running the example

- See the [Doc Scan Example](../examples/doc-scan/README.md) folder for instructions on how to run the Doc Scan Example project

## SDK Configuration

The DocScan SDK can be configured using the `SdkConfigBuilder` to customize the user experience and flow.

### Suppressed Screens Configuration

You can customize and shorten the IDV flow by suppressing specific screens that are not required for your use case. This is done using the `suppressed_screens` configuration option.

#### Setting Suppressed Screens

You can suppress screens in two ways:

1. **Using an array of screen identifiers:**

```php
use Yoti\DocScan\Session\Create\SdkConfigBuilder;

$sdkConfig = (new SdkConfigBuilder())
    ->withSuppressedScreens(['intro_screen', 'document_capture_instruction'])
    ->build();
```

2. **Adding screens individually:**

```php
use Yoti\DocScan\Session\Create\SdkConfigBuilder;

$sdkConfig = (new SdkConfigBuilder())
    ->withSuppressedScreen('intro_screen')
    ->withSuppressedScreen('document_capture_instruction')
    ->build();
```

#### Complete Configuration Example

```php
use Yoti\DocScan\Session\Create\SdkConfigBuilder;
use Yoti\DocScan\Session\Create\SessionSpecificationBuilder;

$sessionSpec = (new SessionSpecificationBuilder())
    ->withClientSessionTokenTtl(600)
    ->withResourcesTtl(604800)
    ->withUserTrackingId('some-user-tracking-id')
    ->withSdkConfig(
        (new SdkConfigBuilder())
            ->withAllowsCameraAndUpload()
            ->withPrimaryColour('#2875BC')
            ->withSecondaryColour('#FFFFFF')
            ->withFontColour('#FFFFFF')
            ->withLocale('en-GB')
            ->withPresetIssuingCountry('GBR')
            ->withSuccessUrl('https://your-app.com/success')
            ->withErrorUrl('https://your-app.com/error')
            ->withPrivacyPolicyUrl('https://your-app.com/privacy-policy')
            ->withBiometricConsentFlow('EARLY')
            ->withBrandId('your_brand_id')
            // Suppress specific screens to customize the flow
            ->withSuppressedScreens(['intro_screen', 'document_capture_instruction'])
            ->build()
    )
    ->build();
```

#### Retrieving Suppressed Screens Configuration

When retrieving session configuration, you can access the suppressed screens configuration:

```php
use Yoti\DocScan\DocScanClient;

$docScanClient = new DocScanClient($sdkId, $pemFile);
$sessionConfiguration = $docScanClient->getSessionConfiguration($sessionId);

// Get the full SDK configuration
$sdkConfig = $sessionConfiguration->getSdkConfig();

// Get specifically the suppressed screens
$suppressedScreens = $sessionConfiguration->getSuppressedScreens();

if ($suppressedScreens !== null) {
    echo "Suppressed screens: " . implode(', ', $suppressedScreens);
}
```

### Screen Identifiers

The exact screen identifiers available for suppression depend on your specific IDV flow configuration. Common screen identifiers include:

- `intro_screen` - Introduction/welcome screen
- `document_capture_instruction` - Document capture instruction screen
- `face_capture_instruction` - Face capture instruction screen
- `confirmation_screen` - Final confirmation screen

**Note:** Contact your Yoti integration team for the complete list of available screen identifiers for your specific use case.

### Best Practices

1. **Test thoroughly:** When suppressing screens, ensure that users still have enough context to complete the flow successfully.

2. **Validation:** The SDK will validate that screen identifiers correspond to known screens. Invalid identifiers will be ignored.

3. **User Experience:** Consider the impact on user experience when removing instructional or confirmation screens.

4. **Documentation:** Keep track of which screens are suppressed for different use cases in your application.

5. **Monitoring:** Monitor completion rates and user feedback when using suppressed screens to ensure the shortened flow meets your users' needs.
