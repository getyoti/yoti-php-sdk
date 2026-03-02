<?php

declare(strict_types=1);

namespace Yoti\Auth;

/**
 * Response from the Yoti authentication token endpoint.
 *
 * Contains the access token, token type, expiry, and scopes
 * returned by the OAuth2 client_credentials grant.
 *
 * Mirrors the Java SDK's com.yoti.auth.CreateAuthenticationTokenResponse.
 */
final class CreateAuthenticationTokenResponse
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $tokenType;

    /**
     * @var int|null
     */
    private $expiresIn;

    /**
     * @var string|null
     */
    private $scope;

    /**
     * @param array<string, mixed> $responseData
     */
    public function __construct(array $responseData)
    {
        $this->accessToken = $responseData['access_token'] ?? '';
        $this->tokenType = $responseData['token_type'] ?? '';
        $this->expiresIn = isset($responseData['expires_in']) ? (int)$responseData['expires_in'] : null;
        $this->scope = $responseData['scope'] ?? null;
    }

    /**
     * Returns the Yoti Authentication token used to perform requests to other Yoti services.
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Returns the type of the newly generated authentication token.
     *
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * Returns the amount of time (in seconds) in which the newly generated
     * Authentication Token will expire.
     *
     * @return int|null
     */
    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    /**
     * A whitespace delimited string of scopes that the Authentication token has.
     *
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }
}
