# Contributing

After cloning the repository, run `composer install` to install dependencies.

> See <https://getcomposer.org/> for Composer installation instructions.

## Testing

Running the tests:

```shell
./vendor/bin/phpunit
```

See [.travis.yml](.travis.yml) for PHP versions we currently support.

## Style guide

We follow the [PSR-2 Style Guide](https://www.php-fig.org/psr/psr-2/), which is
configured in [.phpcs.xml](.phpcs.xml) and can be verified by running:

```shell
./vendor/bin/phpcs
```
