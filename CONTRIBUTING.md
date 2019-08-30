# Contributing

After cloning the repository, run `composer install` to install dependencies.

> See <https://getcomposer.org/> for Composer installation instructions.

## Testing

Running the tests:

```shell
composer test
```

See [.travis.yml](.travis.yml) for PHP versions we currently support.

## Style guide

We follow the [PSR-12 Style Guide](https://www.php-fig.org/psr/psr-12/), which
is configured in [.phpcs.xml](.phpcs.xml).

Additional checks are configured in [.php_cs.dist](.php_cs.dist) - See
<https://cs.symfony.com/> for further information.

Coding style can be verified by running:

```shell
composer lint
```
