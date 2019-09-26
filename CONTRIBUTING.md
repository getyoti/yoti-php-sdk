# Contributing

After cloning the repository, run `composer install` to install dependencies.

> See <https://getcomposer.org/> for Composer installation instructions.

## Testing

Running the tests:

```shell
composer test
```

See [.travis.yml](.travis.yml) for PHP versions we currently support.

Generate and view coverage report by running:

```shell
composer coverage-html
open ./coverage/report/index.html
```

To generate clover report run:

```shell
composer coverage-clover
```

## Style guide

We follow the [PSR-12 Style Guide](https://www.php-fig.org/psr/psr-12/), which
is configured in [.phpcs.xml.dist](.phpcs.xml.dist).

Additional checks are configured in [.php_cs.dist](.php_cs.dist) - See
<https://cs.symfony.com/> for further information.

Coding style can be verified by running:

```shell
composer lint
```

> Note: Windows users that have enabled `core.autocrlf` in their git
  configuration can disable the `Generic.Files.LineEndings` rule by
  copying [.phpcs.xml.dist](.phpcs.xml.dist) file to `.phpcs.xml`
  and adding an exclusion. This sniff will always be included on
  Travis CI.
