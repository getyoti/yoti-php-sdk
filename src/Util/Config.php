<?php

declare(strict_types=1);

namespace Yoti\Util;

use Psr\Http\Client\ClientInterface;
use Yoti\Constants;

/**
 * Provides SDK configuration.
 */
class Config
{
    /** Connect API URL key */
    const CONNECT_API_URL = 'connect.api.url';

    /** SDK identifier key */
    const SDK_IDENTIFIER = 'sdk.identifier';

    /** SDK version key */
    const SDK_VERSION = 'sdk.version';

    /** HTTP client key */
    const HTTP_CLIENT = 'http.client';

    /**
     * @var array
     */
    private $options = [];

    /**
     * Configuration settings include the following options:
     *
     * - Config::HTTP_CLIENT 'http.client' (\Psr\Http\Client\ClientInterface)
     * - Config::CONNECT_API_URL 'connect.api.url' (string)
     * - Config::SDK_IDENTIFIER 'sdk.identifier' (string)
     * - Config::SDK_VERSION 'sdk.version' (string)
     *
     * Example of creating config:
     *
     *     $config = new Config([
     *         Config::HTTP_CLIENT => new \Yoti\Http\Client(),
     *     ]);
     */
    public function __construct(array $options = [])
    {
        $this->validateKeys($options);
        $this->setStringValue(self::CONNECT_API_URL, $options);
        $this->setStringValue(self::SDK_IDENTIFIER, $options);
        $this->setStringValue(self::SDK_VERSION, $options);
        $this->setHttpClient($options);
    }

    /**
     * @param array $options
     *
     * @throws \InvalidArgumentException
     */
    private function validateKeys($options): void
    {
        $invalidKeys = array_diff(
            array_keys($options),
            [
                self::CONNECT_API_URL,
                self::SDK_IDENTIFIER,
                self::SDK_VERSION,
                self::HTTP_CLIENT,
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
     * @param array $options
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
     *
     * @return mixed
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
     * @return string
     */
    public function getConnectApiUrl(): string
    {
        return $this->get(self::CONNECT_API_URL, Constants::CONNECT_API_URL);
    }

    /**
     * @param array $options
     */
    private function setHttpClient(array $options): void
    {
        if (isset($options[self::HTTP_CLIENT])) {
            $value = $options[self::HTTP_CLIENT];
            if (!($value instanceof ClientInterface)) {
                throw new \InvalidArgumentException(sprintf(
                    '%s configuration value must be of type %s',
                    self::HTTP_CLIENT,
                    ClientInterface::class
                ));
            }
            $this->set(self::HTTP_CLIENT, $value);
        }
    }

    /**
     * @return \Psr\Http\Client\ClientInterface|null
     */
    public function getHttpClient(): ?ClientInterface
    {
        return $this->get(self::HTTP_CLIENT);
    }
}
