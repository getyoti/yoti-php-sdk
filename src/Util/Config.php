<?php

declare(strict_types=1);

namespace Yoti\Util;

use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Yoti\Constants;
use Yoti\Http\Client;

/**
 * Provides SDK configuration.
 */
class Config
{
    /** API URL key */
    public const API_URL = 'api.url';

    /** SDK identifier key */
    public const SDK_IDENTIFIER = 'sdk.identifier';

    /** SDK version key */
    public const SDK_VERSION = 'sdk.version';

    /** HTTP client key */
    public const HTTP_CLIENT = 'http.client';

    /** Logger key */
    public const LOGGER = 'logger';

    /**
     * @var array<string, mixed>
     */
    private $options = [];

    /**
     * @param array<string, mixed> $options
     *
     *   Configuration settings include the following options:
     *
     *   - Config::HTTP_CLIENT 'http.client' (\Psr\Http\Client\ClientInterface)
     *   - Config::LOGGER 'logger' (\Psr\Log\LoggerInterface)
     *   - Config::API_URL 'api.url' (string)
     *   - Config::SDK_IDENTIFIER 'sdk.identifier' (string)
     *   - Config::SDK_VERSION 'sdk.version' (string)
     *
     *   Example of creating config:
     *
     *     $config = new Config([
     *         Config::HTTP_CLIENT => new \Yoti\Http\Client(),
     *     ]);
     */
    public function __construct(array $options = [])
    {
        $this->validateKeys($options);
        $this->setStringValue(self::API_URL, $options);
        $this->setStringValue(self::SDK_IDENTIFIER, $options);
        $this->setStringValue(self::SDK_VERSION, $options);
        $this->setHttpClient($options);
        $this->setLogger($options);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @throws \InvalidArgumentException
     */
    private function validateKeys($options): void
    {
        $invalidKeys = array_diff(
            array_keys($options),
            [
                self::API_URL,
                self::SDK_IDENTIFIER,
                self::SDK_VERSION,
                self::HTTP_CLIENT,
                self::LOGGER,
            ]
        );
        if (count($invalidKeys) > 0) {
            throw new \InvalidArgumentException(sprintf(
                'The following configuration keys are not allowed: %s',
                implode(', ', $invalidKeys)
            ));
        }
    }

    /**
     * Set string configuration value.
     *
     * @param string $key
     * @param array<string, mixed> $options
     */
    private function setStringValue(string $key, array $options): void
    {
        if (isset($options[$key])) {
            $value = $options[$key];
            Validation::notEmptyString($value, sprintf('%s configuration value', $key));
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    private function set(string $key, $value): void
    {
        $this->options[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    private function get(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * @return string
     */
    public function getSdkIdentifier(): string
    {
        return $this->get(self::SDK_IDENTIFIER, Constants::SDK_IDENTIFIER);
    }

    /**
     * @return string
     */
    public function getSdkVersion(): string
    {
        return $this->get(self::SDK_VERSION, Constants::SDK_VERSION);
    }

    /**
     * @return string|null
     */
    public function getApiUrl(): ?string
    {
        return $this->get(self::API_URL);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function setHttpClient(array $options): void
    {
        if (isset($options[self::HTTP_CLIENT])) {
            $client = $options[self::HTTP_CLIENT];
            if (!($client instanceof ClientInterface)) {
                throw new \InvalidArgumentException(sprintf(
                    '%s configuration value must be of type %s',
                    self::HTTP_CLIENT,
                    ClientInterface::class
                ));
            }
            $this->set(self::HTTP_CLIENT, $client);
        }
    }

    /**
     * @return \Psr\Http\Client\ClientInterface|null
     */
    public function getHttpClient(): ?ClientInterface
    {
        return $this->get(self::HTTP_CLIENT);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function setLogger(array $options): void
    {
        if (isset($options[self::LOGGER])) {
            $logger = $options[self::LOGGER];
            if (!($logger instanceof LoggerInterface)) {
                throw new \InvalidArgumentException(sprintf(
                    '%s configuration value must be of type %s',
                    self::LOGGER,
                    LoggerInterface::class
                ));
            }
            $this->set(self::LOGGER, $logger);
        }
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->get(self::LOGGER);
    }
}
