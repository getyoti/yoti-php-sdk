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

We follow the [PSR-12 Style Guide](https://www.php-fig.org/psr/psr-12/), which is
configured in [.phpcs.xml](.phpcs.xml) and can be verified by running:

```shell
./vendor/bin/phpcs
```

Additional checks are configured in [.php_cs.dist](.php_cs.dist) and can be verified by running:

```shell
./vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run --stop-on-violation --using-cache=no
```

See <https://cs.symfony.com/> for further information.
