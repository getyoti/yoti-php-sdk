<?php

declare(strict_types=1);

namespace Yoti\Auth;

use Psr\Http\Client\ClientInterface;
use Yoti\Auth\Exception\AuthException;
use Yoti\Util\PemFile;

/**
 * Generates authentication tokens by performing an OAuth2 client_credentials
 * grant using a PS384-signed JWT as the client assertion.
 *
 * The token can then be used with BearerTokenStrategy for authorized requests.
 *
 * Usage:
 *   $generator = AuthenticationTokenGenerator::builder()
 *       ->withSdkId($sdkId)
 *       ->withPemFile($pemFile)
 *       ->build();
 *
 *   $response = $generator->generate(['scope1', 'scope2']);
 *   $token = $response->getAccessToken();
 *
 * Mirrors the Java SDK's com.yoti.auth.AuthenticationTokenGenerator.
 */
class AuthenticationTokenGenerator
{
    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var PemFile
     */
    private $pemFile;

    /**
     * @var callable
     */
    private $jwtIdSupplier;

    /**
     * @var string
     */
    private $authApiUrl;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @param string $sdkId
     * @param PemFile $pemFile
     * @param callable $jwtIdSupplier
     * @param string $authApiUrl
     * @param ClientInterface|null $httpClient
     */
    public function __construct(
        string $sdkId,
        PemFile $pemFile,
        callable $jwtIdSupplier,
        string $authApiUrl,
        ?ClientInterface $httpClient = null
    ) {
        $this->sdkId = $sdkId;
        $this->pemFile = $pemFile;
        $this->jwtIdSupplier = $jwtIdSupplier;
        $this->authApiUrl = $authApiUrl;
        $this->httpClient = $httpClient ?? new \GuzzleHttp\Client();
    }

    /**
     * Creates a new Builder instance.
     *
     * @return Builder
     */
    public static function builder(): Builder
    {
        return new Builder();
    }

    /**
     * Generate an authentication token for the supplied scopes.
     *
     * @param array<string> $scopes
     *
     * @return CreateAuthenticationTokenResponse
     *
     * @throws AuthException
     * @throws \InvalidArgumentException
     */
    public function generate(array $scopes): CreateAuthenticationTokenResponse
    {
        if (count($scopes) === 0) {
            throw new \InvalidArgumentException('scopes must not be empty');
        }

        $jwt = $this->createSignedJwt();

        $formParams = [
            'grant_type' => 'client_credentials',
            'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
            'scope' => implode(' ', $scopes),
            'client_assertion' => $jwt,
        ];

        $responseBody = $this->performFormRequest($formParams);

        $responseData = json_decode($responseBody, true);
        if (!is_array($responseData)) {
            throw new AuthException('Failed to decode authentication token response');
        }

        return new CreateAuthenticationTokenResponse($responseData);
    }

    /**
     * Create a PS384-signed JWT for the client assertion.
     *
     * @return string
     *
     * @throws AuthException
     */
    private function createSignedJwt(): string
    {
        $sdkIdProperty = sprintf('sdk:%s', $this->sdkId);
        $now = time();
        $jwtId = ($this->jwtIdSupplier)();

        $header = [
            'alg' => 'PS384',
            'typ' => 'JWT',
        ];

        $claims = [
            'iss' => $sdkIdProperty,
            'sub' => $sdkIdProperty,
            'jti' => $jwtId,
            'aud' => $this->authApiUrl,
            'exp' => $now + 300, // 5 minutes
            'iat' => $now,
        ];

        // Get the private key from PEM
        $privateKey = openssl_pkey_get_private((string) $this->pemFile);
        if ($privateKey === false) {
            throw new AuthException('Failed to load private key from PEM file');
        }

        return \Firebase\JWT\JWT::encode($claims, $privateKey, 'PS384', null, $header);
    }

    /**
     * Perform an application/x-www-form-urlencoded POST request.
     *
     * @param array<string, string> $formParams
     *
     * @return string
     *
     * @throws AuthException
     */
    private function performFormRequest(array $formParams): string
    {
        $postData = http_build_query($formParams);

        $request = new \GuzzleHttp\Psr7\Request(
            'POST',
            $this->authApiUrl,
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Content-Length' => (string) strlen($postData),
            ],
            $postData
        );

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (\Psr\Http\Client\ClientExceptionInterface $e) {
            throw new AuthException('Auth token request failed: ' . $e->getMessage(), 0, $e);
        }

        $httpCode = $response->getStatusCode();
        $responseBody = (string) $response->getBody();

        if ($httpCode >= 400) {
            throw new AuthException(
                sprintf(
                    'Auth token request failed with HTTP %d: %s',
                    $httpCode,
                    $responseBody
                )
            );
        }

        return $responseBody;
    }
}
