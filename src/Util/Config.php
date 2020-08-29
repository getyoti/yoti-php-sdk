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

    /** Type error message */
    private const TYPE_ERROR_MESSAGE = '%s configuration value must be of type %s';

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

        foreach ($options as $key => $value) {
            if ($value === null) {
                continue;
            }
            switch ($key) {
                case self::HTTP_CLIENT:
                    $this->setHttpClient($value);
                    break;
                case self::LOGGER:
                    $this->setLogger($value);
                    break;
                default:
                    $this->setStringValue($key, $value);
                    break;
            }
        }
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
     * @param mixed $value
     */
    private function setStringValue(string $key, $value): void
    {
        Validation::notEmptyString($value, sprintf('%s configuration value', $key));
        $this->set($key, $value);
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
     *
     * @return mixed
     */
    private function get(string $key)
    {
        return $this->options[$key] ?? null;
    }

    /**
     * @return string
     */
    public function getSdkIdentifier(): string
    {
        return $this->get(self::SDK_IDENTIFIER) ?? Constants::SDK_IDENTIFIER;
    }

    /**
     * @return string
     */
    public function getSdkVersion(): string
    {
        return $this->get(self::SDK_VERSION) ?? Constants::SDK_VERSION;
    }

    /**
     * @return string|null
     */
    public function getApiUrl(): ?string
    {
        return $this->get(self::API_URL);
    }

    /**
     * @param mixed $client
     */
    private function setHttpClient($client): void
    {
        if (!($client instanceof ClientInterface)) {
            throw new \InvalidArgumentException(sprintf(
                self::TYPE_ERROR_MESSAGE,
                self::HTTP_CLIENT,
                ClientInterface::class
            ));
        }

        $this->set(self::HTTP_CLIENT, $client);
    }

    /**
     * @return \Psr\Http\Client\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        if ($this->get(self::HTTP_CLIENT) === null) {
            $this->set(self::HTTP_CLIENT, new Client());
        }
        return $this->get(self::HTTP_CLIENT);
    }

    /**
     * @param mixed $logger
     */
    private function setLogger($logger): void
    {
        if (!($logger instanceof LoggerInterface)) {
            throw new \InvalidArgumentException(sprintf(
                self::TYPE_ERROR_MESSAGE,
                self::LOGGER,
                LoggerInterface::class
            ));
        }

        $this->set(self::LOGGER, $logger);
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        if ($this->get(self::LOGGER) === null) {
            $this->set(self::LOGGER, new Logger());
        }
        return $this->get(self::LOGGER);
    }
}
