# vim: ts=4:sw=4:noexpandtab!:

BASEDIR  := $(shell pwd)
COMPOSER := $(shell which composer)

help:
	@echo "---------------------------------------------"
	@echo "List of available targets:"
	@echo "  composer-install         - Installs composer dependencies."
	@echo "  proto-generate           - Generate PHP classes from proto files."
	@echo "  php-lint                 - Runs PHP Syntax check."
	@echo "  help                     - Shows this dialog."
	@exit 0

all: install test

install: composer-install proto-generate

test: proto-generate php-lint

composer-install:
ifdef COMPOSER
	php $(COMPOSER) install --prefer-source --no-interaction;
else
	@echo "Composer not found !!"
	@echo
	@echo "curl -sS https://getcomposer.org/installer | php"
	@echo "mv composer.phar /usr/local/bin/composer"
endif

proto-generate:
	php $(BASEDIR)/vendor/bin/protobuf -o $(BASEDIR)/src $(BASEDIR)/src/*.proto -i $(BASEDIR)/src

proto-remove:
	find $(BASEDIR)/src -type f -name "*.php" -exec rm {} \;

php-lint:
	find $(BASEDIR)/src -type f -name "*.php" -exec php -l {} \;

.PHONY: composer-install proto-generate proto-clean php-lint help