# Authentication

This document describes the authentication mechanisms supported by the Yoti PHP SDK.

## Overview

The Yoti PHP SDK supports two authentication methods:

1. **Signed Request Authentication** (Traditional) - Uses PEM file-based request signing
2. **Token-Based Authentication** (Central Auth) - Uses authentication tokens with Bearer authorization

## Signed Request Authentication (Traditional)

This is the traditional authentication method that uses a PEM file to sign requests.

### Setup

```php
use Yoti\YotiClient;

$yotiClient = new YotiClient(
    'YOUR_CLIENT_SDK_ID',
    '/path/to/your-application.pem'
);
```

### How It Works

- Each request is signed using your private key from the PEM file
- The signature is sent in the `X-Yoti-Auth-Digest` header
- A nonce and timestamp are included as query parameters for security

## Token-Based Authentication (Central Auth)

The new token-based authentication provides a simpler and more flexible authentication mechanism.

### Setup

To use token-based authentication, provide the `auth.token` configuration option:

```php
use Yoti\YotiClient;
use Yoti\Util\Config;

$yotiClient = new YotiClient(
    'YOUR_CLIENT_SDK_ID',
    '', // Empty PEM string when using token auth
    [
        Config::AUTH_TOKEN => 'YOUR_AUTH_TOKEN'
    ]
);
```

### Using with RequestBuilder

You can also use token authentication directly with the `RequestBuilder`:

```php
use Yoti\Http\RequestBuilder;
use Yoti\Util\Config;

$config = new Config([
    Config::AUTH_TOKEN => 'YOUR_AUTH_TOKEN'
]);

$request = (new RequestBuilder($config))
    ->withBaseUrl('https://api.yoti.com')
    ->withEndpoint('/some-endpoint')
    ->withGet()
    ->build();
```

### How It Works

- The authentication token is sent in the `Authorization` header as a Bearer token
- No request signing is required
- No nonce or timestamp query parameters are added

## Advanced Usage

### Explicitly Setting Auth Strategy

For advanced use cases, you can explicitly set the authentication strategy:

```php
use Yoti\Http\Auth\TokenAuthStrategy;
use Yoti\Http\RequestBuilder;

$authStrategy = new TokenAuthStrategy('YOUR_AUTH_TOKEN');

$request = (new RequestBuilder())
    ->withBaseUrl('https://api.yoti.com')
    ->withEndpoint('/some-endpoint')
    ->withAuthStrategy($authStrategy)
    ->withGet()
    ->build();
```

### Custom Authentication Strategies

You can implement your own authentication strategy by implementing the `AuthStrategyInterface`:

```php
use Yoti\Http\Auth\AuthStrategyInterface;
use Yoti\Http\Payload;

class CustomAuthStrategy implements AuthStrategyInterface
{
    public function applyAuth(
        array $headers,
        string $endpoint,
        string $httpMethod,
        ?Payload $payload = null
    ): array {
        // Add your custom authentication logic here
        $headers['X-Custom-Auth'] = 'custom-value';
        return $headers;
    }
}
```

## Migration Guide

### Migrating from Signed Request to Token-Based Authentication

1. **Obtain an authentication token** from Yoti Central Auth system
2. **Update your YotiClient initialization**:

Before:
```php
$yotiClient = new YotiClient(
    'YOUR_CLIENT_SDK_ID',
    '/path/to/your-application.pem'
);
```

After:
```php
use Yoti\Util\Config;

$yotiClient = new YotiClient(
    'YOUR_CLIENT_SDK_ID',
    '', // Empty PEM string
    [
        Config::AUTH_TOKEN => 'YOUR_AUTH_TOKEN'
    ]
);
```

3. **Update environment configuration** if you're using environment variables for configuration

### Backward Compatibility

The SDK maintains full backward compatibility with the signed request authentication method. Existing applications using PEM files will continue to work without any changes.

## Best Practices

1. **Token Security**: Store authentication tokens securely, similar to how you store PEM files
2. **Token Rotation**: Implement token rotation policies according to your security requirements
3. **Environment Variables**: Use environment variables to manage tokens in different environments (development, staging, production)
4. **Error Handling**: Implement proper error handling for authentication failures

## Configuration Options

The following configuration options are available:

| Option | Type | Description |
|--------|------|-------------|
| `Config::AUTH_TOKEN` | `string` | Authentication token for token-based authentication |
| `Config::API_URL` | `string` | Base API URL (optional) |
| `Config::SDK_IDENTIFIER` | `string` | Custom SDK identifier (optional) |
| `Config::SDK_VERSION` | `string` | Custom SDK version (optional) |
| `Config::HTTP_CLIENT` | `ClientInterface` | Custom HTTP client (optional) |
| `Config::LOGGER` | `LoggerInterface` | Custom logger (optional) |

## Example: Using Both Authentication Methods

You can configure different services to use different authentication methods:

```php
use Yoti\Http\RequestBuilder;
use Yoti\Http\Auth\SignedRequestAuthStrategy;
use Yoti\Http\Auth\TokenAuthStrategy;
use Yoti\Util\PemFile;

// Service using signed request authentication
$pemFile = PemFile::fromFilePath('/path/to/your-application.pem');
$signedRequestAuth = new SignedRequestAuthStrategy($pemFile);

$request1 = (new RequestBuilder())
    ->withBaseUrl('https://api.yoti.com')
    ->withEndpoint('/legacy-endpoint')
    ->withAuthStrategy($signedRequestAuth)
    ->withGet()
    ->build();

// Service using token authentication
$tokenAuth = new TokenAuthStrategy('YOUR_AUTH_TOKEN');

$request2 = (new RequestBuilder())
    ->withBaseUrl('https://api.yoti.com')
    ->withEndpoint('/new-endpoint')
    ->withAuthStrategy($tokenAuth)
    ->withGet()
    ->build();
```

## Troubleshooting

### Common Issues

**Invalid Token Error**
- Verify your token is correct and not expired
- Check that the token is properly formatted in the configuration

**Authentication Method Not Working**
- Ensure you're using the correct authentication method for the endpoint you're calling
- Verify your SDK configuration is correct

**Missing Authorization Header**
- Confirm that `Config::AUTH_TOKEN` is set when using token-based authentication
- Check that the RequestBuilder is using the correct Config instance

## Support

For questions or issues related to authentication, please contact Yoti support at https://support.yoti.com
